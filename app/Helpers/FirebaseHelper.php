<?php

namespace App\Helpers;

use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Storage;

class FirebaseHelper
{
    private static function accessToken(): string
    {
        $client = new GoogleClient();
        $client->setAuthConfig(storage_path(env('FIREBASE_CREDENTIALS')));
        $client->addScope('https://www.googleapis.com/auth/datastore');

        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }

    private static function httpClient(): GuzzleClient
    {
        return new GuzzleClient([
            'headers' => [
                'Authorization' => 'Bearer ' . self::accessToken(),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    private static function formatFields(array $fields): array
    {
        $formatted = [];

        foreach ($fields as $key => $value) {
            if (is_int($value)) {
                $formatted[$key] = ['integerValue' => $value];
            } elseif (is_string($value)) {
                $formatted[$key] = ['stringValue' => $value];
            } elseif (is_bool($value)) {
                $formatted[$key] = ['booleanValue' => $value];
            } elseif (is_array($value)) {
                // Tangani array of string (misal: readBy)
                $formatted[$key] = [
                    'arrayValue' => [
                        'values' => array_map(fn($v) => ['stringValue' => (string)$v], $value)
                    ]
                ];
            } else {
                $formatted[$key] = ['stringValue' => json_encode($value)];
            }
        }

        return $formatted;
    }

    public static function addDocument(string $collection, array $fields): void
    {
        $projectId = env('FIREBASE_PROJECT_ID');
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/$collection";

        $client = self::httpClient();
        $client->post($url, ['json' => ['fields' => self::formatFields($fields)]]);
    }

    public static function addMessageToRoom(string $roomId, array $fields): void
    {
        $path = "rooms/$roomId/messages";
        self::addDocument($path, $fields);
    }

    public static function getDocument(string $documentPath): ?array
    {
        $projectId = env('FIREBASE_PROJECT_ID');
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/$documentPath";

        try {
            $client = self::httpClient();
            $response = $client->get($url);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function markMessageAsRead(string $roomId, string $messageId, string $userId): void
    {
        $doc = self::getDocument("rooms/$roomId/messages/$messageId");

        if (!$doc || !isset($doc['fields'])) return;

        $readBy = [];
        if (isset($doc['fields']['readBy']['arrayValue']['values'])) {
            $readBy = array_map(
                fn($v) => $v['stringValue'] ?? '',
                $doc['fields']['readBy']['arrayValue']['values']
            );
        }

        if (in_array($userId, $readBy)) return; // sudah dibaca

        $readBy[] = $userId;

        // Update ke Firestore
        $projectId = env('FIREBASE_PROJECT_ID');
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/rooms/$roomId/messages/$messageId?updateMask.fieldPaths=readBy";

        $payload = [
            'fields' => [
                'readBy' => [
                    'arrayValue' => [
                        'values' => array_map(fn($v) => ['stringValue' => $v], $readBy)
                    ]
                ]
            ]
        ];

        $client = self::httpClient();
        $client->patch($url, ['json' => $payload]);
    }

    public static function createRoom(string $roomId, string $name): void
    {
        $projectId = env('FIREBASE_PROJECT_ID');

        // Cek apakah dokumen sudah ada
        $existingRoom = self::getDocument("rooms/$roomId");
        if ($existingRoom !== null) {
            // Dokumen sudah ada, tidak perlu menambahkan lagi
            return;
        }
        
        $url = "https://firestore.googleapis.com/v1/projects/$projectId/databases/(default)/documents/rooms?documentId=$roomId";

        $fields = [
            'id' => $roomId,
            'name' => $name,
        ];

        $client = self::httpClient();
        $client->post($url, ['json' => ['fields' => self::formatFields($fields)]]);
    }


}

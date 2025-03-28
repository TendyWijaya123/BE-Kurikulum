<?php

namespace App\Imports;

use App\Models\Sksu;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SksuImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    public function collection(Collection $rows)
    {
        $kurikulum = Auth::user()->activeKurikulum();
        $currentProfile = null;
        $kompetensiList = [];

        foreach ($rows as $index => $row) {
            $validator = Validator::make($row->toArray(), [
                'profil_lulusan'   => 'required|string|max:255',
                'kualifikasi'      => 'required|string|max:255',
                'kategori'         => 'required|string|max:255',
                'kompetensi_kerja' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                $this->errors[] = [
                    'row' => $index + 2,
                    'errors' => $validator->errors()->all()
                ];
                continue;
            }

            if (!empty($row['profil_lulusan'])) {
                $this->saveProfile($currentProfile, $kompetensiList, $kurikulum);
                $currentProfile = collect($row)->only(['profil_lulusan', 'kualifikasi', 'kategori'])->toArray();
                $kompetensiList = array_filter([$row['kompetensi_kerja']]);
            } else {
                $kompetensiList[] = $row['kompetensi_kerja'];
            }
        }

        $this->saveProfile($currentProfile, $kompetensiList, $kurikulum);
    }

    private function saveProfile($profile, $kompetensi, $kurikulum)
    {
        if ($profile && !empty($kompetensi)) {
            Sksu::create([
                'profil_lulusan' => $profile['profil_lulusan'],
                'kualifikasi' => $profile['kualifikasi'],
                'kategori' => $profile['kategori'],
                'kompetensi_kerja' => implode("\n", $kompetensi),
                'kurikulum_id' => $kurikulum->id,
            ]);
        }
    }
}

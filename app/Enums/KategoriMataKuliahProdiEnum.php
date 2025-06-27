<?php

namespace App\Enums;

enum KategoriMataKuliahProdiEnum: string
{
    case WAJIB = 'Wajib';
    case PILIHAN = 'Pilihan';
    case MAGANG = 'Magang';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

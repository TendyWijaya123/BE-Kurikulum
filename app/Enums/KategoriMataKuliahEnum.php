<?php

namespace App\Enums;

enum KategoriMataKuliahEnum: string
{
    case INSTITUSI = 'Institusi';
    case PRODI = 'Prodi';
    case NASIONAL = 'Nasional';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

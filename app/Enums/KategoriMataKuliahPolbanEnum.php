<?php

namespace App\Enums;

enum KategoriMataKuliahPolbanEnum: string
{
    case POLBAN = 'Polban';
    case POLBANPF = 'Polban P/F';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

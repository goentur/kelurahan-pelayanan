<?php

namespace App\Enums;

enum JabatanJenis: string
{
    case KEPALA = 'KEPALA';
    case PETUGAS = 'PETUGAS';

    public function label(): string
    {
        return match ($this) {
            self::KEPALA => 'KEPALA',
            self::PETUGAS => 'PETUGAS',
        };
    }
    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'label' => $case->value,
            'value' => $case->value,
        ], self::cases());
    }
}

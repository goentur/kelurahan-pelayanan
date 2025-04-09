<?php

namespace App\Enums;

enum PegawaiStatus: string
{
    case AKTIF = 'AKTIF';
    case TIDAK = 'TIDAK';

    public function label(): string
    {
        return match ($this) {
            self::AKTIF => 'AKTIF',
            self::TIDAK => 'TIDAK',
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

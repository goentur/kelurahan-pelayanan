<?php

namespace App\Enums;

enum PenyampaianTipe: string
{
    case TERSAMPAIKAN = 'TERSAMPAIKAN';
    case TIDAK = 'TIDAK';
    case KEMBALI = 'KEMBALI';

    public function label(): string
    {
        return match ($this) {
            self::TERSAMPAIKAN => 'TERSAMPAIKAN',
            self::TIDAK => 'TIDAK',
            self::KEMBALI => 'KEMBALI',
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

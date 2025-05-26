<?php

namespace App\Enums\Pelayanan;

enum PerubahanAlamatStatus: string
{
    case MENUNGGU = 'MENUNGGU';
    case SELESAI = 'SELESAI';
    case TOLAK = 'TOLAK';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 'MENUNGGU',
            self::SELESAI => 'SELESAI',
            self::TOLAK => 'TOLAK',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::MENUNGGU => 'blue',
            self::SELESAI => 'green',
            self::TOLAK => 'red',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'label' => $case->value,
            'value' => $case->value,
            'color' => $case->color(),
        ], self::cases());
    }
}

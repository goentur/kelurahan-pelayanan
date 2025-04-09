<?php

namespace App\Enums;

enum PenyampaianStatus: string
{
    case SIMPAN = 'SIMPAN';
    case TERLAPOR = 'TERLAPOR';

    public function label(): string
    {
        return match ($this) {
            self::SIMPAN => 'SIMPAN',
            self::TERLAPOR => 'TERLAPOR',
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

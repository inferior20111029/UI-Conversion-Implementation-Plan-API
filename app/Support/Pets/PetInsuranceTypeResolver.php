<?php

namespace App\Support\Pets;

class PetInsuranceTypeResolver
{
    public static function resolve(?string $petType): array
    {
        return match (strtolower((string) $petType)) {
            'dog' => [
                'key' => 'dog_insurance',
                'label' => '犬用保險',
                'description' => '優先對應犬隻可投保的醫療與意外方案。',
                'eligible_species' => ['dog'],
            ],
            'cat' => [
                'key' => 'cat_insurance',
                'label' => '貓用保險',
                'description' => '優先對應貓咪可投保的醫療與住院方案。',
                'eligible_species' => ['cat'],
            ],
            default => [
                'key' => 'general_pet_insurance',
                'label' => '通用寵物保險',
                'description' => '依寵物資料與方案條件自動比對可投保的保險方案。',
                'eligible_species' => [],
            ],
        };
    }

    public static function label(?string $petType): string
    {
        return match (strtolower((string) $petType)) {
            'dog' => '狗',
            'cat' => '貓',
            default => '寵物',
        };
    }
}

<?php

namespace App\Support\Pets;

class PetBreedMatcher
{
    private const ALIAS_MAP = [
        '柴犬' => 'shibainu',
        'shiba' => 'shibainu',
        'shibainu' => 'shibainu',
        '黃金獵犬' => 'goldenretriever',
        'goldenretriever' => 'goldenretriever',
        '拉布拉多' => 'labradorretriever',
        'labrador' => 'labradorretriever',
        'labradorretriever' => 'labradorretriever',
        '法鬥' => 'frenchbulldog',
        'frenchbulldog' => 'frenchbulldog',
        '柯基' => 'welshcorgi',
        'corgi' => 'welshcorgi',
        'welshcorgi' => 'welshcorgi',
        '貴賓' => 'poodle',
        'poodle' => 'poodle',
        '英短' => 'britishshorthair',
        'britishshorthair' => 'britishshorthair',
        '布偶' => 'ragdoll',
        'ragdoll' => 'ragdoll',
        '波斯' => 'persian',
        'persian' => 'persian',
        '緬因' => 'mainecoon',
        'mainecoon' => 'mainecoon',
        '摺耳' => 'scottishfold',
        'scottishfold' => 'scottishfold',
    ];

    public static function matches(array $supportedBreeds, ?string $petBreed): bool
    {
        $normalizedPetBreed = self::normalize($petBreed);

        if ($normalizedPetBreed === null || $supportedBreeds === []) {
            return true;
        }

        foreach ($supportedBreeds as $supportedBreed) {
            if (self::normalize((string) $supportedBreed) === $normalizedPetBreed) {
                return true;
            }
        }

        return false;
    }

    public static function normalize(?string $breed): ?string
    {
        if ($breed === null) {
            return null;
        }

        $normalized = mb_strtolower(trim($breed));
        $normalized = preg_replace('/[^\pL\pN]+/u', '', $normalized) ?? '';

        if ($normalized === '') {
            return null;
        }

        return self::ALIAS_MAP[$normalized] ?? $normalized;
    }
}

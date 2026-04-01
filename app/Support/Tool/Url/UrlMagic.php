<?php

declare(strict_types=1);

namespace App\Support\Tool\Url;

class UrlMagic
{
    /**
     * 取得短連結
     *
     * @param string $url 網址
     *
     * @return string
     */
    public static function short(string $url): string
    {
        if (!str($url)->isUrl()) {
            return '';
        }

        $builder = new \AshAllenDesign\ShortURL\Classes\Builder();
        $shortURLObject = $builder->destinationUrl($url)->deactivateAt(now()->addDay())->make();

        return $shortURLObject->default_short_url;
    }
}

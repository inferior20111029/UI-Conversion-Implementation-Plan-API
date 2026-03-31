<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('whereLike', function (string|array $attribute, string $searchTerm): Builder {
            if (is_array($attribute)) {
                $attribute = array_map('strval', $attribute);

                /** @var Builder $this */
                return $this->where(function (Builder $query) use ($attribute, $searchTerm) {
                    foreach ($attribute as $attributeName) {
                        $query->orWhere($attributeName, 'LIKE', "%{$searchTerm}%");
                    }
                });
            }

            /** @var Builder $this */
            return $this->where($attribute, 'LIKE', "%{$searchTerm}%");
        });

        Builder::macro('orWhereLike', function (string $attribute, string $searchTerm): Builder {
            /** @var Builder $this */
            return $this->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
        });
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

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
        Validator::extend('image_or_url', function ($attribute, $value, $parameters, $validator) {
            return $validator->validateImage($attribute, $value) || filter_var($value, FILTER_VALIDATE_URL);
        });

        Validator::replacer('image_or_url', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'The :attribute field must be an image file or a valid URL.');
        });
    }
}

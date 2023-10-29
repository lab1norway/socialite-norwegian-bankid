<?php
namespace Lab1\SocialiteNorwegianBankid;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Lab1\SocialiteNorwegianBankid\Two\SocialiteNorwegianBankidProvider;
use Laravel\Socialite\Contracts\Factory;

class SocialiteNorwegianBankidServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/norwegian-bankid.php' => config_path('norwegian-bankid.php'),
        ]);

        $socialite = $this->app->make(Factory::class);

        $socialite->extend('bankid', function () use ($socialite) {
            $config = config('norwegian-bankid.criipto');

            return $socialite->buildProvider(SocialiteNorwegianBankidProvider::class, $config);
        });

    }

    public function register(): void
    {
        
    }
}
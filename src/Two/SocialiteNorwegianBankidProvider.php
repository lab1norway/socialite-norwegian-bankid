<?php

namespace Lab1\SocialiteNorwegianBankid\Two;

use GuzzleHttp\RequestOptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class SocialiteNorwegianBankidProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = [
        'openid'
    ];

    protected $parameters = [
        'acr_values' => 'urn:grn:authn:no:bankid:substantial',
        'response_type' => 'code'
    ];

    protected $scopeSeparator = ' ';


    protected function getAuthUrl($state): string
    {
        return $this->buildAuthUrlFromBase(config('norwegian-bankid.criipto.base_uri') . '/oauth2/authorize', $state);
    }

    protected function getTokenUrl(): string
    {
        return config('norwegian-bankid.criipto.base_uri') . '/oauth2/token';
    }

    protected function getUserByToken($token): array
    {
        $response = $this->getHttpClient()
            ->get(config('norwegian-bankid.criipto.base_uri') . '/oauth2/userinfo',
                [
                    RequestOptions::HEADERS => [
                        'cache-control' => 'no-cache',
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
                ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user): User
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'socialno'),
            'dob' => Arr::get($user, 'dateofbirth'),
            'name' => Arr::get($user, 'name'),
        ]);
    }
}
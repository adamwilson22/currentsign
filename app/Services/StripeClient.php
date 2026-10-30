<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class StripeClient
{
    private function base(): string
    {
        return 'https://api.stripe.com/v1';
    }

    private function secret(): string
    {
        $secret = config('stripe.secret');
        if (! $secret) {
            throw new RuntimeException('STRIPE_SECRET_KEY is not configured.');
        }

        return $secret;
    }

    private function http()
    {
        $client = Http::withBasicAuth($this->secret(), '');
        // Local Windows often lacks a CA bundle; never skip verify in production.
        if (app()->environment('local')) {
            $client = $client->withOptions(['verify' => false]);
        }

        return $client;
    }

    public function post(string $path, array $form): array
    {
        $response = $this->http()
            ->asForm()
            ->post($this->base() . $path, $form);

        $json = $response->json() ?? [];
        if (! $response->successful()) {
            $message = $json['error']['message'] ?? ('Stripe error HTTP ' . $response->status());
            throw new RuntimeException($message);
        }

        return $json;
    }

    public function get(string $path, array $query = []): array
    {
        $response = $this->http()->get($this->base() . $path, $query);

        $json = $response->json() ?? [];
        if (! $response->successful()) {
            $message = $json['error']['message'] ?? ('Stripe error HTTP ' . $response->status());
            throw new RuntimeException($message);
        }

        return $json;
    }
}

<?php

namespace App\Services;

class CroTrailRegistration
{
    public function price(string $distance): ?float
    {
        $price = config("crotrail.prices.$distance");

        return is_numeric($price) && (float) $price > 0 ? (float) $price : null;
    }

    public function nonMemberSurcharge(): ?float
    {
        $configured = config('crotrail.non_member_surcharge');
        if (is_numeric($configured) && (float) $configured >= 0) {
            return (float) $configured;
        }

        return filter_var(config('crotrail.test_mode'), FILTER_VALIDATE_BOOLEAN) ? 10.0 : null;
    }

    public function totalPrice(string $distance, string $membershipType): ?float
    {
        $base = $this->price($distance);
        $surcharge = $this->nonMemberSurcharge();

        return $base === null || $surcharge === null
            ? null
            : $base + ($membershipType === 'none' ? $surcharge : 0);
    }

    public function methods(): array
    {
        $methods = [];
        if (filled(config('services.stripe.key')) && filled(config('services.stripe.secret')) && filled(config('services.stripe.webhook_secret'))) {
            $methods[] = 'stripe';
        }

        $mode = config('paypal.mode') === 'live' ? 'live' : 'sandbox';
        if (filled(config("paypal.$mode.client_id")) && filled(config("paypal.$mode.client_secret"))) {
            $methods[] = 'paypal';
        }

        return $methods;
    }

    public function isOpen(string $distance): bool
    {
        $testMode = filter_var(config('crotrail.test_mode'), FILTER_VALIDATE_BOOLEAN);
        $localTest = $testMode && app()->environment('local', 'testing')
            && config('crotrail.email_mode') === 'log'
            && str_starts_with((string) config('services.stripe.key'), 'pk_test_')
            && str_starts_with((string) config('services.stripe.secret'), 'sk_test_');
        if ($testMode && ! $localTest) {
            return false;
        }
        $emailReady = $localTest || (filled(config('crotrail.brevo_key')) && filled(config('crotrail.sender_email')));
        $documentsReady = $localTest || filter_var(config('crotrail.document_policy_approved'), FILTER_VALIDATE_BOOLEAN);

        return filter_var(config('crotrail.registration_open'), FILTER_VALIDATE_BOOLEAN)
            && $this->price($distance) !== null
            && $this->nonMemberSurcharge() !== null
            && $this->methods() !== []
            && $emailReady
            && $documentsReady;
    }
}

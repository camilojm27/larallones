<?php

declare(strict_types=1);

namespace App\Dto\Lettr;

use Illuminate\Contracts\Support\Arrayable;

final readonly class SubscriptionCreatedData implements Arrayable
{
    public function __construct(
        public string $amount,
        public string $billingInterval,
        public string $helpUrl,
        public string $name,
        public string $nextBillingDate,
        public string $planName,
        public string $privacyPolicyUrl,
        public string $storeAddress,
        public string $storeName,
        public string $supportEmail,
        public string $termsOfUseUrl,
    ) {}

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'billing_interval' => $this->billingInterval,
            'help_url' => $this->helpUrl,
            'name' => $this->name,
            'next_billing_date' => $this->nextBillingDate,
            'plan_name' => $this->planName,
            'privacy_policy_url' => $this->privacyPolicyUrl,
            'store_address' => $this->storeAddress,
            'store_name' => $this->storeName,
            'support_email' => $this->supportEmail,
            'terms_of_use_url' => $this->termsOfUseUrl,
        ];
    }
}

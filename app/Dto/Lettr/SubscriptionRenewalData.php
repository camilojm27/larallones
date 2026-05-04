<?php

declare(strict_types=1);

namespace App\Dto\Lettr;

use Illuminate\Contracts\Support\Arrayable;

final readonly class SubscriptionRenewalData implements Arrayable
{
    public function __construct(
        public string $amount,
        public string $helpUrl,
        public string $name,
        public string $nextBillingDate,
        public string $planName,
        public string $privacyPolicyUrl,
        public string $renewalDate,
        public string $storeAddress,
        public string $storeName,
        public string $supportEmail,
        public string $termsOfUseUrl,
    ) {}

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'help_url' => $this->helpUrl,
            'name' => $this->name,
            'next_billing_date' => $this->nextBillingDate,
            'plan_name' => $this->planName,
            'privacy_policy_url' => $this->privacyPolicyUrl,
            'renewal_date' => $this->renewalDate,
            'store_address' => $this->storeAddress,
            'store_name' => $this->storeName,
            'support_email' => $this->supportEmail,
            'terms_of_use_url' => $this->termsOfUseUrl,
        ];
    }
}

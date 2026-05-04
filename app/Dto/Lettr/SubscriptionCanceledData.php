<?php

declare(strict_types=1);

namespace App\Dto\Lettr;

use Illuminate\Contracts\Support\Arrayable;

final readonly class SubscriptionCanceledData implements Arrayable
{
    public function __construct(
        public string $accessEndDate,
        public string $helpUrl,
        public string $name,
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
            'access_end_date' => $this->accessEndDate,
            'help_url' => $this->helpUrl,
            'name' => $this->name,
            'plan_name' => $this->planName,
            'privacy_policy_url' => $this->privacyPolicyUrl,
            'store_address' => $this->storeAddress,
            'store_name' => $this->storeName,
            'support_email' => $this->supportEmail,
            'terms_of_use_url' => $this->termsOfUseUrl,
        ];
    }
}

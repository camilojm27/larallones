<?php

declare(strict_types=1);

namespace App\Dto\Lettr;

use Illuminate\Contracts\Support\Arrayable;

final readonly class TrialExpiredData implements Arrayable
{
    public function __construct(
        public string $expiryTime,
        public string $helpUrl,
        public string $inviterName,
        public string $name,
        public string $privacyPolicyUrl,
        public string $storeAddress,
        public string $storeName,
        public string $supportEmail,
        public string $termsOfUseUrl,
        public string $trialEndDate,
    ) {}

    public function toArray(): array
    {
        return [
            'expiry_time' => $this->expiryTime,
            'help_url' => $this->helpUrl,
            'inviter_name' => $this->inviterName,
            'name' => $this->name,
            'privacy_policy_url' => $this->privacyPolicyUrl,
            'store_address' => $this->storeAddress,
            'store_name' => $this->storeName,
            'support_email' => $this->supportEmail,
            'terms_of_use_url' => $this->termsOfUseUrl,
            'trial_end_date' => $this->trialEndDate,
        ];
    }
}

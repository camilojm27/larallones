<?php

declare(strict_types=1);

namespace App\Dto\Lettr;

use Illuminate\Contracts\Support\Arrayable;

final readonly class PaymentDeclinedData implements Arrayable
{
    public function __construct(
        public string $helpUrl,
        public string $name,
        public string $privacyPolicyUrl,
        public string $storeAddress,
        public string $storeName,
        public string $supportEmail,
        public string $termsOfUseUrl,
        public string $updatePaymentUrl,
    ) {}

    public function toArray(): array
    {
        return [
            'help_url' => $this->helpUrl,
            'name' => $this->name,
            'privacy_policy_url' => $this->privacyPolicyUrl,
            'store_address' => $this->storeAddress,
            'store_name' => $this->storeName,
            'support_email' => $this->supportEmail,
            'terms_of_use_url' => $this->termsOfUseUrl,
            'update_payment_url' => $this->updatePaymentUrl,
        ];
    }
}

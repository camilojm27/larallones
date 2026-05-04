<?php

declare(strict_types=1);

namespace App\Dto\Lettr;

use Illuminate\Contracts\Support\Arrayable;

final readonly class InvoiceEmailData implements Arrayable
{
    public function __construct(
        public string $amount,
        public string $dueDate,
        public string $helpUrl,
        public string $invoiceDate,
        public string $invoiceNumber,
        public string $invoiceStatus,
        public string $name,
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
            'due_date' => $this->dueDate,
            'help_url' => $this->helpUrl,
            'invoice_date' => $this->invoiceDate,
            'invoice_number' => $this->invoiceNumber,
            'invoice_status' => $this->invoiceStatus,
            'name' => $this->name,
            'privacy_policy_url' => $this->privacyPolicyUrl,
            'store_address' => $this->storeAddress,
            'store_name' => $this->storeName,
            'support_email' => $this->supportEmail,
            'terms_of_use_url' => $this->termsOfUseUrl,
        ];
    }
}

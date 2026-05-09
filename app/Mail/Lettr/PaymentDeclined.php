<?php

declare(strict_types=1);

namespace App\Mail\Lettr;

use App\Dto\Lettr\PaymentDeclinedData;
use Illuminate\Mail\Mailables\Envelope;
use Lettr\Laravel\Mail\LettrMailable;

class PaymentDeclined extends LettrMailable
{
    protected ?string $templateSlug = 'payment-declined';

    /**
     * Path to the local HTML template file (for preview/testing).
     */
    protected ?string $templateHtmlPath = 'resources/templates/lettr/payment-declined.html';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly PaymentDeclinedData $data,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Declined',
        );
    }

    /**
     * Get the merge tags for this mailable.
     *
     * @return array<string, mixed>
     */
    public function withMergeTags(): array
    {
        return $this->data->toArray();
    }
}

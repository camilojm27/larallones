<?php

declare(strict_types=1);

namespace App\Mail\Lettr;

use App\Dto\Lettr\SubscriptionRenewalData;
use Illuminate\Mail\Mailables\Envelope;
use Lettr\Laravel\Mail\LettrMailable;

class SubscriptionRenewal extends LettrMailable
{
    protected ?string $templateSlug = 'subscription-renewal';

    /**
     * Path to the local HTML template file (for preview/testing).
     */
    protected ?string $templateHtmlPath = 'resources/templates/lettr/subscription-renewal.html';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly SubscriptionRenewalData $data,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Renewal',
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

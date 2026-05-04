<?php

declare(strict_types=1);

namespace App\Mail\Lettr;

use Illuminate\Mail\Mailables\Envelope;
use Lettr\Laravel\Mail\LettrMailable;
use App\Dto\Lettr\SubscriptionCreatedData;

class SubscriptionCreated extends LettrMailable
{
    protected ?string $templateSlug = 'subscription-created';

    /**
     * Path to the local HTML template file (for preview/testing).
     */
    protected ?string $templateHtmlPath = 'resources/templates/lettr/subscription-created.html';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly SubscriptionCreatedData $data,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Created',
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

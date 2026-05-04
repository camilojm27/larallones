<?php

declare(strict_types=1);

namespace App\Mail\Lettr;

use Illuminate\Mail\Mailables\Envelope;
use Lettr\Laravel\Mail\LettrMailable;
use App\Dto\Lettr\TrialExpiredData;

class TrialExpired extends LettrMailable
{
    protected ?string $templateSlug = 'trial-expired';

    /**
     * Path to the local HTML template file (for preview/testing).
     */
    protected ?string $templateHtmlPath = 'resources/templates/lettr/trial-expired.html';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly TrialExpiredData $data,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Trial Expired',
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

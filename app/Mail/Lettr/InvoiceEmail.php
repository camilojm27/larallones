<?php

declare(strict_types=1);

namespace App\Mail\Lettr;

use App\Dto\Lettr\InvoiceEmailData;
use Illuminate\Mail\Mailables\Envelope;
use Lettr\Laravel\Mail\LettrMailable;

class InvoiceEmail extends LettrMailable
{
    protected ?string $templateSlug = 'invoice-email';

    /**
     * Path to the local HTML template file (for preview/testing).
     */
    protected ?string $templateHtmlPath = 'resources/templates/lettr/invoice-email.html';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly InvoiceEmailData $data,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice Email',
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

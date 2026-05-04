<?php

declare(strict_types=1);

namespace App\Mail\Lettr;

use Illuminate\Mail\Mailables\Envelope;
use Lettr\Laravel\Mail\LettrMailable;
use App\Dto\Lettr\TeamInvitationData;

class TeamInvitation extends LettrMailable
{
    protected ?string $templateSlug = 'team-invitation';

    /**
     * Path to the local HTML template file (for preview/testing).
     */
    protected ?string $templateHtmlPath = 'resources/templates/lettr/team-invitation.html';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly TeamInvitationData $data,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Team Invitation',
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

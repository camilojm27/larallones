<?php

declare(strict_types=1);

namespace App\Dto\Lettr;

use Illuminate\Contracts\Support\Arrayable;

final readonly class TeamInvitationData implements Arrayable
{
    public function __construct(
        public string $email,
        public string $expiryTime,
        public string $helpUrl,
        public string $inviterName,
        public string $privacyPolicyUrl,
        public string $storeAddress,
        public string $storeName,
        public string $supportEmail,
        public string $teamInviteUrl,
        public string $teamName,
        public string $termsOfUseUrl,
    ) {}

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'expiry_time' => $this->expiryTime,
            'help_url' => $this->helpUrl,
            'inviter_name' => $this->inviterName,
            'privacy_policy_url' => $this->privacyPolicyUrl,
            'store_address' => $this->storeAddress,
            'store_name' => $this->storeName,
            'support_email' => $this->supportEmail,
            'team_invite_url' => $this->teamInviteUrl,
            'team_name' => $this->teamName,
            'terms_of_use_url' => $this->termsOfUseUrl,
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Dtos;

use App\Models\Community;

final readonly class CommunityDto
{
    public function __construct(
        public int $owner_id,
        public string $name,
        public string $slug,
        public ?string $description,
        public ?bool $verified,
        public ?string $logo,
        public ?string $banner,
        public ?string $NIT,
        public ?string $legal_representative,
        public ?string $address,
        public ?string $phone_number,
        public ?string $email,
        public ?string $website,
        public ?int $id = null,
    ) {}

    public static function fromModel(Community $community): self
    {
        return new self(
            owner_id: $community->owner_id,
            name: $community->name,
            slug: $community->slug,
            description: $community->description,
            verified: $community->verified,
            logo: $community->logo,
            banner: $community->banner,
            NIT: $community->NIT,
            legal_representative: $community->legal_representative,
            address: $community->address,
            phone_number: $community->phone_number,
            email: $community->email,
            website: $community->website,
            id: $community->id,
        );
    }
}

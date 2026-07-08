<?php

namespace Uspacy\SDK\DTOs\Users;

/**
 * Payload for importing/activating a registered user by email invite.
 *
 * Common fields are typed; anything else can be passed via {@see $extra}.
 */
final class ImportRegisteredUserDTO
{
    /**
     * @param  array<int, mixed>|null  $roles
     * @param  array<int, mixed>|null  $departmentsIds
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $email = null,
        public ?array $roles = null,
        public ?array $departmentsIds = null,
        public array $extra = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(array_filter([
            'email' => $this->email,
            'roles' => $this->roles,
            'departmentsIds' => $this->departmentsIds,
        ], static fn ($value) => $value !== null), $this->extra);
    }
}

<?php

namespace Uspacy\SDK\DTOs\Users;

/**
 * Payload for updating a user's roles. Serializes to `{ roles: [...] }`.
 */
final class UpdateRolesDTO
{
    /**
     * @param  array<int, mixed>  $roles
     */
    public function __construct(
        public array $roles = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return ['roles' => $this->roles];
    }
}

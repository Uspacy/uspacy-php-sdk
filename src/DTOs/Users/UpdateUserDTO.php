<?php

namespace Uspacy\SDK\DTOs\Users;

/**
 * Payload for updating a user.
 *
 * Common fields are typed; portal-specific custom fields can be passed via
 * {@see $extra}. Only non-null values are sent.
 */
final class UpdateUserDTO
{
    /**
     * @param  array<int, mixed>|null  $roles
     * @param  array<int, mixed>|null  $departmentsIds
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $patronymic = null,
        public ?string $position = null,
        public ?string $specialization = null,
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
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'patronymic' => $this->patronymic,
            'position' => $this->position,
            'specialization' => $this->specialization,
            'roles' => $this->roles,
            'departmentsIds' => $this->departmentsIds,
        ], static fn ($value) => $value !== null), $this->extra);
    }
}

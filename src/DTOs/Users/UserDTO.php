<?php

namespace Uspacy\SDK\DTOs\Users;

/**
 * A Uspacy user.
 *
 * The user entity is extensible (custom fields), so the documented fields are
 * typed while the complete payload is preserved in {@see $raw}.
 */
final class UserDTO
{
    /**
     * @param  array<int, mixed>  $email
     * @param  array<int, mixed>  $phone
     * @param  array<int, mixed>  $roles
     * @param  array<int, mixed>  $departmentsIds
     * @param  array<string, mixed>  $raw
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $authUserId,
        public readonly ?string $firstName,
        public readonly ?string $lastName,
        public readonly ?string $patronymic,
        public readonly ?string $position,
        public readonly ?bool $active,
        public readonly ?bool $registered,
        public readonly array $email,
        public readonly array $phone,
        public readonly array $roles,
        public readonly array $departmentsIds,
        public readonly ?bool $isOnline,
        public readonly ?int $lastSeenAt,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            authUserId: $data['authUserId'] ?? null,
            firstName: $data['firstName'] ?? null,
            lastName: $data['lastName'] ?? null,
            patronymic: $data['patronymic'] ?? null,
            position: $data['position'] ?? null,
            active: $data['active'] ?? null,
            registered: $data['registered'] ?? null,
            email: $data['email'] ?? [],
            phone: $data['phone'] ?? [],
            roles: $data['roles'] ?? [],
            departmentsIds: $data['departmentsIds'] ?? [],
            isOnline: $data['isOnline'] ?? null,
            lastSeenAt: $data['lastSeenAt'] ?? null,
            raw: $data,
        );
    }
}

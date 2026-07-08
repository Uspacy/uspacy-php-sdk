<?php

namespace Uspacy\SDK\DTOs\Users;

/**
 * Query filter for listing users (mirrors the JS `IUserFilter`).
 *
 * Only common fields are typed; anything else can be passed via {@see $extra}.
 */
final class UserFilterDTO
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?int $page = null,
        public ?int $list = null,
        public ?string $show = null,
        public ?string $search = null,
        public array $extra = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(array_filter([
            'page' => $this->page,
            'list' => $this->list,
            'show' => $this->show,
            'search' => $this->search,
        ], static fn ($value) => $value !== null), $this->extra);
    }
}

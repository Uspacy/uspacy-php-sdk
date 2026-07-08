<?php

namespace Uspacy\SDK\DTOs\Users;

/**
 * Search parameters for the users search endpoint (mirrors the JS `ISearchUsersDto`).
 *
 * Only common fields are typed; anything else can be passed via {@see $extra}.
 */
final class SearchUsersDTO
{
    /**
     * @param  array<string, mixed>  $extra
     */
    public function __construct(
        public ?string $search = null,
        public ?int $page = null,
        public ?int $list = null,
        public array $extra = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_merge(array_filter([
            'search' => $this->search,
            'page' => $this->page,
            'list' => $this->list,
        ], static fn ($value) => $value !== null), $this->extra);
    }
}

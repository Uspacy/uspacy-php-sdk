<?php

namespace App\DTOs\CRM;

class KanbanDTO
{
    public readonly string $status;

    public readonly int $reasonId;

    public readonly int $stageId;

    public function __construct(string $status, int $reasonId, int $stageId)
    {
        $this->status = $status;
        $this->reasonId = $reasonId;
        $this->stageId = $stageId;
    }
}
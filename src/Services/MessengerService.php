<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Messages\UpdateMessageStatusDTO;
use Uspacy\SDK\Http\Client\Requests\Messenger\CreateChatRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\CreateExternalLineRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\CreateMessageRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\DeleteMessageRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\GetExternalLineRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\GetExternalLinesRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\GetMessageRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\GoToMessageRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\UpdateExternalLineRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\UpdateMessageRequest;
use Uspacy\SDK\Http\Client\Requests\Messenger\UpdateMessageStatusRequest;

/**
 * Messenger service.
 *
 * Facade over the dedicated `messenger/v1` request classes, which carry domain
 * specific behaviour (e.g. duplicate-message detection on message creation).
 */
class MessengerService extends Service
{
    public function getExternalLines(): Response
    {
        return $this->http->connector()->send(new GetExternalLinesRequest());
    }

    public function getExternalLine(string $id): Response
    {
        return $this->http->connector()->send(new GetExternalLineRequest($id));
    }

    public function createExternalLine(array $payload): Response
    {
        return $this->http->connector()->send(new CreateExternalLineRequest($payload));
    }

    public function updateExternalLine(string $id, array $payload): Response
    {
        return $this->http->connector()->send(new UpdateExternalLineRequest($id, $payload));
    }

    public function createChat(array $payload): Response
    {
        return $this->http->connector()->send(new CreateChatRequest($payload));
    }

    public function createMessage(array $payload): Response
    {
        return $this->http->connector()->send(new CreateMessageRequest($payload));
    }

    public function getMessage(string $messageId): Response
    {
        return $this->http->connector()->send(new GetMessageRequest($messageId));
    }

    public function updateMessage(array $payload): Response
    {
        return $this->http->connector()->send(new UpdateMessageRequest($payload));
    }

    public function deleteMessage(string $messageId): Response
    {
        return $this->http->connector()->send(new DeleteMessageRequest($messageId));
    }

    public function goToMessage(string $messageId): Response
    {
        return $this->http->connector()->send(new GoToMessageRequest($messageId));
    }

    public function updateMessageStatus(string $messageId, UpdateMessageStatusDTO $payload): Response
    {
        return $this->http->connector()->send(new UpdateMessageStatusRequest($messageId, $payload));
    }
}

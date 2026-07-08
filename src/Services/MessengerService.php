<?php

namespace Uspacy\SDK\Services;

use Saloon\Http\Response;
use Uspacy\SDK\DTOs\Messages\UpdateMessageStatusDTO;
use Uspacy\SDK\DTOs\Messenger\ChatDTO;
use Uspacy\SDK\DTOs\Messenger\QuickAnswerDTO;
use Uspacy\SDK\DTOs\Messenger\UserSettingsDTO;
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
    private const NAMESPACE = '/messenger/v1';

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

    /**
     * Get chats.
     *
     * @param  array  $params  chat fetch params (status, type, ...)
     * @return array<int, ChatDTO>
     */
    public function getChats(array $params = []): array
    {
        $data = $this->http->get(self::NAMESPACE . '/chats', $params)->json() ?? [];

        return array_map([ChatDTO::class, 'fromArray'], array_filter($data, 'is_array'));
    }

    /**
     * Get a cursor-paginated page of external chats for one status bucket.
     *
     * @param  array  $params  chat fetch params (cursor, limit, status, ...)
     */
    public function getExternalChatsPage(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/chats', array_merge($params, ['type' => 'EXTERNAL']));
    }

    /**
     * Get messages of a chat.
     *
     * @param  array  $params  message fetch params (chatId, timestamps, ...)
     */
    public function getMessages(array $params = []): Response
    {
        return $this->http->get(self::NAMESPACE . '/messages/', $params);
    }

    /**
     * Get the pinned messages of a chat.
     *
     * @param  int|string  $chatId
     */
    public function getPinnedMessages($chatId): Response
    {
        return $this->http->get(self::NAMESPACE . '/messages/getPinnedMessages/', ['chatId' => $chatId]);
    }

    /**
     * Mark all messages in a chat as read.
     *
     * @param  int|string  $chatId
     */
    public function readAllMessages($chatId): Response
    {
        return $this->http->post(self::NAMESPACE . '/messages/readAll/', ['chatId' => $chatId]);
    }

    /**
     * Create a messenger widget.
     */
    public function createWidget(array $data): Response
    {
        return $this->http->post(self::NAMESPACE . '/widgets', $data);
    }

    /**
     * Get messenger widgets.
     */
    public function getWidgets(?int $limit = null, ?int $page = null): Response
    {
        return $this->http->get(self::NAMESPACE . '/widgets', ['limit' => $limit, 'page' => $page]);
    }

    /**
     * Update a messenger widget.
     *
     * @param  int|string  $id
     */
    public function updateWidget($id, array $data): Response
    {
        return $this->http->patch(self::NAMESPACE . "/widgets/{$id}", $data);
    }

    /**
     * Delete a messenger widget.
     *
     * @param  int|string  $id
     */
    public function deleteWidget($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/widgets/{$id}");
    }

    /**
     * Get quick answers (quick replies).
     *
     * @param  array  $params  filter params
     * @return array<int, QuickAnswerDTO>
     */
    public function getQuickAnswers(array $params = []): array
    {
        $data = $this->http->get(self::NAMESPACE . '/quick-replies', $params)->json() ?? [];

        return array_map([QuickAnswerDTO::class, 'fromArray'], array_filter($data, 'is_array'));
    }

    /**
     * Get a quick answer by id.
     *
     * @param  int|string  $id
     */
    public function getQuickAnswerById($id): QuickAnswerDTO
    {
        return QuickAnswerDTO::fromArray($this->http->get(self::NAMESPACE . "/quick-replies/{$id}")->json() ?? []);
    }

    /**
     * Create a quick answer.
     */
    public function createQuickAnswer(array $data): QuickAnswerDTO
    {
        return QuickAnswerDTO::fromArray($this->http->post(self::NAMESPACE . '/quick-replies', $data)->json() ?? []);
    }

    /**
     * Update a quick answer.
     *
     * @param  int|string  $id
     */
    public function updateQuickAnswer($id, array $data): QuickAnswerDTO
    {
        return QuickAnswerDTO::fromArray($this->http->patch(self::NAMESPACE . "/quick-replies/{$id}", $data)->json() ?? []);
    }

    /**
     * Update a quick answer's status.
     *
     * @param  int|string  $id
     */
    public function updateQuickAnswerStatus($id, string $status): QuickAnswerDTO
    {
        return QuickAnswerDTO::fromArray($this->http->patch(self::NAMESPACE . "/quick-replies/{$id}/status/{$status}")->json() ?? []);
    }

    /**
     * Delete a quick answer.
     *
     * @param  int|string  $id
     */
    public function deleteQuickAnswer($id): Response
    {
        return $this->http->delete(self::NAMESPACE . "/quick-replies/{$id}");
    }

    /**
     * Get the entities related to a chat.
     *
     * @param  int|string  $chatId
     */
    public function getChatRelations($chatId): Response
    {
        return $this->http->get(self::NAMESPACE . '/chat-entity-relations', [
            'chatId' => $chatId,
            'entityType' => 'task',
        ]);
    }

    /**
     * Get the chats related to a task/entity.
     *
     * @param  int|string  $entityId
     */
    public function getTaskRelations($entityId): Response
    {
        return $this->http->get(self::NAMESPACE . '/chat-entity-relations/chats-by-entity', [
            'entityId' => $entityId,
            'entityType' => 'task',
        ]);
    }

    /**
     * Create a chat/entity relation.
     *
     * @param  int|string  $chatId
     * @param  int|string  $entityId
     */
    public function createChatRelation($chatId, $entityId): Response
    {
        return $this->http->post(self::NAMESPACE . '/chat-entity-relations', [
            'chatId' => $chatId,
            'entityId' => $entityId,
            'entityType' => 'task',
        ]);
    }

    /**
     * Delete a chat/entity relation.
     *
     * @param  int|string  $chatId
     * @param  int|string  $entityId
     */
    public function deleteChatRelation($chatId, $entityId): Response
    {
        return $this->http->delete(
            endpoint: self::NAMESPACE . '/chat-entity-relations/by-entity',
            query: [
                'chatId' => $chatId,
                'entityId' => $entityId,
                'entityType' => 'task',
            ],
        );
    }

    /**
     * Get the current user's messenger settings.
     */
    public function getSettings(): UserSettingsDTO
    {
        return UserSettingsDTO::fromArray($this->http->get(self::NAMESPACE . '/user-settings')->json() ?? []);
    }

    /**
     * Update the current user's messenger settings.
     */
    public function updateSettings(array $settings): UserSettingsDTO
    {
        return UserSettingsDTO::fromArray($this->http->patch(self::NAMESPACE . '/user-settings', $settings)->json() ?? []);
    }
}

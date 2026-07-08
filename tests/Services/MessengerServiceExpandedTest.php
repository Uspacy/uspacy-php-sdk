<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class MessengerServiceExpandedTest extends TestCase
{
    public function test_chats_and_messages(): void
    {
        $this->sdk->messenger()->getChats(['status' => 'open']);
        $this->assertRequestSent('GET', '/messenger/v1/chats', null, ['status' => 'open']);

        $this->sdk->messenger()->getExternalChatsPage(['cursor' => 'abc']);
        $this->assertRequestSent('GET', '/messenger/v1/chats', null, ['cursor' => 'abc', 'type' => 'EXTERNAL']);

        $this->sdk->messenger()->getMessages(['chatId' => 5]);
        $this->assertRequestSent('GET', '/messenger/v1/messages/', null, ['chatId' => 5]);

        $this->sdk->messenger()->getPinnedMessages(5);
        $this->assertRequestSent('GET', '/messenger/v1/messages/getPinnedMessages/', null, ['chatId' => 5]);

        $this->sdk->messenger()->readAllMessages(5);
        $this->assertRequestSent('POST', '/messenger/v1/messages/readAll/', ['chatId' => 5]);
    }

    public function test_widgets(): void
    {
        $this->sdk->messenger()->createWidget(['name' => 'W']);
        $this->assertRequestSent('POST', '/messenger/v1/widgets', ['name' => 'W']);

        $this->sdk->messenger()->getWidgets(10, 1);
        $this->assertRequestSent('GET', '/messenger/v1/widgets', null, ['limit' => 10, 'page' => 1]);

        // Null optional params must be dropped, never sent as empty query values.
        $this->sdk->messenger()->getWidgets();
        $this->assertRequestSent('GET', '/messenger/v1/widgets', null, []);

        $this->sdk->messenger()->updateWidget(3, ['name' => 'W2']);
        $this->assertRequestSent('PATCH', '/messenger/v1/widgets/3', ['name' => 'W2']);

        $this->sdk->messenger()->deleteWidget(3);
        $this->assertRequestSent('DELETE', '/messenger/v1/widgets/3');
    }

    public function test_quick_answers(): void
    {
        $this->sdk->messenger()->getQuickAnswers(['q' => 'hi']);
        $this->assertRequestSent('GET', '/messenger/v1/quick-replies', null, ['q' => 'hi']);

        $this->sdk->messenger()->getQuickAnswerById('a1');
        $this->assertRequestSent('GET', '/messenger/v1/quick-replies/a1');

        $this->sdk->messenger()->updateQuickAnswerStatus('a1', 'active');
        $this->assertRequestSent('PATCH', '/messenger/v1/quick-replies/a1/status/active');

        $this->sdk->messenger()->deleteQuickAnswer('a1');
        $this->assertRequestSent('DELETE', '/messenger/v1/quick-replies/a1');
    }

    public function test_relations(): void
    {
        $this->sdk->messenger()->getChatRelations(5);
        $this->assertRequestSent('GET', '/messenger/v1/chat-entity-relations', null, ['chatId' => 5, 'entityType' => 'task']);

        $this->sdk->messenger()->getTaskRelations(9);
        $this->assertRequestSent('GET', '/messenger/v1/chat-entity-relations/chats-by-entity', null, ['entityId' => 9, 'entityType' => 'task']);

        $this->sdk->messenger()->createChatRelation(5, 9);
        $this->assertRequestSent('POST', '/messenger/v1/chat-entity-relations', ['chatId' => 5, 'entityId' => 9, 'entityType' => 'task']);

        $this->sdk->messenger()->deleteChatRelation(5, 9);
        $this->assertRequestSent('DELETE', '/messenger/v1/chat-entity-relations/by-entity', null, ['chatId' => 5, 'entityId' => 9, 'entityType' => 'task']);
    }

    public function test_settings(): void
    {
        $this->sdk->messenger()->getSettings();
        $this->assertRequestSent('GET', '/messenger/v1/user-settings');

        $this->sdk->messenger()->updateSettings(['sound' => false]);
        $this->assertRequestSent('PATCH', '/messenger/v1/user-settings', ['sound' => false]);
    }
}

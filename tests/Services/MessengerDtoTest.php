<?php

namespace Uspacy\SDK\Tests\Services;

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Uspacy\SDK\DTOs\Messenger\ChatDTO;
use Uspacy\SDK\DTOs\Messenger\QuickAnswerDTO;
use Uspacy\SDK\DTOs\Messenger\UserSettingsDTO;
use Uspacy\SDK\Http\Client\Requests\GetRequest;
use Uspacy\SDK\Http\Client\Requests\PatchRequest;
use Uspacy\SDK\Tests\TestCase;

class MessengerDtoTest extends TestCase
{
    public function test_get_chats_hydrates_chat_dtos(): void
    {
        $this->mockGet([
            ['id' => 'c1', 'name' => 'General', 'type' => 'INTERNAL', 'members' => [1, 2], 'pinned' => true],
            ['id' => 'c2', 'name' => 'Support', 'type' => 'EXTERNAL', 'members' => [3]],
        ]);

        $chats = $this->sdk->messenger()->getChats(['status' => 'active']);

        $this->assertCount(2, $chats);
        $this->assertInstanceOf(ChatDTO::class, $chats[0]);
        $this->assertSame('General', $chats[0]->name);
        $this->assertTrue($chats[0]->pinned);
        $this->assertSame([1, 2], $chats[0]->members);
    }

    public function test_quick_answers_hydrate(): void
    {
        $this->mockGet(['id' => 'q1', 'name' => 'Greeting', 'message' => 'Hi!', 'status' => 'active', 'ownerId' => 7]);

        $answer = $this->sdk->messenger()->getQuickAnswerById('q1');

        $this->assertInstanceOf(QuickAnswerDTO::class, $answer);
        $this->assertSame('Greeting', $answer->name);
        $this->assertSame('Hi!', $answer->message);
        $this->assertSame(7, $answer->ownerId);
    }

    public function test_update_quick_answer_status_returns_dto(): void
    {
        $this->sdk->withMockClient(new MockClient([
            PatchRequest::class => MockResponse::make(['id' => 'q1', 'status' => 'inactive'], 200),
        ]));

        $answer = $this->sdk->messenger()->updateQuickAnswerStatus('q1', 'inactive');

        $this->assertInstanceOf(QuickAnswerDTO::class, $answer);
        $this->assertSame('inactive', $answer->status);
    }

    public function test_settings_hydrate(): void
    {
        $this->mockGet([
            'id' => 's1',
            'authUserId' => 7,
            'isInternalMsgSoundEnabled' => true,
            'isExternalMsgSoundEnabled' => false,
        ]);

        $settings = $this->sdk->messenger()->getSettings();

        $this->assertInstanceOf(UserSettingsDTO::class, $settings);
        $this->assertTrue($settings->isInternalMsgSoundEnabled);
        $this->assertFalse($settings->isExternalMsgSoundEnabled);
        $this->assertSame(7, $settings->authUserId);
    }

    public function test_empty_body_does_not_throw(): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make('', 204),
        ]));

        $this->assertSame([], $this->sdk->messenger()->getChats());
        $this->assertSame([], $this->sdk->messenger()->getQuickAnswers());
        $this->assertInstanceOf(UserSettingsDTO::class, $this->sdk->messenger()->getSettings());
    }

    private function mockGet(array $payload): void
    {
        $this->sdk->withMockClient(new MockClient([
            GetRequest::class => MockResponse::make($payload, 200),
        ]));
    }
}

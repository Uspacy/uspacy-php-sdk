<?php

namespace Uspacy\SDK\Http\Client;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Uspacy\SDK\Services\ActivitiesService;
use Uspacy\SDK\Services\AuthService;
use Uspacy\SDK\Services\CommentsService;
use Uspacy\SDK\Services\CrmService;
use Uspacy\SDK\Services\DepartmentsService;
use Uspacy\SDK\Services\EmailsService;
use Uspacy\SDK\Services\FilesService;
use Uspacy\SDK\Services\GroupsService;
use Uspacy\SDK\Services\MessengerService;
use Uspacy\SDK\Services\NewsFeedService;
use Uspacy\SDK\Services\SettingsService;
use Uspacy\SDK\Services\SmartObjectsService;
use Uspacy\SDK\Services\TasksService;
use Uspacy\SDK\Services\UsersService;

class UspacySDK extends Connector
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;

    private ?HttpClient $httpClient = null;

    public function __construct(
        protected string $apiUrl,
        protected string $apiToken,
    ) {
        $this->authenticate(new TokenAuthenticator($this->apiToken));
        $this->initRetryConfig();
    }

    public function resolveBaseUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * Shared, verb-oriented HTTP client used by every service facade.
     */
    public function http(): HttpClient
    {
        return $this->httpClient ??= new HttpClient($this);
    }

    public function crm(): CrmService
    {
        return new CrmService($this->http());
    }

    public function smartObjects(): SmartObjectsService
    {
        return new SmartObjectsService($this->http());
    }

    public function tasks(): TasksService
    {
        return new TasksService($this->http());
    }

    public function activities(): ActivitiesService
    {
        return new ActivitiesService($this->http());
    }

    public function users(): UsersService
    {
        return new UsersService($this->http());
    }

    public function departments(): DepartmentsService
    {
        return new DepartmentsService($this->http());
    }

    public function groups(): GroupsService
    {
        return new GroupsService($this->http());
    }

    public function comments(): CommentsService
    {
        return new CommentsService($this->http());
    }

    public function newsFeed(): NewsFeedService
    {
        return new NewsFeedService($this->http());
    }

    public function emails(): EmailsService
    {
        return new EmailsService($this->http());
    }

    public function files(): FilesService
    {
        return new FilesService($this->http());
    }

    public function messenger(): MessengerService
    {
        return new MessengerService($this->http());
    }

    public function settings(): SettingsService
    {
        return new SettingsService($this->http());
    }

    public function auth(): AuthService
    {
        return new AuthService($this->http());
    }

    private function initRetryConfig(): void
    {
        $this->tries = \config('uspacy-sdk.retry.tries', 3);
        $this->retryInterval = \config('uspacy-sdk.retry.interval', 1000);
    }
}

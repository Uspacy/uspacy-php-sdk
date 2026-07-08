<?php

namespace Uspacy\SDK\Http\Client;

use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Uspacy\SDK\Services\ActivitiesService;
use Uspacy\SDK\Services\AuthService;
use Uspacy\SDK\Services\CommentsService;
use Uspacy\SDK\Services\CrmDocumentTemplatesService;
use Uspacy\SDK\Services\CrmEntityService;
use Uspacy\SDK\Services\CrmFunnelsService;
use Uspacy\SDK\Services\CrmProductsCategoryService;
use Uspacy\SDK\Services\CrmProductsForEntityService;
use Uspacy\SDK\Services\CrmProductsPriceTypesService;
use Uspacy\SDK\Services\CrmProductsService;
use Uspacy\SDK\Services\CrmProductsTaxesService;
use Uspacy\SDK\Services\CrmProductsUnitService;
use Uspacy\SDK\Services\CrmRequisitesService;
use Uspacy\SDK\Services\CrmService;
use Uspacy\SDK\Services\CrmStagesService;
use Uspacy\SDK\Services\DepartmentsService;
use Uspacy\SDK\Services\EmailsService;
use Uspacy\SDK\Services\FilesService;
use Uspacy\SDK\Services\GroupsService;
use Uspacy\SDK\Services\HistoryService;
use Uspacy\SDK\Services\InvatesService;
use Uspacy\SDK\Services\MessengerService;
use Uspacy\SDK\Services\NewsFeedService;
use Uspacy\SDK\Services\NotificationsService;
use Uspacy\SDK\Services\OAuthClientsService;
use Uspacy\SDK\Services\ProfileService;
use Uspacy\SDK\Services\RolesService;
use Uspacy\SDK\Services\SettingsService;
use Uspacy\SDK\Services\SmartObjectsService;
use Uspacy\SDK\Services\TasksService;
use Uspacy\SDK\Services\TasksTimerService;
use Uspacy\SDK\Services\UsersService;
use Uspacy\SDK\Services\WebhooksService;

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

    public function crmDeals(): CrmEntityService
    {
        return new CrmEntityService($this->http(), 'deals');
    }

    public function crmLeads(): CrmEntityService
    {
        return new CrmEntityService($this->http(), 'leads');
    }

    public function crmContacts(): CrmEntityService
    {
        return new CrmEntityService($this->http(), 'contacts');
    }

    public function crmCompanies(): CrmEntityService
    {
        return new CrmEntityService($this->http(), 'companies');
    }

    public function crmDealsFunnels(): CrmFunnelsService
    {
        return new CrmFunnelsService($this->http(), 'deals');
    }

    public function crmLeadsFunnels(): CrmFunnelsService
    {
        return new CrmFunnelsService($this->http(), 'leads');
    }

    public function crmDealsStages(): CrmStagesService
    {
        return new CrmStagesService($this->http(), 'deals');
    }

    public function crmLeadsStages(): CrmStagesService
    {
        return new CrmStagesService($this->http(), 'leads');
    }

    public function crmProducts(): CrmProductsService
    {
        return new CrmProductsService($this->http());
    }

    public function crmProductsCategories(): CrmProductsCategoryService
    {
        return new CrmProductsCategoryService($this->http());
    }

    public function crmProductsUnits(): CrmProductsUnitService
    {
        return new CrmProductsUnitService($this->http());
    }

    public function crmProductsTaxes(): CrmProductsTaxesService
    {
        return new CrmProductsTaxesService($this->http());
    }

    public function crmProductsPriceTypes(): CrmProductsPriceTypesService
    {
        return new CrmProductsPriceTypesService($this->http());
    }

    public function crmProductsForEntity(): CrmProductsForEntityService
    {
        return new CrmProductsForEntityService($this->http());
    }

    public function crmRequisites(): CrmRequisitesService
    {
        return new CrmRequisitesService($this->http());
    }

    public function crmDocumentTemplates(): CrmDocumentTemplatesService
    {
        return new CrmDocumentTemplatesService($this->http());
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

    public function profile(): ProfileService
    {
        return new ProfileService($this->http());
    }

    public function roles(): RolesService
    {
        return new RolesService($this->http());
    }

    public function webhooks(): WebhooksService
    {
        return new WebhooksService($this->http());
    }

    public function oauthClients(): OAuthClientsService
    {
        return new OAuthClientsService($this->http());
    }

    public function invites(): InvatesService
    {
        return new InvatesService($this->http());
    }

    public function notifications(): NotificationsService
    {
        return new NotificationsService($this->http());
    }

    public function tasksTimer(): TasksTimerService
    {
        return new TasksTimerService($this->http());
    }

    public function history(): HistoryService
    {
        return new HistoryService($this->http());
    }

    private function initRetryConfig(): void
    {
        $this->tries = \config('uspacy-sdk.retry.tries', 3);
        $this->retryInterval = \config('uspacy-sdk.retry.interval', 1000);
    }
}

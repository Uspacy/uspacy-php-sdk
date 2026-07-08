<?php

namespace Uspacy\SDK\Services;

use Uspacy\SDK\Http\Client\HttpClient;

/**
 * Base class for all domain services.
 *
 * Each service owns an API `namespace` (matching the JS SDK services) and issues
 * requests through the shared {@see HttpClient}.
 */
abstract class Service
{
    public function __construct(
        protected HttpClient $http,
    ) {
    }
}

<?php

namespace Uspacy\SDK\Http\Client\Requests\Auth;

final class Tokens
{
    public function __construct(
        public string $token,
        public string $refreshToken,
        public string $expiresIn,
    ) {
    }
}

<?php

namespace App\DTOs;


class RefreshTokenDTO
{
    /**
     * @var int
     */
    public int $expireInSeconds;

    /**
     * @var string
     */
    public string $jwt;

    /**
     * @var string
     */
    public string $refreshToken;

    /**
     * @param  int  $expireInSeconds
     * @param  string  $jwt
     * @param  string  $refreshToken
     */
    public function __construct(
        int $expireInSeconds,
        string $jwt,
        string $refreshToken
    )
    {
        $this->expireInSeconds = $expireInSeconds;
        $this->jwt = $jwt;
        $this->refreshToken = $refreshToken;
    }
}
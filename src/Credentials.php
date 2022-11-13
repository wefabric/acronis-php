<?php

declare(strict_types = 1);

namespace Wefabric\Acronis;

use Carbon\Carbon;

class Credentials
{
    /**
     * @var string
     */
    protected string $clientId = '';

    /**
     * @var string
     */
    protected string $clientSecret = '';

    /**
     * @var string
     */
    protected string $accessToken = '';

    /**
     * @var int
     */
    protected int $expiresOn = 0;

    /**
     * @var Carbon|null
     */
    protected ?Carbon $expiresAt = null;

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param string $accessToken
     */
    public function __construct(string $clientId, string $clientSecret, string $accessToken = '') {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);

        if($accessToken) {
            $this->setAccessToken($accessToken);
        }

    }

    /**
     * @return bool
     */
    public function isExpired(): bool
    {
        if($this->getExpiresAt()) {
            return new Carbon() >= $this->getExpiresAt();
        }
        return true;
    }
    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     */
    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return int
     */
    public function getExpiresOn(): int
    {
        return $this->expiresOn;
    }

    /**
     * @param int $expiresOn
     */
    public function setExpiresOn(int $expiresOn): void
    {
        $this->expiresOn = $expiresOn;
    }

    /**
     * @return Carbon|null
     */
    public function getExpiresAt(): ?Carbon
    {
        return $this->expiresAt;
    }

    /**
     * @param Carbon|null $expiresAt
     */
    public function setExpiresAt(?Carbon $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }
}
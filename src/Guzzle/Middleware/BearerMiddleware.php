<?php

declare(strict_types = 1);

namespace Wefabric\Acronis\Guzzle\Middleware;

use Wefabric\Acronis\BearerToken;
use Wefabric\Acronis\Credentials;
use Wefabric\Acronis\UrlResolver;

final class BearerMiddleware
{
    /**
     * @var Credentials|null
     */
    protected ?Credentials $credentials;

    /**
     * @var UrlResolver|null
     */
    protected ?UrlResolver $urlResolver;

    /**
     * @param UrlResolver $urlResolver
     * @param Credentials $credentials
     */
    public function __construct(UrlResolver $urlResolver, Credentials $credentials)
    {
        $this->setUrlResolver($urlResolver);
        $this->setCredentials($credentials);
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBearerToken(): string
    {
        $credentials = (new BearerToken())->getBearerToken($this->getCredentials(), $this->getUrlResolver());
        $this->setCredentials($credentials);

        return $this->getAccessToken();
    }

    /**
     * @return Credentials|null
     */
    public function getCredentials(): ?Credentials
    {
        return $this->credentials;
    }

    /**
     * @param Credentials|null $credentials
     */
    public function setCredentials(?Credentials $credentials): void
    {
        $this->credentials = $credentials;
    }

    /**
     * @return UrlResolver|null
     */
    public function getUrlResolver(): ?UrlResolver
    {
        return $this->urlResolver;
    }

    /**
     * @param UrlResolver|null $urlResolver
     */
    public function setUrlResolver(?UrlResolver $urlResolver): void
    {
        $this->urlResolver = $urlResolver;
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken(): string
    {
        if(!$this->getCredentials()->getAccessToken() || $this->getCredentials()->isExpired()) {
            $this->getBearerToken();
        }

        return $this->getCredentials()->getAccessToken();
    }

}
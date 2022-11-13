<?php

declare(strict_types = 1);

namespace Wefabric\Acronis;

class UrlResolver
{

    /**
     * @var int
     */
    protected int $apiVersion = 2;

    /**
     * @var string
     */
    protected string $baseUrl = '';


    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->setBaseUrl($baseUrl);
    }

    /**
     * @param string $path
     * @return string
     */
    public function resolve(string $path = ''): string
    {
        return str_replace('{apiVersion}', (string)$this->getApiVersion(), $this->getServerUrl().$path);
    }

    /**
     * @return string
     */
    public function getServerUrl(): string
    {
        return $this->getBaseUrl();
    }

    /**
     * @return int
     */
    public function getApiVersion(): int
    {
        return $this->apiVersion;
    }

    /**
     * @param int $apiVersion
     */
    public function setApiVersion(int $apiVersion): void
    {
        $this->apiVersion = $apiVersion;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl(string $baseUrl): void
    {
        // Remove double slashes
        $baseUrl = preg_replace('{/$}', '', $baseUrl);
        $this->baseUrl = $baseUrl;
    }
}
<?php

declare(strict_types = 1);

namespace Wefabric\Acronis\Guzzle\Middleware\Psr7;

use GuzzleHttp\Psr7\Response;

final class JsonAwareResponse extends Response
{
    /**
     * @var array
     */
    private array $decodedJson = [];

    /**
     * @return array
     */
    public function json(): array
    {
        if ($this->decodedJson) {
            return $this->decodedJson;
        }

        if (str_contains($this->getHeaderLine('Content-Type'), 'application/json')) {
            return $this->decodedJson = json_decode((string)parent::getBody(), true);
        }

        return [];
    }
}
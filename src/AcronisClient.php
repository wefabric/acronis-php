<?php

declare(strict_types = 1);

namespace Wefabric\Acronis;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Wefabric\Acronis\Guzzle\Middleware\BearerMiddleware;
use Wefabric\Acronis\Guzzle\Middleware\Psr7\JsonAwareResponse;

final class AcronisClient
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
     * @var HandlerStack|null
     */
    protected ?HandlerStack $handlerStack = null;

    /**
     * @var Client|null
     */
    protected ?Client $client = null;

    /**
     * @param UrlResolver $urlResolver
     * @param Credentials $credentials
     */
    public function __construct(UrlResolver $urlResolver, Credentials $credentials,)
    {
        $this->setUrlResolver($urlResolver);
        $this->setCredentials($credentials);
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        if(!$this->client) {
            $this->setClient($this->createClient());
        }

        return $this->client;
    }

    /**
     * @param Client $client
     * @return void
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    private function createClient(): Client
    {
        return new Client(['handler' => $this->getHandlerStack()]);
    }

    /**
     * @return HandlerStack
     */
    public function getHandlerStack(): HandlerStack
    {
           if(!$this->handlerStack) {
               $handlerStack = HandlerStack::create();

               $handlerStack->push(Middleware::mapRequest(function (RequestInterface $request){

                   $bearer = new BearerMiddleware($this->getUrlResolver(), $this->getCredentials());

                   $this->setCredentials($bearer->getCredentials());

                   return $request->withHeader('Authorization', 'Bearer '.$bearer->getAccessToken());
               }), 'add_bearer_token');

               $handlerStack->push(Middleware::mapRequest(function (RequestInterface $request){

                   $uri = $request->getUri();
                   if(!$uri->getHost()) {
                       $request = $request->withUri(new Uri($this->getUrlResolver()->resolve($request->getUri()->getPath())));
                   }

                   return $request;
               }), 'add_acronis_uri');

               $handlerStack->push(Middleware::mapResponse(function (ResponseInterface $response) {
                   return new JsonAwareResponse(
                       $response->getStatusCode(),
                       $response->getHeaders(),
                       $response->getBody(),
                       $response->getProtocolVersion(),
                       $response->getReasonPhrase()
                   );
               }), 'json_decode_middleware');

               $this->setHandlerStack($handlerStack);
           }

        return $this->handlerStack;
    }

    /**
     * @param HandlerStack $handlerStack
     * @return void
     */
    public function setHandlerStack(HandlerStack $handlerStack)
    {
        $this->handlerStack = $handlerStack;
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

}
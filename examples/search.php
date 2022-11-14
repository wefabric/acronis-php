<?php


use Wefabric\Acronis\AcronisClient;
use Wefabric\Acronis\Credentials;
use Wefabric\Acronis\UrlResolver;

include  '../vendor/autoload.php';

$domainUrl = '{DOMAIN_URL}';
$clientId = '{CLIENT_ID}';
$clientSecret = '{CLIENT_SECRET}';

$credentials = new Credentials($clientId, $clientSecret);
$urlResolver = new UrlResolver($domainUrl);

$acronis = new AcronisClient($urlResolver, $credentials);

try {
    $response = $acronis->getClient()->request('GET', '/api/2/search', [
        'query' => [
            'text' => '{SEARCH_TEXT}',
            'tenant' => '{TENANT_ID = See clients.php example}'
        ]
    ]);
    dd($response->json());
} catch (\GuzzleHttp\Exception\ClientException $e) {
    dd($e->getResponse()->json());
}



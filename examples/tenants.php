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

$uuids = [
    'ede9f834-70b3-476c-83d9-736f9f8c7dae',
    '0fcd4a69-8a40-4de8-b711-d9c83dc000f7',
    '5138b44f-2d05-422f-8c5e-340332a76597',
];

try {
    $response = $acronis->getClient()->request('GET', '/api/2/tenants', [
        'query' => [
            'uuids' => implode(',', $uuids)
        ]
    ]);
    dd($response->json());
} catch (\GuzzleHttp\Exception\ClientException $e) {
    dd($e->getResponse()->json());
}


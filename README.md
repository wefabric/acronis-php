# Acronis API - PHP SDK
Simple implementation of the Acronis PHP library

## Installation
```bash
composer require wefabric/acronis-php
```

## Usage

Register a third-party application as an API client via the management console of the cloud platform in Acronis.
Copy the domain, client id and client secret and use as followed.

```php
<?php

use Wefabric\Acronis\AcronisClient;
use Wefabric\Acronis\Credentials;
use Wefabric\Acronis\UrlResolver;

require __DIR__."/../vendor/autoload.php";

$domainUrl = '{DOMAIN}';
$clientId = '{CLIENT_ID}';
$clientSecret = '{CLIENT_SECRET}';

$acronis = new AcronisClient(new UrlResolver($domainUrl), new Credentials($clientId, $clientSecret));
$alertsResponse = $acronis->getClient()->get('/api/alert_manager/v1/alerts');

// Retrieve the alerts as decoded Json
$alertsResponse->json()
```

## License
Wefabric Acronis PHP is open-sourced software licensed under the MIT license.
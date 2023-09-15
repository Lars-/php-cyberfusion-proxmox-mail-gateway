# proxmox-mail-gateway

PHP client for Proxmox Mail Gateway API.

Documentation: https://pmg.proxmox.com/pmg-docs/api-viewer/index.html

# Usage

## Example

```php
use Cyberfusion\ProxmoxMGW\Client;
use Cyberfusion\ProxmoxMGW\Endpoints\Config\DkimEndpoint;
use Cyberfusion\ProxmoxMGW\Exceptions\AuthenticationException;
use Cyberfusion\ProxmoxMGW\Models\DkimDomainData;
use Cyberfusion\ProxmoxMGW\Requests\DkimGetRequest;

try {
    $client = new Client('pmgtest.cyberfusion.nl');
    $client->authenticate('apiuser', 'Super secret password.');
} catch (AuthenticationException $e) {
    // Handle authentication error
}

$dkimEndpoint = new DkimEndpoint($client);
$result = $dkimEndpoint->get(new DkimGetRequest('example.com'));
if ($result->failed()) {
    // Handle error
}

/** @var DkimDomainData $dkim */
$dkim = $result->getData('dkim');
// $dkim->domain -> example.com
```

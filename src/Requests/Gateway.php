<?php

namespace YWatchman\ProxmoxMGW\Requests;

use GuzzleHttp\Client;
use YWatchman\ProxmoxMGW\Exceptions\AuthenticationException;
use YWatchman\ProxmoxMGW\Exceptions\InvalidRequestException;

class Gateway
{

    /**
     * PMG hostname
     * @var string $hostname
     */
    protected $hostname;

    /**
     * PMG Port
     * @var int $port
     */
    protected $port;

    /**
     * PMG username
     * @var string $username
     */
    protected $username;

    /**
     * PMG Password
     * @var string $password
     */
    protected $password;

    /**
     * Access request object for retrieving tokens etc.
     * @var \YWatchman\ProxmoxMGW\Requests\Access $access
     */
    protected $access;

    /**
     * Authentication realm
     * @var string $realm
     */
    protected $realm;

    /**
     * Login cookie from PMG
     * @var string $ticket
     */
    protected $ticket = '';

    /**
     * Protection token retrieved from API
     * @var string $csrf
     */
    protected $csrf = '';

    /**
     * Can be json or extjs
     * @var string $responseType
     */
    protected $responseType = 'json';

    /**
     * @var \YWatchman\ProxmoxMGW\Requests\Gateway $client
     */
    protected $client;

    /**
     * @var \GuzzleHttp\Client $httpClient
     */
    protected $httpClient;

    /**
     * Gateway constructor.
     * @param string $hostname
     * @param string $username
     * @param string $password
     * @param string $realm
     * @param int $port
     * @param string $userAgent
     * @throws AuthenticationException
     */
    public function __construct(
        string $hostname,
        string $username,
        string $password,
        string $realm = 'pam',
        int $port = 8006,
        string $userAgent = 'Cyberfusion-PMG-PHP/1.0'
    ) {
        if (empty($username) || empty($password)) {
            // Throw exception if username or password is empty
            throw new AuthenticationException(
                'Missing username or password',
                AuthenticationException::AUTH_MISSING_CREDENTIALS);
        }
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->realm = $realm;
        $this->port = $port;

        $this->client = $this;
        $this->httpClient = new Client([
            'defaults' => [
                'User-Agent' => $userAgent
            ]
        ]);
    }

    /**
     * Get ticket
     *
     * @return string
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Set ticket
     *
     * @param string $ticket
     */
    public function setTicket($ticket): void
    {
        $this->ticket = $ticket;
    }

    /**
     * @return string
     *
     *
     */
    public function getCsrf()
    {
        return $this->csrf;
    }

    /**
     *
     * @param string $csrf
     */
    public function setCsrf($csrf): void
    {
        $this->csrf = $csrf;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRealm()
    {
        return $this->realm;
    }

    /**
     * @return \YWatchman\ProxmoxMGW\Requests\Access
     */
    public function getAccess()
    {
        // Set access and return it.
        return $this->access ?: ($this->access = new Access($this->client));
    }

    /**
     * @param string $endpoint
     * @param string $method
     * @param array $params
     *
     * @return \GuzzleHttp\Message\FutureResponse|\GuzzleHttp\Message\ResponseInterface|null
     *
     *
     * @throws InvalidRequestException
     */
    public function makeRequest(string $endpoint, string $method = 'GET', array $params = [])
    {
        // Get API url and append endpoint
        $url = $this->getRequestUrl($endpoint);

        // Initialise variables for later use
        $cookies = $headers = null;

        if ($this->ticket !== null) {
            $cookies = [
                'PMGAuthCookie' => $this->ticket // Authentication cookie for PMG
            ];

            $headers = ['CSRFPreventionToken' => $this->csrf];
        }

        $params = array_filter($params, function ($value) {
            return $value !== null;
        });

        $options = [
            'verify' => false, // Todo: check debug
            'exceptions' => false,
            'cookies' => $cookies,
            'headers' => $headers,
            'query' => $params
        ];

        switch ($method) {
            case 'GET':
                return $this->httpClient->get($url, $options);
            case 'POST':
                return $this->httpClient->post($url, $options);
            case 'PUT':
                return $this->httpClient->put($url, $options);
            case 'DELETE':
                return $this->httpClient->delete($url, $options);
        }

        throw new InvalidRequestException(
            'Request method is not implemented (yet).',
            InvalidRequestException::GATEWAY_METHOD_NOT_IMPLEMENTED
        );
    }

    /**
     * @param $endpoint
     * @return string
     */
    protected function getRequestUrl($endpoint)
    {
        return sprintf(
            'https://%s:%d/api2/%s/%s',
            $this->hostname,
            $this->port,
            $this->responseType,
            $endpoint
        );
    }
}

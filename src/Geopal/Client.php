<?php

namespace Geoapl\Client;

use Guzzle\Http\Client as GuzzleClient;

class Client
{
    /**
     * @var int
     */
    private $employeeId;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var \Guzzle\Http\Client
     */
    private $guzzleClient;

    const API_URL = 'https://app.geopalsolutions.com/api/';

    /**
     * @param $employeeId
     * @param $privateKey
     * @param null|\Guzzle\Http\Client $guzzleClient
     */
    public function __construct($employeeId, $privateKey, $guzzleClient = null)
    {
        $this->employeeId = $employeeId;
        $this->privateKey = $privateKey;
        if (is_null($guzzleClient)) {
            $this->guzzleClient = new GuzzleClient(self::API_URL);
        }
    }

    public function get($uri, $params = array())
    {
        $this->guzzleClient->get($uri . '?' . http_build_query($params), array());
    }
}

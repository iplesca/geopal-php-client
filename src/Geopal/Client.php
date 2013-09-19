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
     * @param int $employeeId
     * @param string $privateKey
     */
    public function __construct($employeeId, $privateKey)
    {
        $this->employeeId = $employeeId;
        $this->privateKey = $privateKey;
        $this->guzzleClient = new GuzzleClient(self::API_URL);
    }

    public function get($params = array())
    {

    }
}
<?php

namespace Geoapl\Http;

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

    /**
     * Geopal API Url
     */
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
        return $this->guzzleClient->get($uri . '?' . http_build_query($params), array());
    }

    /**
     * @param $verb
     * @param $uri
     */
    public function getHeaders($verb, $uri)
    {
        $timestamp = $this->getTimeStamp();
        $headers['GEOPAL_SIGNATURE'] = $this->getSignature($verb, $uri, $timestamp);
        $headers['GEOPAL_TIMESTAMP'] = $timestamp;
        $headers['GEOPAL_EMPLOYEEID'] = $this->employeeId;
    }

    /**
     * @param $verb
     * @param $uri
     * @param $timestamp
     * @return string
     */
    public function getSignature($verb, $uri, $timestamp)
    {
        $sigText = $verb.$uri.$this->employeeId.$timestamp;
        return base64_encode(hash_hmac('sha256', $sigText, $this->privateKey));
    }

    /**
     * gets the current RFC 2822 formatted date
     *
     * @return string
     */
    private function getTimeStamp()
    {
        return date('r');
    }
}

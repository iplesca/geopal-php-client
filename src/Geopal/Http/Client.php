<?php

namespace Geopal\Http;

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
     * Default Geopal API Url
     */
    const DEFAULT_API_URL = 'https://app.geopalsolutions.com/';

    /**
     * GeoPal API URL
     * @var null|string
     */
    private $apiUrl;

    /**
     * @param $employeeId
     * @param $privateKey
     * @param null|\Guzzle\Http\Client $guzzleClient
     * @param $apiUrl
     */
    public function __construct($employeeId, $privateKey, $guzzleClient = null, $apiUrl = null)
    {
        $this->employeeId = $employeeId;
        $this->privateKey = $privateKey;
        $this->apiUrl = is_null($apiUrl) ? self::DEFAULT_API_URL : $apiUrl;
        if (is_null($guzzleClient)) {
            $this->guzzleClient = new GuzzleClient($this->apiUrl);
        } else {
            $this->guzzleClient = $guzzleClient;
        }
    }

    /**
     * @param $uri
     * @param array $params
     * @return \Guzzle\Http\Message\Response
     */
    public function get($uri, $params = array())
    {
        return $this->guzzleClient->get($uri . '?' . http_build_query($params), $this->getHeaders('get', $uri))->send();
    }

    /**
     * @param $uri
     * @param array $params
     * @param string|null $file The file path of the file to upload or null
     * @return \Guzzle\Http\Message\Response
     */
    public function post($uri, $params = array(), $file = null)
    {
        $request = $this->guzzleClient->post($uri, $this->getHeaders('post', $uri), $params);
        if (!is_null($file) && file_exists($file)) {
            $request->addPostFile('file2upload', $file);
        }
        $response =  $request->send();
        return $response;
    }

    /**
     * @param $uri
     * @param array $params
     * @return \Guzzle\Http\Message\Response
     */
    public function put($uri, $params = array())
    {
        return $this->guzzleClient->put($uri, $this->getHeaders('put', $uri), $params)->send();
    }

    /**
     * @param $verb
     * @param $uri
     * @return array
     */
    public function getHeaders($verb, $uri)
    {
        $timestamp = $this->getTimeStamp();
        $headers = array();
        $headers['GEOPAL_SIGNATURE'] = $this->getSignature($verb, $uri, $timestamp);
        $headers['GEOPAL_TIMESTAMP'] = $timestamp;
        $headers['GEOPAL_EMPLOYEEID'] = $this->employeeId;
        return $headers;
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
     * @param $employeeId
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @param $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
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

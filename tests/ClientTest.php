<?php
namespace Geoapl\Tests;

use Geoapl\Http\Client as GeoPalClient;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var GeoPalClient
     */
    private $client;



    public function setUp()
    {
        $guzzleClient = new GuzzleClient('http://www.test.com/');
        $mock = new MockPlugin();
        $mock->addResponse(new Response(200));
        $guzzleClient->addSubscriber($mock);

        $this->client = new GeoPalClient('', '', $guzzleClient);
    }

    public function testGetSignature()
    {

    }
}
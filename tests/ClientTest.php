<?php
namespace Geoapl\Tests;

use Geopal\Http\Client as GeoPalClient;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var GeoPalClient
     */
    private $geopalClient;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $guzzleClient = new GuzzleClient('http://www.test.com/');
        $mock = new MockPlugin();
        $mock->addResponse(new Response(200, array(), 'test'));
        $guzzleClient->addSubscriber($mock);

        $this->geopalClient = new GeoPalClient(null, null, $guzzleClient);
    }

    /**
     * @return array
     */
    public static function providerGetSignature()
    {
        return array(
            array(1, 'private key', 'get', 'api/jobs/get', 'Fri, 20 Sep 2013 15:46:18 +0200',
                'MzI3ZGU4ZThlMzc3N2Q0YWRmZmNkY2RkOWVhZWM0N2JiY2M5ZGFlZTI1Y2RlYzE4OTZhNDdkY2I2OWMwOGVmMA=='),
            array(1, 'private key', 'get', 'api/jobs/get', 'Fri, 20 Sep 2013 15:46:18 +0200',
                'MzI3ZGU4ZThlMzc3N2Q0YWRmZmNkY2RkOWVhZWM0N2JiY2M5ZGFlZTI1Y2RlYzE4OTZhNDdkY2I2OWMwOGVmMA==')
        );
    }

    /**
     * @dataProvider providerGetSignature
     */
    public function testGetSignature($employeeId, $privateKey, $verb, $uri, $timestamp, $expectedResult)
    {
        $this->geopalClient->setEmployeeId($employeeId);
        $this->geopalClient->setPrivateKey($privateKey);
        $result = $this->geopalClient->getSignature($verb, $uri, $timestamp);
        $this->assertEquals($expectedResult, $result);
    }

    public function testGet()
    {
        $this->assertEquals('test', $this->geopalClient->get('api/jobs/get')->getBody());
    }
}

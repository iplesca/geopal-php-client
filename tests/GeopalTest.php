<?php
namespace Geopal\Tests;

use Geopal\Geopal as GeoPal;
use Geopal\Http\Client as GeoPalClient;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

require_once 'GeopalMock.php';

class GeopalTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var GeoPal
     */
    public $geoPal;

    /**
     * @var int
     */
    private $templateId;

    /**
     * @var int
     */
    private $employeeId;

    /**
     * @var int
     */
    private $jobId;

    /**
     * @var string
     */
    private $dateTimeFrom;

    /**
     * @var string
     */
    private $dateTimeTo;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setTemplateId(29);
        $this->setEmployeeId(2);
        $this->setJobId(226226);
        $this->setDateTimeFrom('2013-01-01 00:00');
        $this->setDateTimeTo('2013-12-31 00:00');

        $this->geoPal = new GeopalMock($this->getEmployeeId(), 'secret_key');
        $this->assertEquals(true, is_object($this->geoPal));
        $this->assertEquals(true, $this->geoPal instanceof GeopalMock ? true : false);
    }

    /**
     * @param int $templateId
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * @return int
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * @param int $employeeId
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @return int
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * @param int $jobId
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return int
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @param string $dateTimeFrom
     */
    public function setDateTimeFrom($dateTimeFrom)
    {
        $this->dateTimeFrom = $dateTimeFrom;
    }

    /**
     * @return string
     */
    public function getDateTimeFrom()
    {
        return $this->dateTimeFrom;
    }

    /**
     * @param string $dateTimeTo
     */
    public function setDateTimeTo($dateTimeTo)
    {
        $this->dateTimeTo = $dateTimeTo;
    }

    /**
     * @return string
     */
    public function getDateTimeTo()
    {
        return $this->dateTimeTo;
    }



    /**
     * tests getJobTemplates()
     */
    public function testGetJobTemplates()
    {
        $templates = $this->geoPal->getJobTemplates();
        $this->assertEquals(
            true,
            is_array($templates)
        );

        $this->assertEquals(
            true,
            count($templates)>0
        );
    }

    /**
     * tests getJobTemplateById()
     */
    public function testGetJobTemplateById()
    {
        $template = $this->geoPal->getJobTemplateById($this->getTemplateId());
        $this->assertEquals(
            $this->getTemplateId(),
            $template['id']
        );
    }

    /**
     * tests testCreateAndAssignJob() with json output
     */
    public function testCreateAndAssignJob()
    {
        $job = $this->geoPal->createAndAssignJob($this->getTemplateId());
        $this->assertEquals(
            true,
            is_array($job)
        );
        $this->assertEquals(
            $this->getTemplateId(),
            $job['job_template']['id']
        );
    }

    /**
     * tests testGetJobById() with json output
     */
    public function testGetJobById()
    {
        $job = $this->geoPal->getJobById($this->getJobId());

        $this->assertEquals(
            true,
            is_array($job)
        );
        $this->assertEquals(
            $this->getJobId(),
            $job['id']
        );
    }


    /**
     * tests testGetJobsBetweenDateRange() with json output
     */
    public function testGetJobsBetweenDateRange()
    {
        $job = $this->geoPal->getJobsBetweenDateRange($this->getDateTimeFrom(), $this->getDateTimeTo());

        $this->assertEquals(
            true,
            is_array($job)
        );
    }


    /**
     * tests getEmployeesList() with json output
     */
    public function testGetEmployeesListJSON()
    {
        $employees = $this->geoPal->getEmployeesList();

        $this->assertEquals(
            true,
            is_array($employees)
        );

        $this->assertEquals(
            $this->getEmployeeId(),
            $employees['id']
        );
    }
}

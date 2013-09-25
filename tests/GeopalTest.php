<?php
namespace Geoapl\Tests;

use Geopal\Geopal as GeoPal;
use Geopal\Http\Client as GeoPalClient;
use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;

class GeopalTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var GeoPal
     */
    public $geoPal;

    /**
     * @var GeopalTest
     */
    private $template_id;

    /**
     * @var GeopalTest
     */
    private $employeeId;

    /**
     * @var GeopalTest
     */
    private $job_id;

    /**
     * @var GeopalTest
     */
    private $dateTimeFrom;

    /**
     * @var GeopalTest
     */
    private $dateTimeTo;

    /**
     *
     */
    protected function setUp()
    {
        parent::setUp();
        $this->geoPal = new GeoPal();
        $this->setTemplateId(29);
        $this->setEmployeeId(2);
        $this->setJobId(226226);
        $this->setDateTimeFrom('2013-01-01 00:00');
        $this->setDateTimeTo('2013-12-31 00:00');
    }

    /**
     * @param \Geoapl\Tests\GeopalTest $template_id
     */
    public function setTemplateId($template_id)
    {
        $this->template_id = $template_id;
    }

    /**
     * @return \Geoapl\Tests\GeopalTest
     */
    public function getTemplateId()
    {
        return $this->template_id;
    }

    /**
     * @param \Geoapl\Tests\GeopalTest $employeeId
     */
    public function setEmployeeId($employeeId)
    {
        $this->employeeId = $employeeId;
    }

    /**
     * @return \Geoapl\Tests\GeopalTest
     */
    public function getEmployeeId()
    {
        return $this->employeeId;
    }

    /**
     * @param \Geoapl\Tests\GeopalTest $job_id
     */
    public function setJobId($job_id)
    {
        $this->job_id = $job_id;
    }

    /**
     * @return \Geoapl\Tests\GeopalTest
     */
    public function getJobId()
    {
        return $this->job_id;
    }

    /**
     * @param \Geoapl\Tests\GeopalTest $dateTimeFrom
     */
    public function setDateTimeFrom($dateTimeFrom)
    {
        $this->dateTimeFrom = $dateTimeFrom;
    }

    /**
     * @return \Geoapl\Tests\GeopalTest
     */
    public function getDateTimeFrom()
    {
        return $this->dateTimeFrom;
    }

    /**
     * @param \Geoapl\Tests\GeopalTest $dateTimeTo
     */
    public function setDateTimeTo($dateTimeTo)
    {
        $this->dateTimeTo = $dateTimeTo;
    }

    /**
     * @return \Geoapl\Tests\GeopalTest
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
            is_array($job['job'])
        );
        $this->assertEquals(
            $this->getTemplateId(),
            $job['job']['job_template']['id']
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

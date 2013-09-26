<?php

/**
 *
 */
namespace Geopal;

use Geopal\Http\Client;
use Geopal\Exceptions\GeopalException;

/**
 * Class Geopal
 * @package Geopal
 */
class Geopal
{
    /**
     * @var int
     */
    protected $employeeId;

    /**
     * @var string
     */
    protected $privateKey;

    /**
     * @var Client
     */
    protected $client;


    /**
     * @param $employeeId
     * @param $privateKey
     */
    public function __construct($employeeId, $privateKey)
    {
        $this->setEmployeeId($employeeId);
        $this->setPrivateKey($privateKey);
        $this->client = new Client($this->getEmployeeId(), $this->getPrivateKey());
    }

    /**
     * @param $array
     * @param $key
     * @return mixed
     * @throws Exceptions\GeopalException
     */
    protected function checkPropertyAndReturn($array, $key)
    {
        if (is_array($array) && array_key_exists($key, $array) && array_key_exists('status', $array)) {
            if ($array['success'] == true) {
                return $array[$key];
            } else {
                throw new GeopalException($array['error_message'], $array['error_code']);
            }
        } else {
            throw new GeopalException('Invalid data or key not found');
        }
    }


    /**
     * @param  $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $employeeId
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
     * @param $privateKey
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    protected function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @param $template_id
     * @return array|bool|float|int|string
     */
    public function createAndAssignJob($template_id)
    {
        $job = $this->client->post('api/jobs/createandassign', array('template_id' => $template_id))->json();
        return $this->checkPropertyAndReturn($job, 'job');
    }

    /**
     * @param $jobId
     * @return mixed
     */
    public function getJobById($jobId)
    {
        $jobs = $this->client->get('api/jobs/get', array('job_id' => $jobId))->json();
        return $this->checkPropertyAndReturn($jobs, 'job');
    }

    /**
     * @param $dateTimeFrom
     * @param $dateTimeTo
     * @return mixed
     */
    public function getJobsBetweenDateRange($dateTimeFrom, $dateTimeTo)
    {
        $jobs = $this->client->get(
            'api/jobsearch/ids',
            array('date_time_from' => $dateTimeFrom, 'date_time_to' => $dateTimeTo)
        )->json();
        return $this->checkPropertyAndReturn($jobs, 'jobs');
    }

    /**
     * @return mixed
     */
    public function getEmployeesList()
    {
        $employees = $this->client->get('api/employees/all')->json();
        return $this->checkPropertyAndReturn($employees, 'employee');
    }

    /**
     * gets job templates
     * @return mixed
     */
    public function getJobTemplates()
    {
        $jobTemplates = $this->client->get('api/jobtemplates/all')->json();
        return $this->checkPropertyAndReturn($jobTemplates, 'job_templates');
    }

    /**
     * gets job template by id
     * @param $template_id
     * @return mixed
     */
    public function getJobTemplateById($template_id)
    {
        $jobTemplates = $this->client->get('api/jobtemplates/get', array('template_id' => $template_id))->json();
        return $this->checkPropertyAndReturn($jobTemplates, 'job_template');
    }
}

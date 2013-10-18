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
            if ($array['status'] == true) {
                return $array[$key];
            } else {
                throw new GeopalException($array['error_message'], $array['error_code']);
            }
        } elseif (is_array($array) && array_key_exists('status', $array)) {
            throw new GeopalException($array['error_message'], $array['error_code']);
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
     * @throws Exceptions\GeopalException
     */
    public function createAndAssignJob($template_id)
    {
        $job = $this->client->post('api/jobs/createandassign', array('template_id' => $template_id))->json();
        return $this->checkPropertyAndReturn($job, 'job');
    }

    /**
     * @param $jobId
     * @return mixed
     * @throws Exceptions\GeopalException
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
     * @throws Exceptions\GeopalException
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
     * @throws Exceptions\GeopalException
     */
    public function getEmployeesList()
    {
        $employees = $this->client->get('api/employees/all')->json();
        return $this->checkPropertyAndReturn($employees, 'employees');
    }

    /**
     * gets job templates
     * @return mixed
     * @throws Exceptions\GeopalException
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
     * @throws Exceptions\GeopalException
     */
    public function getJobTemplateById($template_id)
    {
        $jobTemplates = $this->client->get('api/jobtemplates/get', array('template_id' => $template_id))->json();
        return $this->checkPropertyAndReturn($jobTemplates, 'job_template');
    }


    /**
     * creates an employee
     *
     * @param $username
     * @param $password
     * @param $identifier
     * @param $email
     * @param $mobileNumber
     * @param $firstName
     * @param $lastName
     * @param bool $mobileEmployee
     * @param bool $webEmployee
     * @return mixed
     * @throws Exceptions\GeopalException
     */
    public function createEmployee(
        $username,
        $password,
        $identifier,
        $email,
        $mobileNumber,
        $firstName,
        $lastName,
        $mobileEmployee = true,
        $webEmployee = false
    )
    {
        $employee = $this->client->post(
            'api/employees/create',
            array(
                'username' => $username,
                'password' => $password,
                'identifier' => $identifier,
                'email' => $email,
                'mobile_number' => $mobileNumber,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'mobile_user' => $mobileEmployee,
                'web_user' => $webEmployee
            )
        )->json();
        return $this->checkPropertyAndReturn($employee, 'employee_data');
    }


    /**
     * gets an asset by identifier
     *
     * @param $identifier
     * @return mixed
     */
    public function getAssetByIdentifier($identifier)
    {
        $asset = $this->client->get(
            'api/assets/getbyidentifier',
            array(
                'asset_identifier' => $identifier
            )
        )->json();
        return $this->checkPropertyAndReturn($asset, 'asset');
    }



    /**
     * @param $identifier
     * @param $name
     * @param $assetTemplateId
     * @param $assetStatusId
     * @param $addressLine1
     * @param $addressLine2
     * @param $addressLine3
     * @param $addressCity
     * @param $addressPostalCode
     * @param $addressLat
     * @param $addressLng
     * @param array $fields
     * @return mixed
     * @throws Exceptions\GeopalException
     */
    public function replaceAsset(
        $identifier,
        $name,
        $assetTemplateId,
        $assetStatusId,
        $addressLine1,
        $addressLine2,
        $addressLine3,
        $addressCity,
        $addressPostalCode,
        $addressLat,
        $addressLng,
        $fields = array()
    ) {
        $employee = $this->client->post(
            'api/assets/replace',
            array(
                'asset_identifier' => $identifier,
                'asset_name' => $name,
                'asset_template_id' => $assetTemplateId,
                'asset_status_id' => $assetStatusId,
                'address_line_1' => $addressLine1,
                'address_line_2' => $addressLine2,
                'address_line_3' => $addressLine3,
                'address_city' => $addressCity,
                'address_postal_code' => $addressPostalCode,
                'address_lat' => $addressLat,
                'address_lng' => $addressLng,
                'updated_on' => time(),
                'created_on' => time(),
                'asset_fields' => json_encode($fields)
            )
        )->json();
        return $this->checkPropertyAndReturn($employee, 'asset');
    }
}

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
     * @param $apiUrl
     */
    public function __construct($employeeId, $privateKey, $apiUrl = null)
    {
        $this->setEmployeeId($employeeId);
        $this->setPrivateKey($privateKey);
        $this->client = new Client($this->getEmployeeId(), $this->getPrivateKey(), null, $apiUrl);
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
     * @param $templateId
     * @param array $params
     * @return array|bool|float|int|string
     * @throws Exceptions\GeopalException
     */
    public function createAndAssignJob($templateId, array $params = array())
    {
        $job = $this->client->post(
            'api/jobs/createandassign',
            array_merge(array('template_id' => $templateId), $params)
        )->json();
        return $this->checkPropertyAndReturn($job, 'job');
    }


    /**
     * @param $templateId
     * @param array $params
     * @return array|bool|float|int|string
     * @throws Exceptions\GeopalException
     */
    public function createJob($templateId, array $params = array())
    {
        $job = $this->client->post(
            'api/jobs/create',
            array_merge(array('template_id' => $templateId), $params)
        )->json();
        return $this->checkPropertyAndReturn($job, 'job');
    }


    /**
     * @param $jobId
     * @param \DateTime $startDateTime
     * @param $assignedToEmployeeId
     * @return array|bool|float|int|string
     * @throws Exceptions\GeopalException
     */
    public function assignJob($jobId, $startDateTime, $assignedToEmployeeId)
    {
        $job = $this->client->post(
            'api/jobs/assign',
            array(
                'job_id' => $jobId,
                'start_date_time' => $startDateTime->format('Y-m-d H:i:s'),
                'employee_id' => $assignedToEmployeeId
            )
        )->json();
        return $this->checkPropertyAndReturn($job, 'job');
    }

    /**
     * Reassigns a job to another employee
     *
     * @param $jobId
     * @param $employeeReassignedToId
     * @param \DateTime $startDateTime
     * @return mixed
     */
    public function reassignJob($jobId, $employeeReassignedToId, $startDateTime)
    {
        $job = $this->client->post(
            'api/jobs/reassign',
            array(
                'job_id' => $jobId,
                'employee_reassigned_to_id' => $employeeReassignedToId,
                'start_date_time' => $startDateTime->format('Y-m-d H:i:s')
            )
        )->json();
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
     * @param $templateId
     * @return mixed
     * @throws Exceptions\GeopalException
     */
    public function getJobTemplateById($templateId)
    {
        $jobTemplates = $this->client->get('api/jobtemplates/get', array('template_id' => $templateId))->json();
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
    ) {
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
     * gets all assets
     *
     * @param int $limit
     * @param int $page
     * @param int $updatedOn // This should be a valid unix timestamp or null
     * @return mixed
     */
    public function getAllAssets($limit = 10, $page = 0, $updatedOn = null)
    {
        $params = array('limit' => $limit, 'page' => $page);

        if (!is_null($updatedOn) && is_numeric($updatedOn)) {
            $params['updatedOn'] = intval($updatedOn);
        }

        $assets = $this->client->get('api/assets/getall', $params)->json();
        return $this->checkPropertyAndReturn($assets, 'assets');
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
     * @param array $params
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
        $params = array()
    ) {
        $employee = $this->client->post(
            'api/assets/replace',
            array_merge(
                array(
                    'asset_identifier' => $identifier,
                    'asset_name' => $name,
                    'asset_template_id' => $assetTemplateId,
                    'asset_company_status_id' => $assetStatusId,
                    'address_line_1' => $addressLine1,
                    'address_line_2' => $addressLine2,
                    'address_line_3' => $addressLine3,
                    'address_city' => $addressCity,
                    'address_postal_code' => $addressPostalCode,
                    'address_lat' => $addressLat,
                    'address_lng' => $addressLng,
                    'updated_on' => time(),
                    'created_on' => time(),
                    'address_updated_on' => time()
                ),
                $params
            )
        )->json();
        return $this->checkPropertyAndReturn($employee, 'asset');
    }

    /**
     * @param string $identifier
     * @param string $name
     * @param string $customerTypeName
     * @param string $industry
     * @param string $annualRevenue
     * @param string $email
     * @param string $emailAlternate
     * @param string $fax
     * @param string $phoneOffice
     * @param string $phoneAlternate
     * @param string $website
     * @param string $employees
     * @param bool $isDeleted
     * @param array $address
     * @param array $customerExtraFields
     * @param array $customerFields
     * @return mixed
     */
    public function replaceCustomer(
        $identifier,
        $name = '',
        $customerTypeName = '',
        $industry = '',
        $annualRevenue = '',
        $email = '',
        $emailAlternate = '',
        $fax = '',
        $phoneOffice = '',
        $phoneAlternate = '',
        $website = '',
        $employees = '',
        $isDeleted = false,
        $address = array(),
        $customerExtraFields = array(),
        $customerFields = array()
    ) {
        $customer = $this->client->post(
            'api/customers/replace',
            array(
                'identifier' => $identifier,
                'name' => $name,
                'customer_type_name' => $customerTypeName,
                'industry' => $industry,
                'annual_revenue' => $annualRevenue,
                'email' => $email,
                'email_alternate' => $emailAlternate,
                'fax' => $fax,
                'phone_office' => $phoneOffice,
                'phone_alternate' => $phoneAlternate,
                'website' => $website,
                'employees' => $employees,
                'is_deleted' => $isDeleted,
                'address' => $address,
                'customer_extra_fields' => $customerExtraFields,
                'customer_fields' => $customerFields
            )
        )->json();
        return $this->checkPropertyAndReturn($customer, 'customer');
    }


    /**
     * @param string $identifier
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $emailAlternate
     * @param string $faxNumber
     * @param string $phoneNumber
     * @param string $phoneNumberAlternate
     * @param string $mobileNumber
     * @param string $personTypeName
     * @param string $personJobTitleName
     * @param string $personDepartmentName
     * @param bool $isDeleted
     * @param array $params
     * @return mixed
     */
    public function replacePerson(
        $identifier,
        $firstName,
        $lastName,
        $email = '',
        $emailAlternate = '',
        $faxNumber = '',
        $phoneNumber = '',
        $phoneNumberAlternate = '',
        $mobileNumber = '',
        $personTypeName = '',
        $personJobTitleName = '',
        $personDepartmentName = '',
        $isDeleted = false,
        $params = array()
    ) {
        $contact = $this->client->post(
            'api/people/replace',
            array_merge(
                array(
                    'identifier' => $identifier,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'email_alternate' => $emailAlternate,
                    'fax_number' => $faxNumber,
                    'phone_number' => $phoneNumber,
                    'phone_number_alternate' => $phoneNumberAlternate,
                    'mobile_number' => $mobileNumber,
                    'person_type_name' => $personTypeName,
                    'person_job_title_name' => $personJobTitleName,
                    'person_department_name' => $personDepartmentName,
                    'is_deleted' => $isDeleted
                ),
                $params
            )
        )->json();
        return $this->checkPropertyAndReturn($contact, 'person');
    }


    /**
     * Valid status id's are:
     *
     * 6 for “Accepted”
     * 2 for “Rejected”
     * 3 for “Completed”
     * 5 for “In Progress”
     * 7 for “Incomplete”
     *
     * @param int $jobId
     * @param int $newStatusId
     * @param string $message
     * @return mixed
     */
    public function updateJobStatus($jobId, $newStatusId, $message = '')
    {
        $job = $this->client->post(
            'api/jobs/status',
            array(
                'job_id' => $jobId,
                'job_status_id' => $newStatusId,
                'message' => $message,
                'updated_on' => time()
            )
        )->json();
        return $this->checkPropertyAndReturn($job, 'job');
    }

    /**
     * Finds an employee based on her username and password
     *
     * @param string $username
     * @param string $password
     * @return mixed
     */
    public function getEmployeeByCredentials($username, $password)
    {
        $employee = $this->client->post(
            'api/employees/getbycredentials',
            array(
                'username' => $username,
                'password' => $password
            )
        )->json();
        return $this->checkPropertyAndReturn($employee, 'employee_data');
    }

    /**
     * Updates details of an employee
     *
     * @param $id
     * @param $username
     * @param $password
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param array $params
     * @return mixed
     */
    public function updateEmployeeById($id, $username, $password, $firstName, $lastName, $email, $params = array())
    {
        $employee = $this->client->post(
            'api/employees/update',
            array_merge(
                array(
                    'id' => $id,
                    'username' => $username,
                    'password' => $password,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ),
                $params
            )
        )->json();
        return $this->checkPropertyAndReturn($employee, 'employee_data');
    }

    /**
     * Updates details of an employee based on her identifier
     *
     * @param $identifier
     * @param $username
     * @param $password
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param array $params
     * @return mixed
     */
    public function updateEmployeeByIdentifier(
        $identifier,
        $username,
        $password,
        $firstName,
        $lastName,
        $email,
        $params = array()
    ){
        $employee = $this->client->post(
            'api/employees/updatebyidentifier',
            array_merge(
                array(
                    'identifier' => $identifier,
                    'username' => $username,
                    'password' => $password,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ),
                $params
            )
        )->json();
        return $this->checkPropertyAndReturn($employee, 'employee_data');
    }


    /**
     * Gets a list of company files
     *
     * @return mixed
     * @throws GeopalException
     */
    public function getCompanyFiles()
    {
        $companyFileUploadResponse = $this->client->get(
            'api/companyfiles/all',
            array()
        )->json();
        return $this->checkPropertyAndReturn($companyFileUploadResponse, 'company_file_uploads');
    }

    /**
     * Gets a company file by id
     *
     * @param $companyFileId
     * @return mixed
     * @throws GeopalException
     */
    public function getCompanyFile($companyFileId)
    {
        $companyFileUploadResponse = $this->client->get(
            'api/companyfile/get',
            array(
                'id' => $companyFileId
            )
        )->json();

        return $this->checkPropertyAndReturn($companyFileUploadResponse, 'company_file_uploads');
    }

    /**
     * Add Company file
     *
     * @param string $fileName
     * @param string $fileCategory
     * @param string $file path to file
     * @return mixed
     * @throws GeopalException
     */
    public function addCompanyFile($fileName, $fileCategory, $file)
    {
        $companyFileUploadResponse = $this->client->post(
            'api/companyfile/create',
            array(
                'file_name' => $fileName,
                'file_category' => $fileCategory,
            ),
            $file
        )->json();
        return $this->checkPropertyAndReturn($companyFileUploadResponse, 'company_file_uploads');
    }


    /**
     * Update a company file by ID
     *
     * @param $fileId
     * @param null|string $newFileName name string to change name, null to use original name
     * @param null|string $newFileCategory category string to change category, null to use original category
     * @param null|true $newFile true to update file with uploaded file, null to use original file
     * @return mixed
     * @throws GeopalException
     */
    public function updateCompanyFile($fileId, $newFileName = null, $newFileCategory = null, $newFile = null)
    {
        $companyFileUploadResponse = $this->client->post(
            'api/companyfile/update',
            array(
                'id' => $fileId,
                'file_name' => $newFileName,
                'file_category' => $newFileCategory
            ),
            $newFile
        )->json();
        return $this->checkPropertyAndReturn($companyFileUploadResponse, 'company_file_uploads');
    }

    /**
     * Delete a company file by ID
     *
     * @param $fileId
     * @return mixed
     * @throws GeopalException
     */
    public function deleteCompanyFile($fileId)
    {
        $companyFileUploadResponse = $this->client->post(
            'api/companyfile/delete',
            array('id' => $fileId)
        )->json();
        return $this->checkPropertyAndReturn($companyFileUploadResponse, 'company_file_uploads');
    }


    /**
     * Download a company file by ID
     *
     * @param $fileId
     * @return \Guzzle\Http\Message\Response
     */
    public function downloadCompanyFile($fileId)
    {
        $companyFileUploadResponse = $this->client->get(
            'api/companyfile/download',
            array('id' => $fileId)
        );

        return $companyFileUploadResponse;
    }
}

<?php
namespace Geopal\Tests;

use Geopal\Geopal;
use Geopal\Http\Client;

class GeopalMock extends Geopal
{

    /**
     * @param $employeeId
     * @param $privateKey
     */
    public function __construct($employeeId, $privateKey)
    {
        $this->setEmployeeId($employeeId);
        $this->setPrivateKey($privateKey);
        if ($this->getEmployeeId() && $this->getPrivateKey()) {
            return $this;
        } else {
            return null;
        }
    }



    /**
     * @param $templateId
     * @return array|bool|float|int|string
     */
    public function createAndAssignJob($templateId)
    {
        $job = array(
            'status' => true,
            'tag' => '',
            'employee' => array(),
            'company' => array(),
            'job' => array(
                'id' => 1,
                'job_template' => array(
                    'id' => $templateId
                )
            )
        );
        return $this->checkPropertyAndReturn($job, 'job');
    }

    /**
     * @param $jobId
     * @param $employeeReassignedToId
     * @param $startDateTime
     * @return array|bool|float|int|string
     */
    public function reassignJob($jobId, $employeeReassignedToId, $startDateTime)
    {
        $job = array(
            'status' => true,
            'tag' => '',
            'employee' => array(),
            'company' => array(),
            'job' => array(
                'id' => $jobId,
                'assigned_to' => array(
                    'id' => $employeeReassignedToId
                ),
                'start_date' => $startDateTime
            )
        );
        return $this->checkPropertyAndReturn($job, 'job');
    }

    /**
     * @param $jobId
     * @return mixed
     */
    public function getJobById($jobId)
    {
        $jobs =  array(
            'status' => true,
            'job' => array(
                'id' => $jobId,
                'url' =>'',
                'identifier' => $jobId,
                'schedule_log_id' => null,
                'schedule_job_status_id' => null,
                'estimated_duration' => 0
            )
        );
        return $this->checkPropertyAndReturn($jobs, 'job');
    }

    /**
     * @param $dateTimeFrom
     * @param $dateTimeTo
     * @return mixed
     */
    public function getJobsBetweenDateRange($dateTimeFrom, $dateTimeTo)
    {
        if ($dateTimeFrom && $dateTimeTo) {
            $jobs =  array(
                'status' => true,
                'jobs' => array(
                    array('id'=>1),
                    array('id'=>2)
                )
            );
            return $this->checkPropertyAndReturn($jobs, 'jobs');
        } else {
            return null;
        }

    }

    /**
     * @return mixed
     */
    public function getEmployeesList()
    {
        $employees = array(
            'status' => true,
            'employee' => array(
                'id' => 2,
                'title' => '',
                'first_name' => '',
                'last_name' =>''
            )
        );
        return $this->checkPropertyAndReturn($employees, 'employee');
    }

    /**
     * gets job templates
     * @return mixed
     */
    public function getJobTemplates()
    {
        $jobTemplates = array(
            'status' => true,
            'job_templates' => array(
                array(
                    'job_template_id' => 29,
                    'name' => '',
                    'is_deleted' => false,
                    'updated_on' => date('Y-m-d H:i:s', time())
                ),
                array(
                    'job_template_id' => 30,
                    'name' => '',
                    'is_deleted' => false,
                    'updated_on' => date('Y-m-d H:i:s', time())
                )
            )
        );

        return $this->checkPropertyAndReturn($jobTemplates, 'job_templates');
    }

    /**
     * gets job template by id
     * @param $templateId
     * @return mixed
     */
    public function getJobTemplateById($templateId)
    {
        $jobTemplates = array(
            'status' => true,
            'job_template' => array(
                'id' => $templateId,
                'name' => null,
                'is_deleted' => false,
                'updated_on' => date('Y-m-d H:i:s', time())
            )
        );
        return $this->checkPropertyAndReturn($jobTemplates, 'job_template');
    }

    /**
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
        $employee = array(
            'status' => true,
            'employee_data' => array_merge(
                array(
                    'id' => $id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ),
                $params
            )
        );
        return $this->checkPropertyAndReturn($employee, 'employee_data');
    }

    /**
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
        $employee = array(
            'status' => true,
            'employee_data' => array_merge(
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
        );
        return $this->checkPropertyAndReturn($employee, 'employee_data');
    }
}

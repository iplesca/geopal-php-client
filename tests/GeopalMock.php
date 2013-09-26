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
     * @param $template_id
     * @return array|bool|float|int|string
     */
    public function createAndAssignJob($template_id)
    {
        $job = array(
            'status' => true,
            'tag' => '',
            'employee' => array(),
            'company' => array(),
            'job' => array(
                'id' => 1,
                'job_template' => array(
                    'id' => $template_id
                )
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
     * @param $template_id
     * @return mixed
     */
    public function getJobTemplateById($template_id)
    {
        $jobTemplates = array(
            'status' => true,
            'job_template' => array(
                'id' => $template_id,
                'name' => null,
                'is_deleted' => false,
                'updated_on' => date('Y-m-d H:i:s', time())
            )
        );
        return $this->checkPropertyAndReturn($jobTemplates, 'job_template');
    }
}

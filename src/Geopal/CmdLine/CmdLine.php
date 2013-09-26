<?php
require_once './../../../vendor/autoload.php';

function getTextFromCmdLine()
{
    $handle = fopen("php://stdin", "r");
    return trim(fgets($handle));
}

echo "Geopal Command Line\n";
echo "Please enter employee id\n";
$employeeId = getTextFromCmdLine();

echo "Please enter private key id\n";
$privateKey = getTextFromCmdLine();

echo ("---------------------------------------------------------\n");
//echo ("Create A Job: 1\n");
echo ("Read A Job: 2\n");
echo ("List All Job Templates: 3\n");
echo ("Read Job Template: 4\n");
//echo ("Read Jobs Between Date Rage: 5\n");
echo ("List Employees: 6\n");
echo ("Please Choose");

$geopal = new \Geopal\Geopal($employeeId, $privateKey);

$continue = true;
while ($continue) {
    $choice = getTextFromCmdLine();
    switch ($choice) {
        case 2:
            echo ("Please Enter Job Id");
            $jobId = getTextFromCmdLine();
            print_r($geopal->getJobById($jobId));
            break;
        case 3:
            print_r($geopal->getJobTemplates());
            break;
        case 4:
            echo ("Please Enter Template Id");
            $templateId = getTextFromCmdLine();
            print_r($geopal->getJobTemplateById($templateId));
            break;
        case 6:
            print_r($geopal->getEmployeesList());
            break;
        default:
            $continue = false;
    }
}

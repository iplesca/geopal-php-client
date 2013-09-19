<?php

namespace Geoapl\Client;

class Client
{
    /**
     * @var int
     */
    private $employeeId;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @param int $employeeId
     * @param string $privateKey
     */
    public function __construct($employeeId, $privateKey)
    {
        $this->employeeId = $employeeId;
        $this->privateKey = $privateKey;
    }
}
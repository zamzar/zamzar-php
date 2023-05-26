<?php

namespace Zamzar;

/**
 * Format Object
 */
class Format extends ApiResource
{
    /** Properties */
    private $name;
    private $credit_cost;
    private $targets;

    /**
     * Initialise a new instance of the Format object
     * Recursively used to create target formats for a given source format
     */
    public function __construct($config, $data)
    {
        $this->apiInit($config, $data->name);
        $this->setValues($data);
    }

    /**
     * Initialise or Update properties
     */
    private function setValues($data)
    {
        // Should always be supplied
        $this->name = $data->name;

        // Optionally supplied
        if (property_exists($data, "credit_cost")) {
            $this->credit_cost = $data->credit_cost;
        }

        if (property_exists($data, "targets")) {
            $this->targets = array();
            foreach ($data->targets as $target) {
                $this->targets[] = new Format($this->getConfig(), $target);
            }
        }
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of credit_cost
     */
    public function getCreditCost()
    {
        return $this->credit_cost;
    }

    /**
     * Get the value of targets
     */
    public function getTargets()
    {
        return $this->targets;
    }

    /**
     * Return the value of targets as a csv string
     */
    public function getTargetsToCsv()
    {
        $csv = '';
        foreach ($this->getTargets() as $targetformat) {
            $csv = $csv . $targetformat->name . ',';
        }
        return $csv;
    }
}

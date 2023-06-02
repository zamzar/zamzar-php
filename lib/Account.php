<?php

namespace Zamzar;

/**
 * Account Class
 *
 * Stores basic Account and Plan information
 */
class Account extends ApiResource
{
    /** Properties */
    protected $test_credits_remaining;
    protected $production_credits_remaining;

    /** Nested Objects */
    private $plan;

    /**
     * Retrieve the account & plan information
     */
    public function get()
    {
        // make the api request
        $apiResponse = $this->apiRequest($this->getEndpoint());
        $data = $apiResponse->getBody();

        // set the properties
        $this->test_credits_remaining = $data->test_credits_remaining;
        $this->production_credits_remaining = $data->credits_remaining;
        $this->plan = new \Zamzar\Plan($data->plan);

        // return this object
        return $this;
    }

    /**
     * Refresh account information
     */
    public function refresh()
    {
        $this->get();
    }

    /**
     * Return the remaining test credits. This value may be out of date;
     * consider calling `refresh()` to ensure it's up-to-date.
     */
    public function getTestCreditsRemaining()
    {
        if (is_null($this->test_credits_remaining)) {
            $this->get();
        }

        return $this->test_credits_remaining;
    }

    /**
     * Return the remaining production credits. This value may be out of date;
     * consider calling `refresh()` to ensure it's up-to-date.
     */
    public function getProductionCreditsRemaining()
    {
        if (is_null($this->production_credits_remaining)) {
            $this->get();
        }

        return $this->production_credits_remaining;
    }

    /**
     * Return information about your plan. This data may be out of date;
     * consider calling `refresh()` to ensure it's up-to-date.
     */
    public function getPlan()
    {
        if (is_null($this->plan)) {
            $this->get();
        }

        return $this->plan;
    }
}

<?php

namespace Zamzar;

/**
 * Account Class
 * 
 * Stores basic Account and Plan information
 */
class Account extends ApiResource {

	/** Properties */
	protected $test_credits_remaining;
	protected $production_credits_remaining;
	
	/** Nested Objects */
	private $plan;
	
	/**
	 * Inialises a new instance of the Account object
	 */
	public function __construct($config) {
		$this->apiInit($config);
	}

	/**
	 * Retrieve the account & plan information
	 */
	public function get() {

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
	public function refresh() {
		$this->get();
	}

	/** 
	 * Return the Test Credits Remaining
	 */
	public function getTestCreditsRemaining() {
		return $this->test_credits_remaining;
	}

	/**
	 * Return the Production Credits Remaining
	 */
	public function getProductionCreditsRemaining() {
		return $this->production_credits_remaining;
	}

	/**
	 * Return the Plan
	 */
	public function getPlan() {
		return $this->plan;
	}

}

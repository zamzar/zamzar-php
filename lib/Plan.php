<?php

namespace Zamzar;

/**
 * Plan Object
 */
class Plan {

	/* Properties */
	protected $name;
	protected $price_per_month;
	protected $conversions_per_month;
	protected $maximum_file_size;

	public function __construct($plan) {	
		$this->setValues($plan);
	}

	private function setValues($plan) {
		$this->name = $plan->name;
		$this->price_per_month = $plan->price_per_month;
		$this->conversions_per_month = $plan->conversions_per_month;
		$this->maximum_file_size = $plan->maximum_file_size;
	}

	public function getPlanName() {
		return $this->name;
	}

	public function getPricePerMonth() {
		return $this->price_per_month;
	}

	public function getConversionsPerMonth() {
		return $this->conversions_per_month;
	}

	public function getMaximumFileSize() {
		return $this->maximum_file_size;
	}

}

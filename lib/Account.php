<?php

namespace Zamzar;

/**
 * @property \Zamzar\Plan $plan
 * @property int $production_credits_remaining
 * @property int $test_credits_remaining
 */
class Account extends ApiResource
{
    protected $propertyMap = [
        'plan' => Plan::class,
    ];

    /**
     * Overload the classUrl method, as the account endpoint
     * is not pluralised.
     */
    public static function classUrl()
    {
        return "/v1/account";
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getProductionCreditsRemaining()
    {
        return $this->production_credits_remaining;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getTestCreditsRemaining()
    {
        return $this->test_credits_remaining;
    }
}

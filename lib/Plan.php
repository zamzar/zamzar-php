<?php

namespace Zamzar;

/**
 * @property string $name
 * @property int $price_per_month
 * @property int $conversions_per_month
 * @property int $maximum_file_size
 */
class Plan extends ZamzarObject
{
    /**
     * @deprecated Access property directly instead
     */
    public function getPlanName()
    {
        return $this->name;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getPricePerMonth()
    {
        return $this->price_per_month;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getConversionsPerMonth()
    {
        return $this->conversions_per_month;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getMaximumFileSize()
    {
        return $this->maximum_file_size;
    }
}

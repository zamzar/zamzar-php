<?php

namespace Zamzar;

/**
 * @property string $name
 * @property null|array $targets
 */
class Format extends ApiResource
{
    /**
     * Get the value of name
     * @deprecated
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the value of targets
     * @deprecated
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

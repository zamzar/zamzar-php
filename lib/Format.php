<?php

namespace Zamzar;

/**
 * @property string $name
 * @property null|array $targets
 */
class Format extends ApiResource
{
    /**
     * @deprecated Access property directly instead
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @deprecated Access property directly instead
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
        foreach ($this->targets as $targetformat) {
            $csv = $csv . $targetformat->name . ',';
        }
        return $csv;
    }
}

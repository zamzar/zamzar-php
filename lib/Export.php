<?php

namespace Zamzar;

/**
 * @property int $id
 * @property string $url
 * @property string $status
 * @property null|\Zamzar\Failure $failure
 */
class Export extends ZamzarObject
{
    public const STATUS_INITIALISING = 'initialising';
    public const STATUS_UPLOADING = 'uploading';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_FAILED = 'failed';

    protected array $propertyMap = [
        'failure' => Failure::class,
    ];

    /**
     * Get the value of id
     * @deprecated
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of url
     * @deprecated
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the value of status
     * @deprecated
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Does this export have a failure
     */
    public function hasFailure()
    {
        return !is_null($this->failure);
    }

    /**
     * Is the Status = Initialising
     */
    public function isStatusInitialising()
    {
        return $this->status == self::STATUS_INITIALISING;
    }

    /**
     * Is the Status = Uploading
     */
    public function isStatusUploading()
    {
        return $this->status == self::STATUS_UPLOADING;
    }

    /**
     * Is the Status = Successful
     */
    public function isStatusSuccessful()
    {
        return $this->status == self::STATUS_SUCCESSFUL;
    }

    /**
     * Is the Status = Failed
     */
    public function isStatusFailed()
    {
        return $this->status == self::STATUS_FAILED;
    }

    /**
     * Has the export completed (successfully or not)
     */
    public function hasCompleted()
    {
        return $this->isStatusSuccessful() || $this->isStatusFailed();
    }
}

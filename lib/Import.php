<?php

namespace Zamzar;

/**
 * @property int $id
 * @property string $key
 * @property string $url
 * @property string $status
 * @property null|\Zamzar\Failure $failure
 * @property string $created_at
 * @property string $finished_at
 * @property null|\Zamzar\File $file
 */
class Import extends ApiResource
{
    public const STATUS_INITIALISING = 'initialising';
    public const STATUS_DOWNLOADING = 'downloading';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_FAILED = 'failed';

    protected array $propertyMap = [
        'failure' => Failure::class,
        'file' => File::class,
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
     * Get the value of key
     * @deprecated
     */
    public function getKey()
    {
        return $this->key;
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
     * Get the value of failure
     * @deprecated
     */
    public function getFailure()
    {
        return $this->failure;
    }

    /**
     * Get the value of file
     * @deprecated
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get the value of created_at
     * @deprecated
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get the value of finished_at
     * @deprecated
     */
    public function getFinishedAt()
    {
        return $this->finished_at;
    }

    /**
     * Does this import have a file
     * @deprecated
     */
    public function hasFile()
    {
        return !is_null($this->file);
    }

    /**
     * Does this import have a failure
     * @deprecated
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
     * Is the Status = Downloading
     */
    public function isStatusDownloading()
    {
        return $this->status == self::STATUS_DOWNLOADING;
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
     * Has the import completed (successfully or not)
     */
    public function hasCompleted()
    {
        return $this->isStatusSuccessful() || $this->isStatusFailed();
    }
}

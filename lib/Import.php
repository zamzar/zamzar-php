<?php

namespace Zamzar;

use Zamzar\ApiOperations\WaitForCompletion;

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
    use WaitForCompletion;

    public const STATUS_INITIALISING = 'initialising';
    public const STATUS_DOWNLOADING = 'downloading';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_FAILED = 'failed';

    protected $propertyMap = [
        'failure' => Failure::class,
        'file' => File::class,
    ];

    /**
     * @deprecated Access property directly instead
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getFailure()
    {
        return $this->failure;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getFinishedAt()
    {
        return $this->finished_at;
    }

    public function hasFile()
    {
        return !is_null($this->file);
    }

    public function hasFailure()
    {
        return !is_null($this->failure);
    }

    public function isStatusInitialising()
    {
        return $this->status == self::STATUS_INITIALISING;
    }

    public function isStatusDownloading()
    {
        return $this->status == self::STATUS_DOWNLOADING;
    }

    public function isStatusSuccessful()
    {
        return $this->status == self::STATUS_SUCCESSFUL;
    }

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

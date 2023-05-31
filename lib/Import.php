<?php

namespace Zamzar;

/**
 * Import Object
 */
class Import extends ApiResource
{
    /** Valid API Operations for this Class */
    use \Zamzar\ApiOperations\Refresh;

    /** Constants */
    public const STATUS_INITIALISING = 'initialising';
    public const STATUS_DOWNLOADING = 'downloading';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_FAILED = 'failed';

    /** Properties */
    private $id;
    private $key;
    private $url;
    private $status;
    private $failure;
    private $file;
    private $created_at;
    private $finished_at;

    /**
     * Initialise a new instance of the Import object
     */
    public function __construct($config, $data)
    {
        $this->apiInit($config, $data->id);
        $this->setValues($data);
    }

    /**
     * Initialise or Update properties
     */
    private function setValues($data)
    {
        // Should always be supplied
        $this->id = $data->id;
        $this->url = $data->url;
        $this->status = $data->status;

        // Optionally supplied
        if (property_exists($data, "key")) {
            $this->key = $data->key;
        }

        if (property_exists($data, "failure")) {
            $this->failure = new \Zamzar\Failure($data->failure);
        }

        if (property_exists($data, "file")) {
            $this->file = new \Zamzar\File($this->getConfig(), $data->file);
        }

        if (property_exists($data, "created_at")) {
            $this->created_at = $data->created_at;
        }

        if (property_exists($data, "finished_at")) {
            $this->finished_at = $data->finished_at;
        }
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get the value of url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get the value of failure
     */
    public function getFailure()
    {
        return $this->failure;
    }

    /**
     * Get the value of file
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get the value of finished_at
     */
    public function getFinishedAt()
    {
        return $this->finished_at;
    }

    /**
     * Does this import have a file
     */
    public function hasFile()
    {
        return !is_null($this->file);
    }

    /**
     * Does this import have a failure
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

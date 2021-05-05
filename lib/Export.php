<?php

Namespace Zamzar;

/**
 * Export Class
 * 
 * Exports are requested when submitting jobs, i.e. submit the job and export the converted file(s) to a remote server
 * Export Objects are therefore only created when retrieving Jobs data, they are not part of an exports collection
 * Exports Objects do not interact with the api, hence no inheritance of the ApiResource class
 */
class Export
{

    /** Constants */
    public const STATUS_INITIALISING = 'initialising';
    public const STATUS_UPLOADING = 'uploading';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_FAILED = 'failed';
    
    /** Properties */
    private $id;
    private $url;
    private $status;
    private $failure;

	/**
	 * Initialise a new instance of the Export object
	 */
	public function __construct($data) {
        $this->setValues($data);
    }

    /**
	 * Initialise or Update properties
	 */
	private function setValues($data) {
        
        // Should always be supplied
        $this->id = $data->id;
        $this->url = $data->url;
        $this->status = $data->status;

        // Optionally supplied
        if(property_exists($data, "failure")) {
            $this->failure = new \Zamzar\Failure($data->failure);
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
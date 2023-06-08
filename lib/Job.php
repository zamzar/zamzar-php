<?php

namespace Zamzar;

/**
 * Job Object
 */
class Job extends ApiResource
{
    /** Valid API Operations for this Class */
    use \Zamzar\ApiOperations\Cancel;
    use \Zamzar\ApiOperations\Refresh;

    /** Constants */
    public const STATUS_INITIALISING = 'initialising';
    public const STATUS_CONVERTING = 'converting';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    /** Properties */
    private $id;
    private $key;
    private $status;
    private $failure;
    private $sandbox;
    private $created_at;
    private $finished_at;
    private $import;
    private $source_file;
    private $target_files;
    private $target_format;
    private $credit_cost;
    private $export_url;
    private $exports;

    /**
     * Initialise a new instance of the Job object
     */
    public function __construct($config, $data)
    {
        parent::__construct($config, $data->id);
        $this->setValues($data);
    }

    /**
     * Initialise or Update properties
     */
    private function setValues($data)
    {
        // Should always be supplied
        $this->id = $data->id;
        $this->key = $data->key;
        $this->status = $data->status;
        $this->sandbox = $data->sandbox;
        $this->finished_at = $data->finished_at;
        $this->created_at = $data->created_at;
        $this->target_files = array();
        $this->target_format = $data->target_format;
        $this->credit_cost = $data->credit_cost;

        // Target Files will be empty when a job is submitted
        foreach ($data->target_files as $target_file) {
            $this->target_files[] = new \Zamzar\File($this->getConfig(), $target_file);
        }

        // Optionally supplied
        if (property_exists($data, "source_file")) {
            $this->source_file = new \Zamzar\File($this->getConfig(), $data->source_file);
        }

        if (property_exists($data, "failure")) {
            $this->failure = new \Zamzar\Failure($data->failure);
        }

        if (property_exists($data, "import")) {
            $this->import = new \Zamzar\Import($this->getConfig(), $data->import);
        }

        if (property_exists($data, "export_url")) {
            $this->export_url = $data->export_url;
        } else {
            $this->export_url = '';
        }

        if (property_exists($data, "exports")) {
            foreach ($data->exports as $export) {
                $this->exports[] = new \Zamzar\Export($export);
            }
        }
    }

    /**
     * Delete source file
     */
    public function deleteSourceFile()
    {
        $this->getSourceFile()->delete();
        return $this;
    }

    /**
     * Delete target files
     */
    public function deleteTargetFiles()
    {
        foreach ($this->getTargetFiles() as $target_file) {
            $target_file->delete();
        }
        return $this;
    }

    /**
     * Download the target files
     */
    public function downloadTargetFiles($path)
    {
        foreach ($this->getTargetFiles() as $target_file) {
            $target_file->download($path);
        }
        return $this;
    }

    /**
     * Download and Delete all files
     */
    public function downloadAndDeleteAllFiles($path)
    {
        $this->downloadTargetFiles($path);
        $this->deleteSourceFile();
        $this->deleteTargetFiles();
        return $this;
    }

    /**
     * Delete all files
     */
    public function deleteAllFiles()
    {
        $this->deleteSourceFile();
        $this->deleteTargetFiles();
        return $this;
    }

    /**
     * Wait for the job to complete
     */
    public function waitForCompletion($params = [])
    {
        $totalSleep = 0;
        $sleepInterval = 1;
        $timeout = 60;

        if (is_array($params)) {
            if (array_key_exists("timeout", $params)) {
                $timeout = $params['timeout'];
            }
        }

        do {
            // goto sleep
            sleep($sleepInterval);

            // how long have we been sleeping for
            $totalSleep += $sleepInterval;

            // gradually decrease the number of api calls based on the time spent waiting, upto a max of 30 second intervals
            if ($totalSleep >= 10) {
                $sleepInterval = 5;
            } elseif ($totalSleep >= 20) {
                $sleepInterval = 10;
            } elseif ($totalSleep >= 40) {
                $sleepInterval = 20;
            } elseif ($totalSleep >= 60) {
                $sleepInterval = 30;
            }

            // throw an exception if we exceed the timeout
            if ($totalSleep > $timeout) {
                throw new \Zamzar\Exception\TimeOutException('Timed out waiting for Job Id ' . $this->getId() . ' to complete. Increase the timeout period.');
            }

            // refresh this job's values
            $this->refresh();
        } while (
            // check the latest status
            $this->status !== self::STATUS_SUCCESSFUL && $this->status !== self::STATUS_FAILED && $this->status !== self::STATUS_CANCELLED
        );

        // return this job
        return $this;
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
     * Get the value of sandbox
     */
    public function getSandbox()
    {
        return $this->sandbox;
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
     * Get the value of import
     */
    public function getImport()
    {
        return $this->import;
    }

    /**
     * Get the value of source_file
     */
    public function getSourceFile()
    {
        return $this->source_file;
    }

    /**
     * Get the value of target_files
     */
    public function getTargetFiles()
    {
        return $this->target_files;
    }

    /**
     * Get the value of target_format
     */
    public function getTargetFormat()
    {
        return $this->target_format;
    }

    /**
     * Get the value of credit_cost
     */
    public function getCreditCost()
    {
        return $this->credit_cost;
    }

    /**
     * Get the value of export_url
     */
    public function getExportUrl()
    {
        return $this->export_url;
    }

    /**
     * Get the value of exports
     */
    public function getExports()
    {
        return $this->exports;
    }

    /**
     * Does this job have a source file
     */
    public function hasSourceFile()
    {
        return !is_null($this->source_file);
    }

    /**
     * Does this job have target files
     */
    public function hasTargetFiles()
    {
        return !is_null($this->target_files);
    }

    /**
     * Does this job have a failure
     */
    public function hasFailure()
    {
        return !is_null($this->failure);
    }

    /**
     * Does this job have an import
     */
    public function hasImport()
    {
        return !is_null($this->import);
    }

    /**
     * Does this job have exports
     */
    public function hasExports()
    {
        return !is_null($this->exports);
    }

    /**
     * Is the Status = Initialising
     */
    public function isStatusInitialising()
    {
        return $this->status == self::STATUS_INITIALISING;
    }

    /**
     * Is the Status = Converting
     */
    public function isStatusConverting()
    {
        return $this->status == self::STATUS_CONVERTING;
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
     * Is the Status = Cancelled
     */
    public function isStatusCancelled()
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    /**
     * Has the job completed (successfully or not)
     */
    public function hasCompleted()
    {
        return $this->isStatusSuccessful() || $this->isStatusFailed();
    }
}

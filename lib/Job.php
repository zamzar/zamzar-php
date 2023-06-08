<?php

namespace Zamzar;

/**
 * @property int $id
 * @property string $key
 * @property string $status
 * @property null|\Zamzar\Failure $failure
 * @property bool $sandbox
 * @property string $created_at
 * @property string $finished_at
 * @property null|\Zamzar\Import $import
 * @property null|\Zamzar\File $source_file
 * @property \Zamzar\File[] $target_files
 * @property string $target_format
 * @property int $credit_cost
 * @property string $export_url
 * @property null|\Zamzar\Export $exports
 */
class Job extends ApiResource
{
    // use \Zamzar\ApiOperations\Cancel;

    public const STATUS_INITIALISING = 'initialising';
    public const STATUS_CONVERTING = 'converting';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    protected array $propertyMap = [
        'failure' => Failure::class,
        'import' => Import::class,
        'source_file' => File::class,
        'target_files' => [File::class],
    ];

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
    public function waitForCompletion($timeout = 60)
    {
        $totalSleep = 0;
        $sleepInterval = 1;

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

    public function cancel()
    {
        $this->request('DELETE', $this->instanceUrl());
        return $this->refresh();
    }

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
     * Get the value of sandbox
     * @deprecated
     */
    public function getSandbox()
    {
        return $this->sandbox;
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
     * Get the value of import
     * @deprecated
     */
    public function getImport()
    {
        return $this->import;
    }

    /**
     * Get the value of source_file
     * @deprecated
     */
    public function getSourceFile()
    {
        return $this->source_file;
    }

    /**
     * Get the value of target_files
     * @deprecated
     */
    public function getTargetFiles()
    {
        return $this->target_files;
    }

    /**
     * Get the value of target_format
     * @deprecated
     */
    public function getTargetFormat()
    {
        return $this->target_format;
    }

    /**
     * Get the value of credit_cost
     * @deprecated
     */
    public function getCreditCost()
    {
        return $this->credit_cost;
    }

    /**
     * Get the value of export_url
     * @deprecated
     */
    public function getExportUrl()
    {
        return $this->export_url;
    }

    /**
     * Get the value of exports
     * @deprecated
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

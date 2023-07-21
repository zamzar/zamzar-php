<?php

namespace Zamzar;

use Zamzar\ApiOperations\WaitForCompletion;

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
    use WaitForCompletion;

    public const STATUS_INITIALISING = 'initialising';
    public const STATUS_CONVERTING = 'converting';
    public const STATUS_SUCCESSFUL = 'successful';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $propertyMap = [
        'export' => Export::class,
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
        $this->source_file->delete();
        return $this;
    }

    /**
     * Delete target files
     */
    public function deleteTargetFiles()
    {
        foreach ($this->target_files as $target_file) {
            $target_file->delete();
        }
        return $this;
    }

    /**
     * Download the target files
     */
    public function downloadTargetFiles($path)
    {
        foreach ($this->target_files as $target_file) {
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

    public function cancel()
    {
        $this->request('DELETE', $this->instanceUrl());
        return $this->refresh();
    }

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
    public function getSandbox()
    {
        return $this->sandbox;
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

    /**
     * @deprecated Access property directly instead
     */
    public function getImport()
    {
        return $this->import;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getSourceFile()
    {
        return $this->source_file;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getTargetFiles()
    {
        return $this->target_files;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getTargetFormat()
    {
        return $this->target_format;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getCreditCost()
    {
        return $this->credit_cost;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getExportUrl()
    {
        return $this->export_url;
    }

    /**
     * @deprecated Access property directly instead
     */
    public function getExports()
    {
        return $this->exports;
    }

    public function hasSourceFile()
    {
        return !is_null($this->source_file);
    }

    public function hasTargetFiles()
    {
        return !is_null($this->target_files);
    }

    public function hasFailure()
    {
        return !is_null($this->failure);
    }

    public function hasImport()
    {
        return !is_null($this->import);
    }

    public function hasExports()
    {
        return !is_null($this->exports);
    }

    public function isStatusInitialising()
    {
        return $this->status == self::STATUS_INITIALISING;
    }

    public function isStatusConverting()
    {
        return $this->status == self::STATUS_CONVERTING;
    }

    public function isStatusSuccessful()
    {
        return $this->status == self::STATUS_SUCCESSFUL;
    }

    public function isStatusFailed()
    {
        return $this->status == self::STATUS_FAILED;
    }

    public function isStatusCancelled()
    {
        return $this->status == self::STATUS_CANCELLED;
    }

    public function hasCompleted()
    {
        return $this->isStatusSuccessful() || $this->isStatusFailed() || $this->isStatusCancelled();
    }
}

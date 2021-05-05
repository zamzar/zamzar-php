# Job Object

A job object is created by submitting a job, and retrieved using data retrieval methods using the [Jobs object](jobs.md). 

## The Properties of a Job
When a job is submitted, various flags indicate where the source file originates from, and what happens with the files once the job has been completed. Then, during processing, the job will progress through various status values to reach a point of being successful, failed or cancelled. 

As such a job object cannot be guaranteed to have 1) all of its properties complete depending on the time of the inspection 2) all of its properties present depending on the flags being used to process the job.

For example, if a job is submitting using a file on a remote server, this will result in an import record being created and an import object being attached to the job. If the job instead used a local file, the import object would not be a member of the job.

It is important to understand the above so that when a job is being interrogated, the right properties are interrogated depending on the status of the job, the origination of the files being used, and the target destination of the converted files.


## Standard Properties

Property | Method | Type | Description
---------|--------|------|-------------
id | getId() | integer | The id associated with the job
key | getKey() | string | The api key used to create the job
status | getStatus() | string | The status of the job
failure | getFailure() | failure object | If the job fails, a failure code and message will be represented within a failure object highlighting why a job failed. 
sandbox | getSandbox() | boolean | Indicates whether the job was processed on the sandbox environment
created_at | getCreatedAt() | timestamp (UTC in ISO_8601) | The time at which the job was created
finished_at | getFinishedAt() | timestamp (UTC in ISO_8601) | The time at which the job finished if successful, or null otherwise
import | getImport() | import object | If the job was started using a file imported from a remote url, the import object  will contain information about the source file
source_file | getSourceFile() | file object | The source file which was used as input to the job
target_files | getTargetFiles() | array of <code>file</code> object(s) | An array of file objects representing the converted files
target_format | getTargetFormat() | string | The name of the format which the source_file is being converted to
credit_cost | getCreditCost() | integer | The cost in conversion credits of the job
export_url | getExportUrl() | url | The location to which the converted files will be copied
exports | getExports() | array of export objects | A set of objects representing the process of copying the <code>target_files</code> to the <code>export_url</code>

## Refresh Methods
Method | Returns | Description
-------|---------|-------------
refresh() | job object | refreshes the data within the object by making a call to the API

## Status Related Methods

Status methods are used to determine the status of a job.

Method | Returns | Description
-------|---------|-------------
isStatusInitialising() | boolean | true if the status = 'initialising'
isStatusConverting() | boolean | true if the status = 'converting'
isStatusSuccessful() | boolean | true if that status = 'successful'
isStatusFailed() | boolean | true if the status = 'failed'
isStatusCancelled() | boolean | true if the status = 'cancelled'
hasCompleted() | boolean | true if the job has completed with a status of 'successful' or 'failed'

## Processing Methods

Method | Returns | Description
-------|---------|-------------
waitForCompletion() | boolean | Waits for a job to complete for a default timeout of 60 seconds. Provide a timeout parameter to override the default timeout e.g. <code>job->waitForCompletion([ 'timeout' => 120]);
cancel() | Job object | Cancels a job. Check for a status of <code>cancelled</code> or <code>isStatusCancelled()</code>

## Data Helpers

The following methods are used to determine if a job contains associated child objects or optional values.

Method | Returns | Description
-------|---------|-----------
hasSourceFile() | boolean | true if the job has a source file object
hasTargetFiles() | boolean | true if the job has one or more target files
hasFailure() | boolean | true if the job has a failure object which provides information relating to the failure of a job. Cast the failure to a <code>string</code> to get a single string representation of the failure code and message, i.e. <code>$failure = (string) $job->getFailure()</code>
hasImport() | boolean | true if the job has an Import object
hasExports() | boolean | true if the job has export objects

## File Related Methods

Once a job has been completed, the associated files can be downloaded and deleted if required.

Method | Returns | Description 
-------|---------|------------
downloadTargetFiles($params) | Job object | Downloads the converted files associated with the job
downloadAndDeleteAllFiles($params) | Job object | Downloads and deletes all files associated with the job
deleteSourceFile() | Job object | Deletes the source file associated with the job
deleteTargetFiles() | Job object | Deletes the converted files associated with the job
deleteAllFiles() | Job object | Deletes all files associated with the job

For methods related to downloading files, <code>download_path</code> will need to be specified.

```php
$job->downloadAndDeleteAllFiles([
    'download_path' => 'path/to/folder'
])
```

## Additional Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.
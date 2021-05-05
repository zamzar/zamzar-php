# Export Object

An export object is created when requesting that converted files are exported to a remote server as part of submitting a job. Where a conversion job generates one export file, the job will have one export object. Where a job generates more than one export file, the job will have an array of export objects, within the <code>exports</code> property. Refer to the [Job](job.md) and [Jobs](jobs.md) objects for more information. 

## Standard Properties

Property | Method | Type | Description
---------|--------|------|-------------
id | getId() | integer | The id associated with the export
url | getUrl() | string | The url of the file being exported
status | getStatus() | string | The status of the export
failure | getFailure() | failure object | If the export fails, a failure code and message will be represented within a failure object highlighting why the export failed. 

## Status Related Methods

Status methods are used to determine the status of an export.

Method | Returns | Description
-------|---------|-------------
isStatusInitialising() | boolean | true if the status = 'initialising'
isStatusUploading() | boolean | true if the status = 'uploading'
isStatusSuccessful() | boolean | true if that status = 'successful'
isStatusFailed() | boolean | true if the status = 'failed'
hasCompleted() | boolean | true if the export has completed with a status of 'successful' or 'failed'

## Data Helpers

The following methods are used to determine if an export contains associated child objects or optional values.

Method | Returns | Description
-------|---------|-----------
hasFailure() | boolean | true if the export has a failure object which provides information relating to the failure of an export. Cast the failure to a <code>string</code> to get a single string representation of the failure code and message, i.e. <code>$failure = (string) $export->getFailure()</code>

## Additional Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.
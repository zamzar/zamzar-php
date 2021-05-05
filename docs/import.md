# Import Object

An import object is created by starting an import, and retrieved using data retrieval methods using the [Imports object](imports.md). 

## Standard Properties

Property | Method | Type | Description
---------|--------|------|-------------
id | getId() | integer | The id associated with the import
key | getKey() | string | The api key used to create the import
url | getUrl() | string | The url to the original file
status | getStatus() | string | The status of the import
failure | getFailure() | failure object | If the import fails, a failure code and message will be represented within a failure object highlighting why the import failed. 
file | getFile() | file object | The file which was created from the import
created_at | getCreatedAt() | timestamp (UTC in ISO_8601) | The time at which the import was started
finished_at | getFinishedAt() | timestamp (UTC in ISO_8601) | The time at which the import finished if successful, or null otherwise

## Refresh Methods
Method | Returns | Description
-------|---------|-------------
refresh() | import object | refreshes the data within the object by making a call to the API

## Status Related Methods

Status methods are used to determine the status of an import.

Method | Returns | Description
-------|---------|-------------
isStatusInitialising() | boolean | true if the status = 'initialising'
isStatusDownloading() | boolean | true if the status = 'downloading'
isStatusSuccessful() | boolean | true if that status = 'successful'
isStatusFailed() | boolean | true if the status = 'failed'
hasCompleted() | boolean | true if the import has completed with a status of 'successful' or 'failed'

## Data Helpers

The following methods are used to determine if an import contains associated child objects or optional values.

Method | Returns | Description
-------|---------|-----------
hasFile() | boolean | true if the import has a file object
hasFailure() | boolean | true if the import has a failure object which provides information relating to the failure of an import. Cast the failure to a <code>string</code> to get a single string representation of the failure code and message, i.e. <code>$failure = (string) $import->getFailure()</code>

## Additional Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.
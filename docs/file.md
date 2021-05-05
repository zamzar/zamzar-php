# File Object

A file object is created by uploading a file from a local or remote source during job submission or discretely, and retrieved using data retrieval methods of the [Files](files.md) object.

## Standard Properties

Property | Method | Type | Description
---------|--------|------|-------------
id | getId() | integer | The id associated with the file
key | getKey() | string | The api key used to create the file
name | getName() | string | The name of the file
size | getSize() | integer | The size of the file in bytes 
format | getFormat() | string | The format of the file 
created_at | getCreatedAt() | timestamp (UTC in ISO_8601) | The time at which the file was created

## File Content Methods

Method | Returns | Description
-------|---------|-------------
download($params) | Downloaded File | Downloads a file to a given destination in $params [download_path = 'path/to/local/folder']

## Conversion Methods

Method | Returns | Description
-------|---------|-------------
convert($params) | Job Object | Starts a conversion job for the file using the parameters supplied. As a minium specify a [ 'target_format' => 'xxx']. See the [Jobs](jobs.md) object for full parameter information.

## Additional Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.
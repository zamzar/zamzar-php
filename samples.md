# Code Samples

The following code samples demonstrate the features of the library. Most samples can be copy/pasted into your own environment and should work 'out of the box' as long as you have a valid API key. Some samples which may not work will relate to local folder and file locations, and connected services such as Amazon S3 storage.

Jump to:

[Zamzar Client](#Initialising-a-new-ZamzarClient)

[Account](#Account)

[Files](#Files)

[Formats](#Formats)

[Imports](#Imports)

[Jobs](#Jobs)

[Exceptions](#Exceptions)

[End-to-End Job Conversion With Exceptions Handling](#End-to-End-Job-Conversion-With-Exceptions-Handling)

[Per-Object instantiation](#Per-Object-Instantiation)

[Object Endpoints](#Object-Endpoints)

## Initialising a new ZamzarClient

Initialising a ZamzarClient is the starting point for any calls to be made to the API using the library.

### Default to using the Production Environment

```php
// supply an api key
$zamzar = new \Zamzar\ZamzarClient('apikey');
```

### Use the Sandbox Environment

```php
// supply a config array with the api key and the environment 
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => 'apikey',
    'environment' => 'sandbox'
]);
```

### Configuring a Logger

The library does minimal logging, if the `debug` config option is used. Use either the supplied default logger or a psr-3 compatible logger.

```php
$client = new Zamzar\ZamzarClient([
    'api_key' => '****',
    'debug' => true,
]);

// PSR-3 Compatible Logger
\Zamzar\Zamzar::setLogger($psr3Logger);

// Using Monolog to output to a custom log file
$logger = new Logger('Zamzar');
$logger->pushHandler(new StreamHandler(__DIR__.'/app.log', Logger::DEBUG));
\Zamzar\Zamzar::setLogger($logger);
```

### Customising the Guzzle HTTP Client

This library uses Guzzle as the HTTP client. The default client can be customised by passing in a custom Guzzle client instance.

```php
// create a custom Guzzle client
$guzzle = new \GuzzleHttp\Client([
    'timeout' => 10,
    'connect_timeout' => 5,
]);

// pass the custom Guzzle client to the ZamzarClient
$zamzar = new \Zamzar\ZamzarClient([
    'api_key' => '****',
    'transport' => $guzzle
]);
```

### Viewing the Config Array

When the ZamzarClient is created, a config array is created which is used during subsequent API calls.

```php
var_dump($zamzar->getConfig());
```

### Viewing the Last Response from the API

Viewing the last response from the API can be useful for troubleshooting purposes if the raw responses from the API need to be viewed.

```php
if($zamzar->hasLastResponse()) {
    var_dump($zamzar->$lastResponse);
}
```

### Viewing the Production and Test Credits Remaining

The credits remaining are returned in the headers of every call to the API. To retrieve the credits that were available at the time of the last request, either directly access the last response, or call the helper methods on the client:

```php
$zamzar->$lastResponse->getProductionCreditsRemaining();
$zamzar->$lastResponse->getTestCreditsRemaining();

$zamzar->getLastProductionCreditsRemaining();
$zamzar->getLastTestCreditsRemaining();
```

To make a new API call and return the current credits remaining:

```php
$zamzar->getProductionCreditsRemaining();
$zamzar->getTestCreditsRemaining();
```

## Account

The account object is read-only.

```php
// get the account 
$account = $zamzar->account->get();

// view the direct properties of the account
echo $account->production_credits_remaining;
echo $account->test_credits_remaining;

// get the plan object which is a child of account and retrieved in account->get()
$plan = $account->plan;
echo $plan->name;
echo $plan->price_per_month;
echo $plan->conversions_per_month;
echo $plan->maximum_file_size;
```

## Files

File related objects are used to retrieve a list of files, upload, download and delete files. 

### Uploading a File

The <code>create</code> operator is performed on the Files object, which creates and returns a File object.

```php
$file = $zamzar->files->create([
    'name' => 'path/to/local/filename.ext'
]);

echo $file->id;
```

### Downloading a File

The <code>download</code> operator is performed against the file object once its data has been retrieved.

```php
// retrieve the file object
$file = $zamzar->files->get(123456);

// download the file to the specified folder
$file->download('path/to/download/folder');

// or in one statement
$file = $zamzar->files->get(123456)->download('path/to/download/folder');
```

### Deleting a File

The <code>delete</code> operator is performed against the file object once its data has been retrieved.

```php
// retrieve the file object
$file = $zamzar->files->get(123456);

// download the file to the specified folder
$file->delete();

// or in one statement
$file = $zamzar->files->get(123456)->delete();
```

### Converting a file

Use the <code>convert</code> operator to start a job to convert an uploaded file:

```php
$job = $file->convert([
    'target_format' => 'xxx'
]);
```


### Retrieving a file

Use the <code>get</code> operator to retrieve the data for a file and create a file object.

```php
$file = $zamzar->files->get(123456);
```

### Retrieving a list of files

Use the <code>all</code> operation to retrieve a list of files.

```php
// if limit is not specified, a maximum of 50 files are retrieved by default
$files = $zamzar->files->all(
    ['limit' => 10]
);
```

### Iterating through a list of files

Iterate through the list:

```php
$files = $zamzar->files->all();

foreach($files as $file) {
    //download and delete all files
    $file->download('path/to/download/folder');
    $file->delete();
}
```

### Paging through the Files Collection

Pagination is used to move forwards and backwards through an object collection.

```php
// initial call
$files = $zamzar->files->all();

// next page
$files = $files->nextPage();

// previous page
$files = $files->previousPage();
```

## Formats

Format related objects are used to retrieve information about file formats and valid conversion targets for them.

### Retrieving a specific format

Use the <code>get</code> operator to retrieve a specific format.

```php
$format = $zamzar->formats->get('jpg');
```

### Viewing the target conversion options for a format

```php

// simple comma separated list of valid target formats
echo $format->getTargetsToCsv();

// inspect each target format
foreach($format->targets as $targetFormat) {
    echo $targetFormat->name;
    echo $targetFormat->credit_cost;
}
```

### Retrieving a list of formats

Use the <code>all</code> operator to retrieve a list of formats.

```php
// if limit is not specified, a maximum of 50 formats are retrieved by default
$formats = $zamzar->formats->all(
    ['limit' => 10]
);
```

### Iterating through a list of formats

Iterate through the list:

```php
foreach($formats as $format) {
    // output the target formats for each format
    echo $format->name . ' ---> ' . $format->getTargetsToCsv();
}
```

### Paging through the Formats collection

Pagination is used to move forwards and backwards through an object collection.

```php
// initial call
$formats = $zamzar->formats->all();

// next page
$formats = $formats->nextPage();

// previous page
$formats = $formats->previousPage();
```

## Imports

Import related objects are used to retrieve information about imports and to start import operations.


### Starting an Import

The <code>create</code> operator is performed on the Imports object, which creates and returns an Import object.

```php
$import = $zamzar->imports->create([
    'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
]);

echo $import->id;
```

### Checking on the status of an Import

After an import has started, the status can be checked to determine one of four status values: <code>initialising</code>, <code>downloading</code>, <code>successful</code> or <code>failed</code>. 

```php
// start an import
$import = $zamzar->imports->create([
    'url' => 'https://www.zamzar.com/images/zamzar-logo.png'
]);

// wait

// refresh the import object with the latest data from the API
$import = $import->refresh();

// check for an individual status using helper methods
if ($import->isStatusInitialising()) {
    echo 'initialising';
}

if ($import->isStatusDownloading()) {
    echo 'downloading';
}

if ($import->isStatusSuccessful()) {
    echo 'successful';
} 

if ($import->isStatusFailed()) {
    echo 'failed';
}
```

Instead of checking for each status value, the <code>hasCompleted()</code> method can be used to check if an import has completed or not.

```php
if ($import->hasCompleted()) {
    if ($import->isStatusSuccessful()) {
        echo 'successful';
    } else {
        echo 'failed'; 
    }
}
```

To wait for an import to complete, the `waitForCompletion()` method can be used:

```php
$import->waitForCompletion();
```

### Checking why an import failed

If an import has failed it will have a <code>status</code> of <code>failed</code> and include a <code>failure</code> object.

```php
if ($import->isStatusFailed()) {
    if ($import->hasFailure()) {
        // cast the failure object to a string (code and message)
        echo (string) $import->failure;

        // or capture and use each value
        $code = $import->failure->code;
        $message = $import->failure->message;
    }
}
```

### Retrieving a specific import

As demonstrated above, the <code>get</code> method is used to retrieve a specific import.

```php
$import = $zamzar->imports->get(123456);
```

All successful imports produce a file object which can be used in the same way as described in the Files samples:

```php
// start a conversion job for an imported file
$job = $import->file->convert([
    'target_format' => 'pdf'
]);

// delete an imported file
$import->file->delete();
```

### Retrieving a list of imports

Use the <code>all</code> operator to retrieve a list of imports.

```php
// if limit is not specified, a maximum of 50 imports are retrieved by default
$imports = $zamzar->imports->all(
    ['limit' => 10]
);
```

### Iterating through a list of imports

Iterate through the list:

```php
foreach($imports as $import) {
    echo $import->id;
}
```

### Paging through the Imports collection

Pagination is used to move forwards and backwards through an object collection.

```php
// initial call
$imports = $zamzar->imports->all();

// next page
$imports = $imports->nextPage();

// previous page
$imports = $imports->previousPage();
```

## Jobs

Job related objects are used to retrieve information about jobs and to submit conversion jobs followed by downloading and deleting converted files.


### Starting a Job

The <code>create</code> operator is performed on the Jobs object, which creates and returns a Job object.

```php
// start a job for a local file
$job = $zamzar->jobs->create([
    'source_file' => 'path/to/local/file',
    'target_format' => 'xxx'
]);

// start a job for a remote url
$job = $zamzar->jobs->create([
    'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
    'target_format' => 'pdf'
]);

// start a job for a file which already exists on Zamzar's servers
$job = $zamzar->jobs->create([
    'source_file' => 123456,
    'target_format' => 'pdf'
]);

// start a job for a S3 file (requires Connected Services to be configured in the developer dashboard)
$job = $zamzar->jobs->create([
    'source_file' => 's3://CREDENTIAL_NAME@my-bucket-name/logo.png',
    'target_format' => 'pdf'
]);

echo $job->id;
```

### Overriding the Source Format

The source format can be overridden if the <code>source_file</code> does not have a format within the filename for example:

```php
$job = $zamzar->jobs->create([
    'source_file' => 'https://www.zamzar.com/images/zamzar-logo',
    'target_format' => 'pdf',
    'source_format' => 'png'
]);
```

### Exporting converted files to a remote server

An <code>export_url</code> can be provided to instruct the conversion process to export files to a remote server once the job has completed:

```php
$job = $zamzar->jobs->create([
    'source_file' => 'https://www.zamzar.com/images/zamzar-logo',
    'target_format' => 'pdf',
    'source_format' => 'png',
    'export_url' => 's3://...' // or (s)ftp
]);
```

### Specifying conversion options

Conversion options can be specified for certain target formats. For example, to specify a different voice for a text-to-speech conversion:

```php
$job = $zamzar->jobs->create([
    'source_file' => 'path/to/local/file.txt',
    'target_format' => 'mp3',
    'options' => [
        'voice' => 'en.female'
    ]
]);
```

### Waiting for a job to complete

The <code>waitForCompletion()</code> method is used to wait for a job to complete, which will poll the job at exponentially larger intervals upto a maximum timeout.

```php
// default timeout of 60 seconds
$job = $job->waitForCompletion();

// specify a timeout
$job = $job->waitForCompletion(120);
```

### Check on the status of a Job

After an job has started, the status can be checked to determine one of five status values: <code>initialising</code>, <code>converting</code>, <code>cancelled</code>, <code>successful</code> or <code>failed</code>. 

```php
// start a job
$job = $zamzar->jobs->create([
    'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
    'target_format' => 'pdf'
]);

// wait for completion
$job = $job->waitForCompletion();

// check for an individual status using helper methods
if ($job->isStatusInitialising()) {
    echo 'initialising';
    // wait some more
}

if ($job->isStatusConverting()) {
    echo 'converting';
    // wait some more
}

if ($job->isStatusCancelled()) {
    echo 'cancelled';
    // abort
} 

if ($job->isStatusSuccessful()) {
    echo 'successful';
    // proceed
} 

if ($job->isStatusFailed()) {
    echo 'failed';
    // inspect the failure message (see below)
}
```

Instead of checking for each status value, the <code>hasCompleted()</code> method can be used to check if a job has completed successfully or not.

```php
if ($job->hasCompleted()) {
    if ($job->isStatusSuccessful()) {
        echo 'successful';
    } else {
        echo 'failed'; 
    }
}
```

### Checking why a job failed

If a job has failed it will have a <code>status</code> of <code>failed</code> and include a <code>failure</code> object.

```php
if($job->isStatusFailed()) {
    if($job->hasFailure()) {
        // cast the failure object to a string (code and message)
        echo (string) $job->failure;
        
        // or capture and use each value
        $code = $job->failure->code;
        $message = $job->failure->message;
    }
}
```

### Downloading converted files

To download converted files:

```php
$job->downloadTargetFiles('path/to/download/folder');
```

### Deleting converted files

Various options exist for deleting files depending on the use case:

```php
// delete both source and target files
$job->deleteAllFiles();

// delete the source file
$job->deleteSourceFile();

// delete the target files
$job->deleteTargetFiles();

// expanded form using the file objects directly
$job->source_file->delete();
foreach($job->target_files as $file) {
    $file->delete();
}
```

### Method Chaining

Method chaining is supported for the processes described above.

```php
// start a job, wait, download, delete. 
// downloading and deleting files will only happen if the job status is successful
$job = $zamzar->jobs->create([
        'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
        'target_format' => 'pdf',
    ])
    ->waitForCompletion(120)
    ->downloadTargetFiles('path/to/folder')
    ->deleteAllFiles();
```

### Retrieving a specific job

As demonstrated above, the <code>get</code> method is used to retrieve a specific job.

```php
$job = $zamzar->jobs->get(123456);
```

### Retrieving a list of jobs

Use the <code>all</code> operator to retrieve a list of jobs.

```php
// if limit is not specified, a maximum of 50 jobs are retrieved by default
$jobs = $zamzar->jobs->all(
    ['limit' => 10]
);
```

### Iterate through a list of jobs

Iterate through the list:

```php
foreach($jobs as $job) {
    echo $job->getId();
}
```

### Paging through the Jobs collection

Pagination is used to move forwards and backwards through an object collection.

```php
// initial call
$jobs = $zamzar->jobs->all();

// next page
$jobs = $jobs->nextPage();

// previous page
$jobs = $jobs->previousPage();
```

## Exceptions

All of the above examples do not use exceptions handling for brevity. In real-world scenarious where the SDK is being used to support production processes, the failure of any part of the following request might cause an issue with tracking, reporting or wasting conversion credits if jobs have to be resubmitted. 

```php
//start, wait, download, delete - but don't give the job any time to complete
$job = $zamzar->jobs->create([
    'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
    'target_format' => 'pdf',
])->waitForCompletion(0);
```

The above example fails immediately, an uncaught error is reported and the process aborted. Using exception handling provides an opportunity to handle the error.

```php
try {
    $job = $zamzar->jobs->create([
        'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
        'target_format' => 'pdf',
    ])->waitForCompletion(0);
} catch (\Zamzar\Exception\TimeOutException $e) {
    echo $e->getMessage();
}
```

[Read more about exceptions](exceptions.md) to find out the custom exceptions which can be caught.


## End-to-End Job Conversion With Exceptions Handling

In a production batch processing scenario, it might be preferable to break the above job submission examples into their component parts, so that context and continuity is not lost if an exception is thrown in between what is four discrete operations - submit, wait, download, delete - whilst giving the opportunity to retry operations and audit events at a more granular level.

```php
/**
 * Submit a converion job
 */

$proceed = true;

try {
    $job = $zamzar->jobs->create([
        'source_file' => 'https://www.zamzar.com/images/zamzar-logo.png',
        'target_format' => 'pdf',
    ]);
} catch (\Zamzar\Exception\InvalidArgumentException $e) {
    echo $e->getMessage();
    $proceed = false;
} catch (\Zamzar\Exception\ApiErrorException $e) {
    echo $e->getMessage();
    $proceed = false;
}

/**
 * Proceed to next step?
 */
if($proceed) {
    // Wait for completion
    try {
        $job = $job->waitForCompletion(60);
    } catch (\Zamzar\Exception\TimeOutException $e) {
        // Retry with a longer timeout period
        echo $e->getMessage() . "\n\n";
        echo "Increasing timeout period" . "\n\n";
        $job = $job->waitForCompletion(180);
    }

    // Refresh the job object to get the latest status
    $job = $job->refresh();

    // Has the job completed successfully or not
    if($job->hasCompleted()) {
        echo 'job has completed' . "\n\n";
        
        // Is the job succcessful?
        if($job->isStatusSuccessful()) {
            // job is successful
            echo 'job is successful. downloading files' . "\n\n";
            
            // try downloading and deleting the files
            try {
                $job = $job->downloadTargetFiles('path/to/folder');

                echo 'deleting files' . "\n\n";
                $job = $job->deleteAllFiles();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo 'job has failed' . "\n\n";
        }
    } else {
        echo 'job has not completed. status is' . $job->status . "\n\n";
    }
}
```

## Object Endpoints

Each object stores its own endpoint, which is not an essential part of using the library, but might be useful for troubleshooting.

```php
foreach($jobs as $job) {
	echo $job->instanceUrl() . "\n\n";
}
```

Outputs something like:

```php
/v1/jobs/18906377

/v1/jobs/18906341

/v1/jobs/18906322

/v1/jobs/18906188

/v1/jobs/18906127
```

## Additional Information

Refer back to the [readme](readme.md) for additional information.
# Jobs Object

A job represents the process of converting a file to another format. The Jobs object can be used to start a job, wait for it's completion, download the converted files and delete all the files associated with the job which are stored on Zamzar's servers. The SDK can also be used to retrieve all or specific jobs.

All examples below make reference to <code>$zamzar</code> which is referring to an object created by initialising a new [ZamzarClient](zamzarclient.md).

```php
$zamzar = new \Zamzar\ZamzarClient('apikey');
```

## Methods

The following key methods can be used to work with the Jobs object.

### -> <code>all($params)</code>

The <code>all()</code> method is used to retrieve a list of job objects. 

```php
// returns the 50 most recent jobs by default
$jobs = $zamzar->jobs->all();   
```

#### Iterating through Jobs 

Job objects can be iterated through the <code>data</code> property of the Jobs object:

```php
// 
$jobs = $zamzar->jobs->all([
    'limit' => 10
]);

foreach($jobs->data as $job) {
    echo $job->getId() . ' ' . $job->getStatus();
}
```

#### Limiting the Results
The number of job objects returned in a single call can be limited to anything less than the maximum of 50 jobs:

```php
// retreieve the 10 most recent jobs
$jobs = $zamzar->jobs->all([
    'limit' => 10
]);
```

#### Pagination

The library supports pagination for cursor based retrieval of results:

```php
// retrieve the next page of jobs
$jobs = $jobs->nextPage();

// retrieve the previous page of jobs
$jobs = $jobs->previousPage();
```

Refer to [Pagination](pagination.md) for more information.


### -><code>get(jobid)</code>

The <code>get()</code> method is used to retrieve a single job object.

```php
$job = $zamzar->jobs->get(123456);
```


### -><code>submit($params)</code>

The <code>submit()</code> is used to submit a new job to convert a file

```php
// minimum set of parameters
$job = $zamzar->jobs->submit([
    source_file = 'path/to/local/file.ext',
    target_format = 'xxx'
])
```

The following parameters can be used when submitting a conversion job.

#### <code>source_file</code> (string) | (integer) mandatory

This can be one of the following:
- A path to a local file
- A url to a remote file - http(s) (s)ftp or s3
- The id of a file which has already been uploaded

#### <code>target_format</code> (string) mandatory

This is a format which the <code>source_file</code> is to be converted to.

#### <code>source_format</code> (string)  optional

If for any reason the format of the source file cannot be determined from the file name, or if the source format needs to be overriden, use <code>source_format</code> to provide the format.

#### <code>export_url</code> (string) optional

Once a job has completed, the converted files can be transferred to a remote url specified by the <code>export_url</code> parameter.

#### <code>download_path</code> (string) optional

Specify where to download converted files once a job has completed. By specifying this paramter, the <code>submit</code> method will wait for the job to complete, for a default timeout period.

#### <code>timeout</code> (integer) optional

Specify to override the default timeout period of 60 seconds, during which time the job is polled to check for completion.

#### <code>delete_files</code> (string) optional

Specify to delete the source and converted files once a job has been completed and the files have been downloaded.

- <code>all</code> means delete both the source and target files
- <code>source</code> means delete the source file
- <code>target</code> means delete the target (converted) files

#### Example

The following example will submit a conversion job, wait for it to complete, download the converted files to a folder, and then delete the files on Zamzar's servers.

```php
$job = $zamzar->jobs->submit([
    'source_file' => 'c:/users/downloads/profilepic.jpg',
    'target_format' => 'pdf',
    'download_path' => 'c:/users/downloads/',
    'timeout' => 120,
    'delete_files' => 'all'
]);
```

#### Job Object

The above parameters which are used to wait, download and delete files relate to the associated methods of the Job object which is returned when a job has been submitted. These methods can be used to separate each area of concern depending on the particular requirements of your workflow. Refer to the [Job Object](job.md) for more information or refer to the [Samples](samples.md) for a list of worked examples.

## More Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.
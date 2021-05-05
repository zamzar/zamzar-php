# Files Object

Files represent the input to - and output from - a conversion job. The library supports uploading, downloading and deleting files using local and remote sources, plus standard data retrieval options relating to all files stored on Zamzar.

The Files object provides methods for uploading files, plus data retrieval operations. Individual [File](file.md) objects provide options for downloading and deleting files.

All examples below make reference to <code>$zamzar</code> which is referring to an object created by initialising a new [ZamzarClient](zamzarclient.md).

```php
$zamzar = new \Zamzar\ZamzarClient('apikey');
```

## Methods

The following key methods can be used to work with the Files object.

### -> <code>all($params)</code>

The <code>all()</code> method is used to retrieve a list of file objects. 

```php
// returns the 50 most recent files by default
$files = $zamzar->files->all();   
```

#### Iterating through Files 

File objects can be iterated through the <code>data</code> property of the Files object:

```php
$files = $zamzar->files->all([
    'limit' => 10
]);

foreach($files->data as $file) {
    echo $file->getId() . ' ' . $file->getName();
}
```

#### Limiting the Results
The number of file objects returned in a single call can be limited to anything less than the maximum of 50 files:

```php
// retrieve the 10 most recent files
$files = $zamzar->files->all([
    'limit' => 10
]);
```

#### Pagination

The library supports pagination for cursor based retrieval of results:

```php
// retrieve the next page of files
$files = $files->nextPage();

// retrieve the previous page of files
$files = $files->previousPage();
```

Refer to [Pagination](pagination.md) for more information.


### -> <code>get(fileid)</code>

The <code>get()</code> method is used to retrieve a single file object.

```php
$file = $zamzar->files->get(123456);
```


### -><code>upload($params)</code>

The <code>upload()</code> method is used to upload a local file to Zamzar.

```php
$file = $zamzar->files->upload([
    name => 'path/to/local/file.ext',
]);
```

The following parameters can be used when uploading a file

#### <code>name</code>=> (string) mandatory

The path and name of the local file.

## More Information

If you wish to send a file to Zamzar from a remote server, use the [Imports](imports.md) object.

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.
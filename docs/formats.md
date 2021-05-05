# Formats Object

Formats provide valid conversion targets for files being submitted for conversion. The Formats object can be used to retrieve a list of all formats or a specific [Format](format.md) object, and provide valid conversion targets for each format.

All examples below make reference to <code>$zamzar</code> which is referring to an object created by initialising a new [ZamzarClient](zamzarclient.md).

```php
$zamzar = new \Zamzar\ZamzarClient('apikey');
```

## Methods

The following key methods can be used to work with the Formats object.

### -> <code>all($params)</code>

The <code>all()</code> method is used to retrieve a list of format objects. 

```php
// returns the the first 50 formats in alphabetical order
$formats = $zamzar->formats->all();   
```

#### Iterating through Formats

Format objects can be iterated using the <code>data</code> property of the Formats object:

```php
// get the first 10 formats
$formats = $zamzar->formats->all([
    'limit' => 10
]);

// iterate and output a list of valid conversion targets
foreach($formats->data as $format) {
    echo $format->getName() . ' ' . $format->getTargetsToCsv();
}
```

#### Limiting the Results
The number of format objects returned in a single call can be limited to anything less than the maximum of 50 formats:

```php
// retrieve 10 formats
$formats = $zamzar->formats->all([
    'limit' => 10
]);
```

#### Pagination

The library supports pagination for cursor based retrieval of results:

```php
// retrieve the next page of formats
$formats = $formats->nextPage();

// retrieve the previous page of formats
$formats = $formats->previousPage();
```

Refer to [Pagination](pagination.md) for more information.


### -> <code>get(format)</code>

The <code>get()</code> method is used to retrieve a single format object.

```php
// get the jpg format object
$format = $zamzar->format->get('jpg');

// output all the valid conversion options and the associated credit cost
foreach($format->getTargets() as $targetFormat) {
    echo $format->getName() . ' ---> ' . $targetFormat->getName() . ' (Cost=' . $targetFormat->getCreditCost() . ' conversion credits)'; 
}
```

## More Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.
# Error Object

When any particular requests fails at the point of the request being made, for example submitting a job with invalid request parameters, custom error exceptions are thrown containing one or more Error objects.

## Standard Properties

Property | Method | Type | Description
---------|--------|------|-------------
code | getCode() | integer | The code of the error
message | getMessage() | string | The associated error message
context | getContext() | string array | A variable number of context messages relating to the error

## Data Helpers

The following methods are used to determine if an error contains associated child objects or optional values.

Method | Returns | Description
-------|---------|-----------
hasContext() | boolean | true if the error has context, in which case the getContext() method can be called to return the array of context related messages.

## Examples

The following example attempts to start a conversion job for a url which does not have an extension in the filename. In such cases, a <code>source_format</code> parameter is also required.

```php
try {
    $zamzar = new \Zamzar\ZamzarClient('apikey');
  	$zamzar->jobs->submit([
      	'source_file' => 'https://www.zamzar.com/images/zamzar-logo',
      	'target_format' => 'pdf'
      ]);
} catch (\Zamzar\Exception\ApiErrorException $e) {
    foreach($e->getApiErrors() as $apiError) {
        echo $apiError->getCode() . "\n";
        echo $apiError->getMessage() . "\n";
        if($apiError->hasContext()) {
          foreach($apiError->getContext() as $context) {
            echo $context . "\n";
          }
        }
    }
}
```

```php
Output
======
Code = 10
Message = no value was specified for a mandatory parameter
Context = source_format
```

## Additional Information

Refer to the [Exceptions](exceptions.md) page for more information relating to exceptions and error handling.

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of the above properties and methods.
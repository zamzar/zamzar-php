# Failure Object

When any particular requests fail during processing, the object in question will have a failure object which contains a failure code and message. Jobs, Imports & Exports represent such objects.

## Standard Properties

Property | Method | Type | Description
---------|--------|------|-------------
code | getCode() | integer | The code of the failure
message | getMessage() | string | The associated failure message

## Standard Methods

Method | Returns | Description
-------|---------|-------------
__toString() | string | Allows for the failure to be cast to a string containing both the code and the message, e.g. <code>(string) $failure</code>


## Examples

```php
// general job failure
if($job->statusIsFailed()) {
    if($job->hasFailure()) {
        echo (string) $job->getFailure();
    }
}

// job related export failure
if($job->hasExports()) {
   foreach($job->getExports() as $export) {
       if($export->hasFailure()) {
        echo (string) $export->getFailure();        
       }
   } 
}

// import failure
if($import->statusIsFailed()) {
    if($import->hasFailure()) {
        echo (string) $import->getFailure();
    }
}
```

## Additional Information

Refer to the [Samples](samples.md) page for example use cases which demonstrate the use of all the above properties and methods.
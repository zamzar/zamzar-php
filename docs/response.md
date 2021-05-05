# ApiResponse Object

The Response Object is used internally to receive data from the API and translate the data into objects. It can however be used to interrogate the last response from the API if required, for troubleshooting purposes for example.

## -> <code>getBody()</code>

Returns the body as an PHP array of stdClass objects. Note that these are translated into the standard SDK object types (Jobs, Files etc.) elsewhere, so you cannot use the standard properties and methods to manipulate these objects.

```php
// get the latest 5 jobs
$zamzar->jobs->all(['limit' => 5]);

// var_dump the entire body
var_dump($zamzar->getLastResponse()->getBody());

// or iterate through stdClass objects
foreach($zamzar->getLastResponse()->getBody() as $object) {
  var_dump($object);
}
```

## -> <code>getBodyRaw()</code>

Returns the raw json:

```php
// get the raw json
echo $zamzar->getLastResponse()->getBodyRaw();

// will output something like...
// {"paging":{"total_count":1680,"limit":1,"first":18861965,"last":18861965},"data":[{"id":18861965,"key":"apikey","status":"successful","sandbox":false,"created_at":"2021-03-15T15:55:31Z",...
```

## -> <code>getCode()</code>

Returns the HTTP response code:

```php
echo $zamzar->getLastResponse()->getCode();
```

## -> <code>getHeaders()</code>

Returns the standard response headers:

```php
var_dump($zamzar->getLastResponse()->getHeaders());
```

## -> <code>getData()</code>

Returns the data array from the body of the response. Typically this will contain an array of stdClass objects.

```php
var_dump($zamzar->getLastResponse()->getData());
```

## -> <code>getPaging()</code>

Returns the paging object from the body. This will only apply to high level endpoints which return lists of data (Files, Formats, Imports, Jobs).

```php
var_dump($zamzar->getLastResponse()->getPaging());
```

## -> <code>getProductionCreditsRemaining()</code>

Returns the production credits remaining on the account associated with the API key being used. 

```php
// get production credits from the last response
echo $zamzar->getLastResponse()->getProductionCreditsRemaining();

// or more conveniently use the zamzar client directly
echo $zamzar->getProductionCreditsRemaining();
```

## -> <code>getTestCreditsRemaining()</code>

Returns the test credits remaining on the account associated with the API key being used. 

```php
// get test credits from the last response
echo $zamzar->getLastResponse()->getTestCreditsRemaining();

// or more conveniently use the zamzar client directly
echo $zamzar->getTestCreditsRemaining();
```

# Paged Collections

When a request is made for a list of objects, the response will be a paged collection.

## Applies To

- Files Object / Endpoint
- Formats Object / Endpoint
- Imports Object / Endpoint
- Jobs Object / Endpoint

## Background

If you're already familiar with the Zamzar API, you'll know that a response to any of the above endpoints includes a paging object in the response body which contains the following properties:

- <code>first</code> is the id of the first record on the page 
- <code>last</code> is the id of the last record on the page
- <code>limit</code> refers to the maximum number of elements the page could contain
- <code>total_count</code> refers to the total number of records across the wider collection

```php
"paging":{"total_count":1682,"limit":50,"first":18882270,"last":18856502}
```

When using the API directly, these properties would be used to request the next or previous page in the collection, via calls like this:

```php
// Next Page
https://api.zamzar.com/v1/jobs/?after=18856502

// Previous Page
https://api.zamzar.com/v1/jobs/?before=18882270
```

Within the SDK library, the same properties and parameters are used internally to provide paging methods, but can still be referenced directly if a manual process is required.

## The Paging Object

The Paging object is exposed in any of the list based classes (Files, Formats, Imports, Jobs).

```php
$zamzar = new \Zamzar\ZamzarClient('apikey');
$jobs = $zamzar->jobs->all();
echo $jobs->paging->first;
echo $jobs->paging->last;
echo $jobs->paging->limit;
echo $jobs->paging->total_count;
```

## Paging Methods

To navigate to the next or previous page, use the nextPage() or previousPage() methods.

```php
// Move to the next page
$jobs = $jobs->nextPage();

// Move to the previous page
$jobs = $jobs->previousPage();
```

When using these methods, the record limits requested will be observed automatically. For example, if the first request is for 10 records, <code>nextPage</code> and <code>previousPage</code> will request the next or previous 10 records respectively.

```php
// request 10 records
$jobs = $zamzar->jobs([
    'limit' => 10
]);

// request the next 10 records
$jobs = $jobs->nextPage();

// request the previous 10 records
$jobs = $jobs->previousPage();
```

## Manual Approach

If the <code>nextPage</code> and <code>previousPage</code> functions are not suitable, for example in a stateless based context where an object cannot be saved in memory and referenced later, the paging object or properties can be captured using cookies, href's, or some other method, and used in subsequent calls.

```php 
// Context 1

// get 20 jobs
$zamzar = new \Zamzar\ZamzarClient('apikey');
$jobs = $zamzar->jobs->all([
    'limit' => 20
])

// capture the paging properties
$first = $jobs->paging->next;
$last = $jobs->paging->last;
$limit = $jobs->paging->limit; 

// store the properties or use as href's for example

// Context 2

// next 10 records
$zamzar = new \Zamzar\ZamzarClient('apikey');
$jobs = $zamzar->jobs([
    'after' => $_GET['last'],
    'limit' => $_GET['limit']
]);

//previous 10 records
$jobs = $zamzar->jobs([
    'before' => $_GET['first'],
    'limit' => $_GET['limit']
]);
```


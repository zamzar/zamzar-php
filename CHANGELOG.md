## 2.0.0 - 2023-06-22

Internal optimisations and improved developer experience. Contains some breaking changes and deprecated methods.

* [#2](https://github.com/zamzar/zamzar-php/pull/2) Conform to PSR-12

* [#3](https://github.com/zamzar/zamzar-php/pull/3) Simplify response caching

* [#4](https://github.com/zamzar/zamzar-php/pull/4) Reduce Logging noise

    - Add `'debug' => true` when instantiating the Zamzar Client to output to the log.

* [#5](https://github.com/zamzar/zamzar-php/pull/5) Fix Credits Remaining bugs

    - Use `getLastResponse()->getProductionCreditsRemaining()` to get the credits remaining from the last response.

* [#6](https://github.com/zamzar/zamzar-php/pull/6) Reorganise Core methods

* [#7](https://github.com/zamzar/zamzar-php/pull/7) Extract params

    - Use `create()` when creating new jobs, files and imports, instead of `submit()`, `upload()` and `start()` respectively, e.g.

        - `$zamzar->jobs->create([...])`

    - Specify parameters directly for methods instead of passing them within an array:

        - Use `$job->waitForCompletion(30)`

          - instead of `$job->waitForCompletion(['timeout' => 30])`

        - Use `$job->downloadTargetFiles('path/to/folder')`

          - instead of `$job->downloadTargetFiles(['download_path => '...']`

        - Use `$file->download('path/to/folder')`
        
          - instead of `$file->download('download_path' => '...'])`

* [#8](https://github.com/zamzar/zamzar-php/pull/8) Add generic objects & collections

    - Getter methods are deprecated and will be removed in a future release

        - Access properties directly instead of using `get()` methods, e.g.

            - Use `$file->id` instead of `$file->getId()`
            - Use `$job->id` instead of `$job->getId()`

    - Improved support for collections

        - When iterating through a list of jobs, you no longer have to use the data property

          - `foreach($jobs as $job) { ... }`

            - instead of `foreach($jobs->data as $job) { ... }`

    - A `waitForCompletion()` method has been added to imports

    - Removed support for instantiating services directly, always use the `ZamzarClient`

    - Use `instanceUrl()` instead of `getEndpoint()` to retrieve an object's endpoints, e.g.

      - `$job->instanceUrl()`


* [#9](https://github.com/zamzar/zamzar-php/pull/9) Remove the sample app

Refer to the [code samples](samples.md) for examples.

## 1.0.1 - 2021-05-07
* Updated the sample app and instructions

## 1.0.0 - 2021-05-07
* Initial release of the Zamzar SDK for PHP
* Consolidated documentation

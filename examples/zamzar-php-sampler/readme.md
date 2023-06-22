# SDK Sampler App

The SDK Sampler App was built using the PHP-SDK to demonstrate all the key areas of functionality. Code Snippets are provided on every page.

## Installing the App

1. Download the [latest release](https://github.com/zamzar/zamzar-php/releases) of the library which includes the sample app.

2. In a console window, navigate to the sample app folder:

    ```
    cd downloads/zamzar-php-x.x.x/examples/zamzar-php-sampler
    ```

3. Install the Zamzar Library and associated dependencies using composer:

    ```
    composer install
    ```

4. Update the <code>config.php</code> in the root of the app:

    ```
    'api-key' => 'your_api_key',
    'environment' => 'sandbox', // or production
    ```

5. Start a PHP Server

    ```
    php -S localhost:8000
    ```

6. Open [http://localhost:8000](http://localhost:8000) in your browser

The home page of the app explains the features.

## Additional Information

View the [SDK Code Samples](/samples.md) for more examples.

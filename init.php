<?php

// Utilities
require __DIR__ . '/lib/Util/LoggerInterface.php';
require __DIR__ . '/lib/Util/DefaultLogger.php';
require __DIR__ . '/lib/Zamzar.php';

// HTTPClient
require __DIR__ . '/lib/HttpClient/GuzzleClient.php';

// Exception
require __DIR__ . '/lib/Exception/InvalidArgumentException.php';
require __DIR__ . '/lib/Exception/ApiErrorException.php';
require __DIR__ . '/lib/Exception/AccountException.php';
require __DIR__ . '/lib/Exception/AuthenticationException.php';
require __DIR__ . '/lib/Exception/InvalidRequestException.php';
require __DIR__ . '/lib/Exception/InvalidResourceException.php';
require __DIR__ . '/lib/Exception/PayloadException.php';
require __DIR__ . '/lib/Exception/RateLimitException.php';
require __DIR__ . '/lib/Exception/TimeOutException.php';
require __DIR__ . '/lib/Exception/UnknownApiErrorException.php';

// API Operations
require __DIR__ . '/lib/ApiOperations/WaitForCompletion.php';

// Services
require __DIR__ . '/lib/Service/AbstractService.php';
require __DIR__ . '/lib/Service/AccountService.php';
require __DIR__ . '/lib/Service/FileService.php';
require __DIR__ . '/lib/Service/FormatService.php';
require __DIR__ . '/lib/Service/ImportService.php';
require __DIR__ . '/lib/Service/JobService.php';

// Plumbing
require __DIR__ . '/lib/ZamzarObject.php';
require __DIR__ . '/lib/ApiResource.php';
require __DIR__ . '/lib/Collection.php';
require __DIR__ . '/lib/ApiRequestor.php';
require __DIR__ . '/lib/ApiResponse.php';

// ZamzarClient
require __DIR__ . '/lib/Contracts/ClientInterface.php';
require __DIR__ . '/lib/BaseZamzarClient.php';
require __DIR__ . '/lib/ZamzarClient.php';

// Zamzar API Resources
require __DIR__ . '/lib/Account.php';
require __DIR__ . '/lib/Error.php';
require __DIR__ . '/lib/Failure.php';
require __DIR__ . '/lib/File.php';
require __DIR__ . '/lib/Format.php';
require __DIR__ . '/lib/TargetFormat.php';
require __DIR__ . '/lib/Import.php';
require __DIR__ . '/lib/Job.php';
require __DIR__ . '/lib/Plan.php';

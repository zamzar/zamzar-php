# Account Object

Every API key is associated with an account. The Account Object allows the retrieval of account and plan information (represented as a child object under Account), both of which are represented below.

## Account Object Properties

Property | Method | Type | Description
---------|--------|------|-------------
test_credits_remaining | getTestCreditsRemaining() | integer | The number of test credits remaining for this account
production_credits_remaining | getProductionCreditsRemaining() | integer | The number of production credits remaining for this account
plan | getPlan() | Plan object | The name of the file

## Plan Object Properties

Property | Method | Type | Description
---------|--------|------|-------------
name | getPlanName() | string | The number of test credits remaining for this account
price_per_month | getPricePerMonth() | decimal | The number of production credits remaining for this account
conversions_per_month | getConversionsPerMonth() | integer | The name of the file
maximum_file_size | getMaximumFileSize() | integer | The name of the file

## Methods

### -> <code>Account->get()</code>

Retrieve the account and plan information using the <code>get()</code> method.

```php
$zamzar = new \Zamzar\ZamzarClient('apikey');

$account = $zamzar->account->get();
$plan = $account->getPlan();

echo $account->getTestCreditsRemaining();
echo $account->getTestCreditsRemaining();
echo $plan->getPlanName();
echo $plan->getPricePerMonth();
echo $plan->getConversionsPerMonth();
echo $plan->getMaximumFileSize();
```
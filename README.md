# EagleView PHP SDK

This is an unofficial PHP SDK for [EagleView's REST API](https://restdoc.eagleview.com/).

THIS SDK IS NOT YET PRODUCTION READY.

## Introduction

The EagleView SDK provides an expressive interface for interacting with the EagleView REST API and managing different resources. Before we dive in, here are some examples of what to expect:

```php
use ThinkUp\EagleView\EagleView;

// One of three authentication methods
$eagleView = EagleView::withRawToken('your-access-token');

// Get available products
$products = $eagleView->getAvailableProducts(); // array of "ThinkUp\EagleView\Resources\Product" objects

// Access what you need
$products[0]->name;                             // EagleView Inform Essentials+
$products[0]->description;                      // Inform Essentials+
$products[0]->isTemporarilyUnavailable;         // false
$products[0]->deliveryProducts[1]->name;        // Express
```

This SDK has been built and documented in such a way that your IDE should provide autocomplete functionality as you build your integration.

## Documentation

### Requirements

Just PHP 7.2 or greater and composer, so you can install this SDK.

### Installation

Install the SDK in your project via composer:

```bash
composer require thinkup-technologies/eagleview-php-sdk
```

### Authentication

EagleView's REST API uses OAuth2 Token Authentication. This SDK will take care of creating and refreshing tokens for you. However, you will need to store some token details on your end in order to comply with OAuth2 standards.

#### Initial Log In

Here is an example of how to establish an **initial** connection to EagleView's REST API:

```php
use ThinkUp\EagleView\EagleView;

// You'll need the following info:
$username = 'your_account@eagleview.com';
$password = 'your-secret-password';
$sourceId = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
$clientSecret = 'your-super-duper-client-secret';
$endpoint = 'https://webservices-integrations.eagleview.com';

// Establish a connection with EagleView using your data
$eagleView = EagleView::login($username, $password, $sourceId, $clientSecret, $endpoint);
```

This will create a new SDK instance and route all api calls to "https://webservices-integrations.eagleview.com". You are not required to provide an `$endpoint` value and by default, the SDK will use the above URL. If you attempt to log in with invalid authentication details, an exception will be thrown.

At this point, you are ready to make calls to EagleView's REST API. But before you do, keep reading to make sure you understand how to properly upkeep the connection you just made.

**IMPORTANT**: You should not be logging in on every request lifecycle or everytime you need to make an API call or consume resources from EagleView. The above is meant to be the initial login only and should be unique to whatever primary resource integrates with EagleView. In most cases that's your users, companies or equivalent.

#### Storing Token Details

Once you have established a successful, **initial** connection, you should store a few of the token details in your database or storage of choice so that you can reuse them on subsequent calls.

```php
// Store the following on your end per user, company, etc.
$accessToken = $eagleView->token->access_token      // string: Primary token that's used for accessing the API
$refreshToken = $eagleView->token->refresh_token    // string: Refresh token that's used to fetch new access tokens
$issuedAt = $eagleView->token->issued_at            // string: Datetime UTC string of when the token was issued
$expiresAt = $eagleView->token->expires_at          // string: Datetime UTC string of when the token will expire
```

You should store `$accessToken`, `$refreshToken`, `$issuedAt`, and `$expiresAt` on your end so that you can retrieve them later.

#### Using Stored Token Details

To reuse the token details you stored above, you will need to retrieve them from your storage system and re-establish a connection with EagleView like so:

```php
use ThinkUp\EagleView\EagleView;
use ThinkUp\EagleView\Resources\Token;

// Retrieve these four values from your database or storage system
$accessToken = 'the-access-token-you-stored';
$refreshToken = 'the-refresh-token-you-stored';
$issuedAt = 'the-issued-at-datetime-you-stored';
$expiresAt = 'the-expires-at-datetime-you-stored';

// Also provide the source id and client secret so that tokens can be refreshed
$sourceId = 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX';
$clientSecret = 'your-super-duper-client-secret';

// Now, build up a token from your data
$token = Token::create($accessToken, $refreshToken, $issuedAt, $expiresAt);

// And create a connection to EagleView with your token, source id and client secret 
$eagleView = EagleView::withToken($token, $sourceId, $clientSecret);

// Nice, you are ready make API calls
$products = $eagleView->getAvailableProducts();
```

If your token has expired or is about to expire, the SDK will automatically renew it for you. However, you should still check to see if a new token has been issued by comparing the `$eagleView->token` object with what's stored on your end and [update the stored details with fresh values](#storing-token-details).

#### Manually Managing Tokens

If you'd rather handle authentication and the creating, refreshing and manging of tokens yourself and simply just want to consume EagleView's API via the SDK, that's fine too. All you will need to do is provide an access token (bearer token) to the SDK.

```php
use ThinkUp\EagleView\EagleView;

// Create a connection to EagleView with just a bearer token
$eagleView = EagleView::withRawToken('your-access-token');

// Done. Make your API calls.
$products = $eagleView->getAvailableProducts();
```

Keep in mind that with this approach the SDK will not be able to refresh your token once it expires. That will be your responsibility. Additionally, the SDK provides a public method for refreshing tokens if you'd like to use that rather than rolling your own implementation.

### Basic Usage

As mentioned earlier, the SDK is self documenting and most IDEs will provide autocomplete for you as work with the SDK. Even so, below you will find basic examples and how to access the resources you need.

#### Get Available Products

See [GET v2/Product/GetAvailableProducts](https://restdoc.eagleview.com/#GetAvailableProducts) for more info.

```php
// Get available products
$products = $eagleView->getAvailableProducts(); // array of "ThinkUp\EagleView\Resources\Product" objects

// Access what you need
$products[0]->name;                             // EagleView Inform Essentials+
$products[0]->description;                      // Inform Essentials+
$products[0]->isTemporarilyUnavailable;         // false
$products[0]->deliveryProducts[1]->name;        // Express
```

#### Place Order

See [POST v2/Order/PlaceOrder](https://restdoc.eagleview.com/#PlaceOrder) for more info.

```php
// Build your payload to have all data necessary to place the order...
$payload = [...];

// Place the order...
$response = $eagleView->placeOrder($payload); // $response should contain an order id as well as report ids

// Access what you need
$response['OrderId'];       // 1
$response['ReportIds'][0]   // 123456
$response['ReportIds'][1]   // 123457
```

#### Get Report (v2)

See [GET v2/Report/GetReport](https://restdoc.eagleview.com/#GetReport) for more info.

```php
// The ID of the report you would like to get...
$reportId = 49827746;

// Get the report...
$report = $eagleView->getReportV2($reportId);

// Access what you need
$report['Street'];      // 52 Mapl'e Ave
$report['City']         // Annandale
$report['Status']       // In Process
```

#### Get Report (v3)

See [GET v3/Report/GetReport](https://restdoc.eagleview.com/#V3GetReport) for more info.

```php
// The ID of the report you would like to get...
$reportId = 49827746;

// Get the report...
$report = $eagleView->getReportV3($reportId);

// Access what you need
$report['Street'];      // 52 Mapl'e Ave
$report['City']         // Annandale
$report['Status']       // In Process
```

#### Get Report File

See [GET v1/File/GetReportFile](https://restdoc.eagleview.com/#GetReportFile) for more info.

```php
$reportId = 49827746;   // The ID of the report you would like to get...
$fileType = 2;          // Code to specify the file type for the report (invoice, top image, etc.)
$fileFormat = 2;        // Code to specify the file format for the report (pdf, json, etc.)

// Get the report file raw contents...
$reportData = $eagleView->getReportFile($reportId, $fileType, $fileFormat);

// Save the report to a local file...
file_put_contents('my-awesome-report.pdf', $reportData);
```

_More documentation to come..._

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
$products = $eagleView->getAvailableProducts();
$products[0]->name;                         // EagleView Inform Essentials+
$products[0]->description;                  // Inform Essentials+
$products[0]->isTemporarilyUnavailable;     // false
$products[0]->deliveryProducts[1]->name;    // Express
```

## Documentation

### Requirements

PHP 7.2 or greater

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

This will create a new SDK instance and route all api calls to "https://webservices-integrations.eagleview.com". You are not required to provide an `$endpoint` and by default, the SDK will use the above URL. If you attempt to log in with invalid authentication details, an exception will be thrown.

At this point, you are ready to make calls to EagleView's REST API. But before you do, keep reading to make sure you understand how to properly upkeep the connection you just made.

**IMPORTANT**: You should not be logging in everytime you need to make an API call or consume resources from EagleView. The above is meant to be the initial login only and should be unique to whatever primary resource integrates with EagleView. In most cases that's your users, companies or equivalent.

#### Storing Token Details

Once you have established a successful, **initial** connection, you should store a few of the token details in your database or storage of choice so that you can reuse them on subsequent calls.

```php
// Store the following on your end per user, company, etc.
$accessToken = $eagleView->token->access_token      // string: Primary token that's used for accessing the API
$refreshToken = $eagleView->token->refresh_token    // string: Refresh token that's used to fetch new access token
$issuedAt = $eagleView->token->issued_at            // string: Datetime UTC string of when the token was issued
$expiresAt = $eagleView->token->expires_at          // string: Datetime UTC string of when the token will expire
```

You should store `$accessToken`, `$refreshToken`, `$issuedAt`, and `$expiresAt` on your end so that you can retrieve them later.

#### Using Stored Tokens

To reuse the token details you stored above, you will need to retrieve them from your storage system and re-establish a connection with EagleView like so:

```php
use ThinkUp\EagleView\EagleView;
use ThinkUp\EagleView\Resources\Token;

// Retrieve these four values from your storage
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

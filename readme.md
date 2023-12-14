# SMS.NET.BD SMS Package for Laravel

[![Packagist version](https://img.shields.io/packagist/v/sms.net.bd/sms?v=1)](https://packagist.org/packages/sms.net.bd/sms) [![mit](https://img.shields.io/badge/License-MIT-green)](https://packagist.org/packages/sms.net.bd/sms) ![Packagist Downloads](https://img.shields.io/packagist/dt/sms.net.bd/sms?v=1)

SMS Package for Laravel - Simplify SMS integration with the SMS Gateway from [sms.net.bd](https://www.sms.net.bd/api). Send messages, check balance, get delivery reports, and manage SMS effortlessly in your Laravel applications.

The SMS Laravel package provides convenient access to the sms.net.bd REST API from php applications.

Sign up for a [free sms.net.bd account](https://www.sms.net.bd/signup/) today and get your API Key from our advanced SMS platform. Plus, enjoy free credits to try out your API in full!

## Example

Check out the other code [examples](https://www.sms.net.bd/api#:~:text=SMS%20API%20Code-,samples,-.)

## Features

- Send SMS messages through the [sms.net.bd sms gateway](https://www.sms.net.bd/api).
- Schedule SMS messages for future delivery.
- Send SMS to multiple recipients with sender id
- Retrieve SMS delivery reports.
- Check account balance and balance validity.

## Requirements

- Laravel Framework 7.x
- PHP 7.2 or higher

## Installation

Install the package via Composer:

```bash
composer require sms_net_bd/sms
```

Set your SMS API key in the `.env` file:

```bash
SMS_NET_BD_API_KEY=your-api-key
```

Note: Ensure to replace placeholder `your-api-key` with your actual API key

## Usage

```php
use sms_net_bd\SMS; // Import the SMS class

// Create an instance of the class
$sms = new SMS();

try {
    // Send Single SMS
    $response = $sms->sendSMS(
        "Hello, this is a test SMS!",
        "01701010101"
    );

    // Send Multiple Recipients SMS
    $response = $sms->sendSMS(
        "Hello, this is a test SMS!",
        "01701010101,+8801856666666,8801349494949,01500000000"
    );

    // Send SMS With Sender ID or Masking Name
    $response = $sms->sendSMS(
        "Hello, this is a test SMS!",
        "01701010101",
        "Alpha Net"
    );

    // Schedule SMS for future delivery
    $response = $sms->sendScheduledSMS(
        "Scheduled SMS",
        "8801701010101",
        "2023-12-01 14:30:00" // Date format: YYYY-MM-DD HH:MM:SS
    );

    // Schedule SMS for future delivery with Sender ID
    $response = $sms->sendScheduledSMS(
        "Scheduled SMS with date",
        "8801701010101",
        "2023-12-01 14:30:00",
        "Alpha Net"
    );

    // Get SMS delivery report
    $report = $sms->getReport($requestId);

    // Check account balance
    $balanceInfo = $sms->getBalance();

} catch (Exception $e) {
    // handle $e->getMessage();
}
```

Note: Ensure to replace placeholder values with your phone numbers and messages.

## Error Handling

The package provides better error handling. If the API response has an error (error != 0), an exception is thrown with the error message.

```php
try {
    $response = $sms->sendSMS('Invalid Recipient', '+invalid-number');
} catch (\Exception $e) {
    // Handle the exception, log the error, or display a user-friendly message.
    echo 'Error: ' . $e->getMessage();
}
```

## License

This package is open-source software licensed under the [MIT license](LICENSE.md)

## Contribution

Contributions are welcome! Feel free to submit [issues](https://github.com/smsnetbd/sms-net-bd-laravel/issues) or [open a pull request](https://github.com/smsnetbd/sms-net-bd-laravel/pulls).

## Support

If you have any questions or feedback, please [open an issue](https://github.com/smsnetbd/sms-net-bd-laravel/issues) or [open a pull request](https://github.com/smsnetbd/sms-net-bd-laravel/pulls).

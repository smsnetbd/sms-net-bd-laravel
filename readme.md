# Alpha SMS Package for Laravel

[![Packagist version](https://img.shields.io/packagist/v/alphanetbd/sms)](https://packagist.org/packages/alphanetbd/sms) [![mit](https://img.shields.io/badge/License-MIT-green
)](https://packagist.org/packages/alphanetbd/sms) ![Packagist Downloads](https://img.shields.io/packagist/dt/alphanetbd/sms)

SMS Package for Laravel - Simplify SMS integration with the SMS Gateway from [sms.net.bd](https://www.sms.net.bd/api)/[alpha.net.bd](https://alpha.net.bd/SMS/API/). Send message, check balance, get delivery reports, and manage SMS effortlessly in your Laravel applications.

## Features

- Send SMS messages through the [Alpha Net SMS Gateway](https://www.sms.net.bd).
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
composer require alphanetbd/sms
```

Set your SMS API key in the `.env` file:

```bash
ALPHANETBD_SMS_API_KEY=your-api-key
```

## Usage

```php
use Alphanetbd\SMS\AlphaSMS;

// Create an instance of AlphaSMS
$sms = new AlphaSMS();

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
        "2023-12-01 14:30:00"
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

Note: Ensure to replace placeholder values with your actual API key, phone numbers, and messages.

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

Contributions are welcome! Feel free to submit [issues](https://github.com/alphanetbd/alpha-sms-laravel/issues) or [open a pull request](https://github.com/alphanetbd/alpha-sms-laravel/pulls).

## Support

If you have any questions or feedback, please [open an issue](https://github.com/alphanetbd/alpha-sms-laravel/issues) or [open a pull request](https://github.com/alphanetbd/alpha-sms-laravel/pulls).

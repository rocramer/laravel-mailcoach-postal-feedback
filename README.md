![run-tests](https://github.com/rocramer/laravel-mailcoach-postal-feedback/workflows/run-tests/badge.svg)

# Process feedback for email campaigns sent using Postal

This is an unofficial package for [mailcoach.app](https://mailcoach.app/) that can process the feedback given by [Postal](https://github.com/postalhq/postal).

## Requirements

* Laravel 8.x
* Mailcoach 3.x

## Setup

The package can be installed via composer:

```bash
composer require rocramer/laravel-mailcoach-postal-feedback
```
You need to configure an endpoint in your Postal dashboard and register your routes in your Laravel app:

```php
Route::postalFeedback('postal-feedback');
```

Since Postal webhooks need to bypass Laravel's CSRF protection, be sure to list the URI as an exception in your VerifyCsrfToken middleware or list the route outside the web middleware group:

```php
protected $except = [
    'postal-feedback',
];
```
To verify the signature, add following to your `mailcoach.config`:

```php
'postal_feedback' => [
    'public_key' => env('POSTAL_PUBLIC_KEY'),
],
```
Finally, add your public key to the `.env` file.  You can get the public key by executing `postal default-dkim-record` on your Postal server and copy the `p` parameter (without the semicolon at the end).

## Restriction

This package only handles Bounces, Opens and Clicks. (Spam) complaints are not supported by Postal. Other mail delivery platforms like Amazon SES usually handle these complaints via a [Feedback Loop (FBL)](https://support.google.com/mail/answer/6254652?hl=en). Postal has no mechanism to process these complaints from mail provider and link them back to their original message. Nevertheless, it is possible to route these complaints to an abuse team or to use services like [Google Postmaster](https://www.google.com/url?sa=t&rct=j&q=&esrc=s&source=web&cd=&cad=rja&uact=8&ved=2ahUKEwi-77anwZ_sAhXhzoUKHWjFC1UQFjAAegQIAhAC&url=https%3A%2F%2Fwww.gmail.com%2Fpostmaster%2F&usg=AOvVaw1BGfC42LJItAWxj8MhLBHe) to have an overview about complain rates.

## Postal driver for Laravel

To send emails in Laravel via Postal you can use [SynergiTech/laravel-postal](https://github.com/SynergiTech/laravel-postal).

## Documentation

You can view the Mailcoach documentation on [the mailcoach site](https://mailcoach.app).

The Postal documentation can be found on [Github](https://github.com/postalhq/postal/wiki)

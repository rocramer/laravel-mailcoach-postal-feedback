# WIP

# Process feedback for email campaigns sent using Postal

This is an unofficial package for [spatie/laravel-mailcoach](https://github.com/spatie/laravel-mailcoach) that can process the feedback given by [Postal](https://github.com/postalhq/postal).

## Setup

The package can be installed via composer:

```bash
composer require rocramer/laravel-mailcoach-postal-feedback
```
You need to configure an endpoint in your Postal dashboard and register your routes in your Laravel app:

```php
Route::postalFeedback('postal-feedback');
```

Since Postal webhooks need to bypass Laravel's CSRF protection, be sure to list the URI as an exception in your VerifyCsrfToken middleware or list the route outside of the web middleware group:

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
Finally, add your public key in vor `.env` file.  You can get the public key by executing `postal default-dkim-record` on your Postal server and copy the `p` parameter (without the semicolon at the end).

## Documentation

You can view the Mailcoach documentation on [the mailcoach site](https://mailcoach.app).

The Postal documentation can be found on [Github](https://github.com/postalhq/postal/wiki)

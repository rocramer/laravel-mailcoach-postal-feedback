# WIP

# Process feedback for email campaigns sent using Postal

This is an unofficial package for [spatie/laravel-mailcoach](https://github.com/spatie/laravel-mailcoach) that can process the feedback given by [Postal](https://github.com/postalhq/postal).

## Setup

After installation, you need to create your webhook endpoint:

```php
Route::postalFeedback('postal-feedback');
```

You need to configure this endpoint in your Postal dashboard as well.

## Documentation

You can view the Mailcoach documentation on [the mailcoach site](https://mailcoach.app).

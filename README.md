# One Campaign Monitor

Craft CMS Plugin for Campaign Monitor Integration

## Installation

### Composer

The easiest way to install One Campaign Monitor is via [composer](https://getcomposer.org). Add the following to your `composer.json`:

```
"repositories": [
    ...
    {
        "type": "vcs",
      	"url": "https://github.com/onedesign/onecampaignmonitor.git"
    }
],
"require": {
    ...
    "onedesign/onecampaignmonitor": "dev-feature/craft-3",
}
```

Then, from the root of your project, run `composer install`.

If you keep your project in a git repository, you should also add `craft/plugins/onecampaignmonitor` to your `.gitignore`.

## Manual

If you'd prefer not to install via composer, clone or download the code from this repo, and drop it into `craft/plugins/onecampaignmonitor`.

You'll also need to install [campaignmonitor/createsend-php](https://github.com/campaignmonitor/createsend-php) by dropping its contents into `vendor/campaignmonitor/createsend-php`.

## Environment Variables

`CAMPAIGN_MONITOR_API_KEY` must be included as an environment variable for this project, in order to authorize calls to the API.

`GOOGLE_RECAPTCHA_SECRET_KEY` is an optional environmental variable. If included, it will check for `g-recaptcha-response` in the post request and verify that the recaptcha response is valid.

## Subscribing to a list

Add subscriptions to a list using either a POST route, the OneCampaignMonitor service, or variable.

Adding subscriptions requires a List ID and an email, but there are a few other fields you can send:

* List ID (required)
* email address (required)
* boolean for whether to resubscribe addresses that have previously been removed (default true)

Note about the **list ID**: this is _not_ the parameter "listID" in the URL when viewing a list. Annoyingly, Campaign Monitor uses two IDs for lists. To find your List ID, view [this FAQ](https://createform.com/support/campaignmonitor-list).

### POSTing a form

The easiest way to add subscribers is by posting a form with their data. This follows the standard Craft form template, with some required and some optional fields.

```
<form method="POST">
  <!-- craft form fields -->
  {{ actionInput('one-campaign-monitor/subscribers/add') }}
  {{ redirectInput(craft.app.request.pathInfo) }}
  {{ csrfInput() }}

  <!-- required fields ** REPLACE "myListId" WITH YOUR LIST ID ** -->
  <input type="hidden" name="listId" value="myListId" />
  <label for="email">Email (required)</label>

  <!-- optional fields -->
  <input type="hidden" name="resubscribe" value="0" />

  <input type="submit" value="Subscribe!" />
</form>
```

### Responses

If adding a subscriber fails, the call to either method below will return false, and will log the error (available in `/admin/utils/logs`).

If successful, the methods will return true and not log.

### Recaptcha Validation

There is an option to validate a Google Recaptcha parameter by adding a Google Recaptcha Secret Key to the plugin settings. When the Add Subscribers action is called, it will look for a param `g-recaptcha-response` which will be validated against that Recaptcha Secret key. If it fails, the subscriber will not be added.

## A note on blocking calls

It looks like the library this depends on, `createsend-php`, executes these CURL requests using sockets when available. (See `vendor/campaignmonitor/createsend-php/class/transport.php`.) If that's the case, then all CURL requests are performed asyncronously. Consider this a TODO to investigate and update responses.
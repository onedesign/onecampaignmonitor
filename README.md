# One Campaign Monitor

Craft CMS Plugin for Campaign Monitor Integration

## Installation

### Composer

The easiest way to install One Campaign Monitor is via [composer](https://getcomposer.org). Add the following to your `composer.json`:

```
"repositories": [
    ...
    {
        "type": "git",
        "url": "git@github.com:onedesign/onecampaignmonitor.git"
    }
],
"require": {
    ...
    "onedesign/onecampaignmonitor": "0.1.3"
}
```

Then, from the root of your project, run `composer install`.

If you keep your project in a git repository, you should also add `craft/plugins/onecampaignmonitor` to your `.gitignore`.

## Manual

If you'd prefer not to install via composer, clone or download the code from this repo, and drop it into `craft/plugins/onecampaignmonitor`.

You'll also need to install [campaignmonitor/createsend-php](https://github.com/campaignmonitor/createsend-php) by dropping its contents into `vendor/campaignmonitor/createsend-php`.

## Subscribing to a list

Add subscriptions to a list using either a POST route, the OneCampaignMonitor service, or variable.

Adding subscriptions requires a List ID and an email, but there are a few other fields you can send:

* List ID (required)
* email address (required)
* name
* array of custom fields (eg `{age: 22}`)
* boolean for whether to resubscribe addresses that have previously been removed (default true)

Note about the **list ID**: this is _not_ the parameter "listID" in the URL when viewing a list. Annoyingly, Campaign Monitor uses two IDs for lists. To find your List ID, view [this FAQ](https://createform.com/support/campaignmonitor-list).

### POSTing a form

The easiest way to add subscribers is by posting a form with their data. This follows the standard Craft form template, with some required and some optional fields.

```
<form method="POST" action="">
  <!-- craft form fields -->
  {{ getCsrfInput() }}
  <input type="hidden" name="action" value="oneCampaignMonitor/subscribers/add" />
  <input type="hidden" name="redirect" value="/" />

  <!-- required fields ** REPLACE "myListId" WITH YOUR LIST ID ** -->
  <input type="hidden" name="listId" value="myListId" />
  <label for="email">Email (required)</label>
  <input type="text" id="email" name="email" />

  <!-- optional fields -->
  <input type="hidden" name="resubscribe" value="0" /><!-- don't resubscribe if email has already opted out -->

  <label for="name">Name</label>
  <input type="text" id="name" name="name" />

  <label for="customFields[myCustomField]">My Custom Field</label>
  <input type="text" id="customFields[myCustomField]" name="customFields[myCustomField]" />

  <label for="customFields[myOtherCustomField]">My Other Custom Field</label>
  <input type="text" id="customFields[myOtherCustomField]" name="customFields[myOtherCustomField]" />

  <input type="submit" value="Subscribe!" />
</form>
```

### Responses

If adding a subscriber fails, the call to either method below will return false, and will log the error (available in `/admin/utils/logs`).

If successful, the methods will return true and not log.

### Variable (in templates)

The simplest implementation for use in templates is:

```
{% craft.oneCampaignMonitor.subscribe('myListId', 'email@email.com') %}
```

Add additional parameters as needed:

```
{% set listId = 'blabla' %}
{% set email = 'some@email.com' %}
{% set name = 'Steve' %}
{% set customFields = {age: 22, city: 'Chicago'} %}
{% set resubscribe = false %}
{% craft.oneCampaignMonitor.subscribe(listId, email, name, customFields, resubscribe) %}
```

### Service (in controllers, etc)

The simplest implementation for adding subscriptions using the OneCampaignMonitor Subscribers Service is:

```
$list_id = '123';
$email = 'email@email.com';
craft()->oneCampaignMonitor_subscribers->add($list_id, $email);
```

Or, add some params:

```
$list_id = '123';
$email = 'email@email.com';
$name = 'Steve';
$customFields = ['city': 'Chicago'];
$resubscribe = false;
craft()->oneCampaignMonitor_subscribers->add($list_id, $email, $name, $customFields, $resubscribe);
```

Update a subscriber in a list:

```
$list_id = '123';
$email = 'email@email.com';
$name = 'Steve';
$customFields = ['city': 'New York'];
$resubscribe = true;
craft()->oneCampaignMonitor_subscribers->update($list_id, $email, $name, $customFields, $resubscribe);
```

Determine if a subscriber exists in a list:

```
$list_id = '123';
$email = 'email@email.com';
if (craft()->oneCampaignMonitor_subscribers->exists($list_id, $email)) {
  // subscriber exists
}
```

### Checking if the user has subscribed to a list

You can check in a template if the current user (by session) has already subscribed to a list:

```
{% if craft.oneCampaignMonitor_log.hasSubscribed(listId) %}
```

Or elsewhere:

```
if (craft()->oneCampaignMonitor_log->hasSubscribed($listId)) { ... }
```

**Important** This log is only saved per session. It does NOT check with Campaign Monitor.

## A note on blocking calls

It looks like the library this depends on, `createsend-php`, executes these CURL requests using sockets when available. (See `vendor/campaignmonitor/createsend-php/class/transport.php`.) If that's the case, then all CURL requests are performed asyncronously. Consider this a TODO to investigate and update responses.

# One Campaign Monitor

Craft CMS Plugin for Campaign Monitor Integration

## Subscribing to a list

Add subscriptions to a list using either the OneCampaignMonitor service or variable.

Adding subscriptions requires a List ID and an email, but there are a few other fields you can send:

* List ID (required)
* email address (required)
* name
* array of custom fields (eg `{age: 22}`)
* boolean for whether to resubscribe addresses that have previously been removed (default true)

### Resposes

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

## A note on blocking calls

As of right now, all calls to CampaignMonitor are _blocking_, aka _synchronous_. This means that Craft will not render any template or continue executing code until the call is complete.

For best practice, make these calls asynchronously using AJAX, or #TODO add a non-blocking method.

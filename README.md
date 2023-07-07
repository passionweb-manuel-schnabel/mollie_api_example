# Use Mollie API for payments

Shows the integration of the Mollie API for payments. (TYPO3 CMS)

## What does it do?

Adds two plugins to show the process for handling payments with the Mollie API.

## Installation

Add via composer:

    composer require "passionweb/mollie-api"

* Install the extension via composer
* Flush TYPO3 and PHP Cache

## Requirements

This example uses the Mollie API and the corresponding composer package mollie/mollie-api-php.

## Simple RouteEnhancer

Add the following snippet to the `routeEnhancers` section within your `config.yaml`:

    MollieApiPaymentReturn:
      type: Simple
      routePath: '/{order_id}'
      requirements:
        order_id: '[a-zA-Z0-9].*'
      _arguments: {}

## Frontend configuration "enforceValidation"

If this setting is active you need to add the `order_id` parameter to the `excludedParameters`
if you want to use exactly the code snippets from this example repository.

    'cacheHash' => [
        'enforceValidation' => true,
        'excludedParameters' => [
            'order_id',
        ],
    ],

Otherwise, you can build an Extbase RouteEnhancer, generate the uri for the Mollie API request and add the parameter to the
`paymentreturnAction` within the `MollieController`.

## Extension settings

There are the following extension settings available.

### `mollieApiKey`

    # cat=mollie; type=string; label=Mollie API key
    mollieApiKey = YOUR_API_KEY

Enter your Mollie API key.

### `successPid`

    # cat=mollie; type=string; label=PID of payment success page
    successPid =

Enter the page id of your payment success page.

## Troubleshooting and logging

If something does not work as expected take a look at the log file.
Every problem is logged to the TYPO3 log (normally found in `var/log/typo3_*.log`)

## Achieving more together or Feedback, Feedback, Feedback

I'm grateful for any feedback! Be it suggestions for improvement, requests or just a (constructive) feedback on how good or crappy this snippet/repo is.

Feel free to send me your feedback to [service@passionweb.de](mailto:service@passionweb.de "Send Feedback") or [contact me on Slack](https://typo3.slack.com/team/U02FG49J4TG "Contact me on Slack")

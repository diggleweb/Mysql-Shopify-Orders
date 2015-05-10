Mysql Shopify Orders
=============

This script will add all Orders completed in shopify to the mysql DB of your choosing. 

My goal for this is to copy orders made om shopify and mirror them to a local mysql DB. To then integrate it with UPS Worldship.

Please see our [contributing guidelines](CONTRIBUTING.md) before reporting an issue.

Shopify Webhook
-------

The script below shows you how to get the XML data in from Shopify into your script, archive the file, and send the proper headers ...

Given that the new order subscription setup in the admin for the webhook is: http://example.com/mysql-shopify.php?key=123456789


Installation
-----------

```
Upload mysql-shopify.php to your website and create a webhook for order completion (XML format) and direct it to the url http://example.com/mysql-shopify.php?key=123456789
```


Contributing
------------

See [Contributing](CONTRIBUTING.md)
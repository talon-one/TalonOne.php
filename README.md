# ⚠️ THIS SDK IS NOT BEING MAINTAINED. PLEASE REFER TO https://github.com/talon-one/TalonOnePHPsdk

# PHP Examples for Talon.One Integration API and Mangement API

Currently, these demos only require the cURL PHP module.

There are two demos:

1. ```management_create_coupons.php``` demonstrates how you can dynamically create coupons (to use in a mailing, for example) via the Mangement API.
2. ```integration_apply_coupons.php``` shows how you can check and invalidate customer provided coupons in your integration, and how to send profile and session attributes.

To test the integration, you need to create an application and a campaign in the Talon.One Campaign Manager and find out their ID and the application's secret key.
Paste these values and your subdomain name in the right places in ```management_create_coupons.php``` and ```integration_apply_coupons.php```.

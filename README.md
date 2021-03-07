# clean7

clean7




# SMS Requirement
- Migration

* ENVIRONMENT VARIABLES 
   - APP_SMS_ENABLED=false

### Notes
-  Set APP_SMS_ENABLED to true to send sms4
-  Create Data in setting Table
     - sm_api_url
     - sms_api_key
     - sms_sender_id
-  Set Laravel scheduler in server cron job to execute send:sms command every minute
-  Install Sms Libray to convert in E164 Format
   - composer require giggsey/libphonenumber-for-php
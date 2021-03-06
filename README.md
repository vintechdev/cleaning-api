# clean7

clean7




# SMS Requirement
- Migration

* ENVIRONMENT VARIABLES 
   - APP_SMS_ENABLED=false
   - APP_SMS_URL=
   - APP_SMS_SENDER_ID=
   - APP_SMS_API_KEY=

### Notes
-  Set APP_SMS_ENABLED to true to send sms
-  Set Laravel scheduler in server cron job to execute send:sms command every minute
-  Install Sms Libray to convert in E164 Format
   - composer require giggsey/libphonenumber-for-php
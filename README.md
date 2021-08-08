# API Backend

This application serves as the backend for managing users and jobs.

# Deployment steps
## Database
Snap shot of the MySql DB can be used to create the baseline that should contain one admin user and some example services provided.

## Code deployment
There are 4 code bases including this repo as follows:

- https://bitbucket.org/cleaningmarketplace/api-backend/src/master/
- https://bitbucket.org/cleaningmarketplace/user-portal/src/master/
- https://bitbucket.org/cleaningmarketplace/provider-portal/src/master/
- https://bitbucket.org/cleaningmarketplace/admin-portal/src/master/

Once the DB has been built, api backend is the first one that needs to be deployed to the environment of choice that runs PHP >= 7, followed by the portals.

### Steps to deploy
- Clone the repositories to the server.
- Check out master (this will be checked out by default when cloned)
- Create .env file for each repo and fill the required info referring to the .env.example in the corresponding repo. Make sure the DB details are added to .env for api-backend. **Make sure PROVIDER_PORTAL_URL in user portal and USER_PORTAL_URL in provider portal are updated with the corresponding urls as they are used for the functionality where a user or provider signs up to each others portals.**
- Make sure the APP_NAME is all portals are unique as this is used to create the session names. If not unique, there are chances of session name conflict as all portals are likely to have the same domain.
- Once the .env files are updated, run the following commands in each repos:
      - composer install --no-dev
      - php artisan key:generate
      - php artisan cache:clear
      - php artisan config:clear
   
   **The above commands except that to generate key have to be run after every releases too**.

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
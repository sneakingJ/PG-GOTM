# Windows Local Installation

## Installation
- Download PHP 8.1.x (exact version)
    - Extract the php folder to the root of your C: drive
    - Add ``C:\php`` to your PATH
    - Go to your php folder and find the ``php.ini-development`` file
        - Create a copy of this file and rename it to ``php.ini``
    - Open ``php.ini`` in a text editor and uncomment these lines:
        - ``extension_dir = "ext"``
            - After uncommenting this line, you can move your php folder to a different location if you wish
            - Remember to update your PATH if you move your folder
        - ``extension=curl``
        - ``extension=fileinfo``
        - ``extension=openssl``
        - ``extension=pdo_mysql``
        - ``curl.cainfo =``
            - Download the PEM file from [curl.se](https://curl.se/docs/caextract.html) and put it somewhere
            - Change the curl.cainfo to the PEM file location
                - Example: ``curl.cainfo = "C:\php\cacert.pem"``
                - Change the location if your php installation is not ``C:\php``
- Download MySQL
    - Create a user with full access rights
    - Note down the username and password for this user to use in the ``.env`` file later
- Download [Composer](https://getcomposer.org/)
    - Open a command-line interface in your local repository and run ``composer install``

## ``.env`` Setup
- Start by going to the local repository of this project
- Make a copy of ``.env.example`` and rename it to ``.env``
### DB Section
- Enter the MySQL full-access user's username after ``DB_USERNAME=``
- Enter the MySQL full-access user's password after ``DB_PASSWORD=``
### DISCORD Section
- Go to the [Discord Developer Portal's applications page](https://discord.com/developers/applications)
    - Create a new application
    - Go to the OAuth2 section of your application's settings
        - Note down the ``CLIENT ID`` and the ``CLIENT SECRET``
        - Enter ``http://localhost:8000/discord-auth/callback`` in the "Redirects" section
        - Save Changes
- Back in the .env file:
    - Enter the noted ``CLIENT ID`` after ``DISCORD_CLIENT_ID=``
    - Enter the noted ``CLIENT SECRET`` after ``DISCORD_CLIENT_SECRET=``
    - Enter ``http://localhost:8000/discord-auth/callback`` after ``DISCORD_REDIRECT_URI=``
### TWITCH Section
- IGDB is owned by Twitch and is used to look up the games for nominations
- Go to the [Twitch Developers](https://dev.twitch.tv/console/apps/create) page and create an application
    - Enter `https://localhost` for the ``OAuth Redirect URLs``
    - After creating the application, note down the ``Client ID`` and the ``Client Secret``
- Back in the .env file:
    - Enter the noted ``Client ID`` after ``TWITCH_CLIENT_ID=``
    - Enter the noted ``Client Secret`` after ``TWITCH_CLIENT_SECRET=``
### ADMIN Section
- Go to Discord and make sure "Developer Mode" is enabled (Settings > Advanced  > Developer Mode)
    - On PC:
        - Right-click your profile picture in chat
        - Click on ``Copy User ID``
    - On Mobile:
        - Tap on your profile picture in chat
        - Tap on the ``...`` button in the top-right
        - Tap on ``Copy User ID``
- Back in the .env file:
    - Replace the default ``yyy,zzz`` after ``ADMIN_DISCORD_IDS=`` with your copied ``User ID``
### Run the following commands in a command-line interface in your local repository
- Create the ``pg_gotm`` database in your MySQL server
  - ``php artisan migrate:fresh``
- Import game platforms into the ``pg_gotm`` database using the IGDB API
    - ``php artisan gotm:importplatforms``
- Populate the various database tables with test data
    - ``php artisan db:seed``
- Used by the Illuminate encrypter service to generate an ``APP_KEY`` in your .env file
    - ``php artisan key:generate``
- Run ``php artisan serve`` to start your server

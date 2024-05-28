## About
This is the Game of the Month (GOTM) web application for nomination, voting and jury duties. It is used in the Patient Gaming subreddit's Discord.

## Privacy
This website gets the following information from your Discord account when you authenticate:
- Account ID
- Account nickname
- Account avatar

**All of these are publicly visible to anyone in every server you joined.**\
The only data that is used and saved on this site, is the account id and ONLY if you nominate or vote for a game. The sole purpose of this is to prevent multiple nominations and votings by the same user in one month.\
No other data is made use of in any way.

This whole project is open source and the code can be inspected by everyone on Github.

## Tech Stack
- PHP 8.1
- Mysql 8.0
- Laravel 8
- Livewire
- Bulma CSS

## Setup
- artisan migrate (:fresh)
- artisan gotm:importplatforms
- artisan db:seed

## Docker Container Installation
- See [docker-compose.yml](docker-compose.yml) to set up your environment with [Laravel Sail](https://laravel.com/docs/8.x/sail)

## Windows Local Installation
- See [LOCAL_INSTALLATION.md](LOCAL_INSTALLATION.md) for details on setting up your environment locally in Windows

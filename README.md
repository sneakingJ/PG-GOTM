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
- Laravel
- Livewire
- Bulma CSS

## Setup
- artisan migrate (:fresh)
- artisan gotm:importplatforms
- artisan db:seed

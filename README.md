App Guestbook
=

Installation

1. Make sure you have docker installed. Copy `.dist.env` to `.env`
2. Run `composer install`
3. Run the following in project dir: `docker-compose build`
4. Run `docker-compose up`
5. Populate DB data with fixtures `php bin/console doctrine:fixtures:load`
6. Proceed to `localhost:8080`
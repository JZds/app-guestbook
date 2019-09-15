App Guestbook
=

Installation

1. Make sure you have docker and npm installed. Copy `.dist.env` to `.env`
2. Run `composer install`
3. Run `npm install`
4. Run `npm run build`
5. Run the following in project dir: `docker-compose build`
6. Run `docker-compose up`
7. Proceed to `localhost:8080`

- To populate DB data with fixtures `php bin/console doctrine:fixtures:load`
- To make user an admin by command: `bin/console user:make-admin {mail}`
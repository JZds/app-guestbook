App Guestbook
=

Installation

1. Make sure you have docker installed. Copy `.dist.env` to `.env`
2. Run the following in project dir:
    ```
    docker-compose build
    ```
3. Run `docker-compose up -d`
4. Load data fixtures `php bin/console doctrine:fixtures:load`
5. Proceed to `localhost:8080`
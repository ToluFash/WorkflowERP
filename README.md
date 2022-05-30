# Workflow

ERP application to manage workflow in construction sites.

# How to install dev
1. Clone repo
2. Create `.env.local` with your env vars
# How to run
1. `docker-compose build`
2. `docker-compose up -d`
3. `docker-compose run --rm php bash "-c" "cd /var/www/html && composer install"`
# Run migrations
1. `docker-compose run --rm php bash "-c" "cd /var/www/html && ./bin/console d:m:di"`
2. `docker-compose run --rm php bash "-c" "cd /var/www/html && ./bin/console d:m:m"`
# Setup Node Project
4. `docker-compose run --rm php bash "-c" "cd /var/www/html && yarn install"`
5. `docker-compose run --rm php bash "-c" "cd /var/www/html && yarn watch"`
# Clear cache manually
1. `docker-compose run --rm php bash "-c" "cd /var/www/html && ./bin/console cache:pool:clear cache.global_clearer" && docker-compose run --rm php bash "-c" "cd /var/www/html && composer clearcache"`
# If company database is not created automatically
1. `docker-compose run --rm php bash "-c" "cd /var/www/html && ./bin/console d:d:c"`

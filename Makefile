fresh: init load-fixtures
init:
	composer install
	chmod 777 assets
	yes | php bin/console doctrine:migrations:migrate
load-fixtures:
	yes "yes" | php bin/console doctrine:fixtures:load

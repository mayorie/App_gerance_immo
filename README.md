php bin/console make:migration
php bin/console doctrine:migrations:migrate

lancement du serveur sur le port 8000 :
php -S localhost:8000 -t public

notes base de donnée : 

j'ai remplacé tout les accents par leur version sans accents




php bin/console doctrine:fixtures:load --no-interaction
php bin/console doctrine:fixtures:load --append 

php bin/console doctrine:fixtures:load --group=test     
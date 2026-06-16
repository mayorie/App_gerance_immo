php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate

------------------ lancement du serveur sur le port 8000 :
php -S localhost:8000 -t public


------------------ notes base de donnée : 
j'ai remplacé tout les accents par leur version sans accents


------------------ Insertion de fixtures
php bin/console doctrine:fixtures:load --no-interaction
php bin/console doctrine:fixtures:load --append 

php bin/console doctrine:fixtures:load --group=test     


------------------ reset Base de bonnées : 
supprimer : data.db du dossier var
puis faire cette commande : 
php bin/console doctrine:migrations:migrate


------------------ mettez votre signature dans private,
le nom du fichier doit être : Signature.jpg


------------------ php.ini :
faire php --ini et trouver : Loaded Configuration File: et prenez le chemin pour le mettre dans un explorateur de fichier
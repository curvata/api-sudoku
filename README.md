# api-sudoku

Sudoku est une API de générateur et validateur de Sudoku développé avec Symfony 5. 

Procédure d'installation du projet:

1. Récupérer le dépôt git ```git clone https://github.com/curvata/api-sudoku.git```
2. Installation des dépendances php ```composer update```

Procédure pour lancer les tests

1. Renseigner le DATABASE_URL dans le fichier .env.test
2. Lancer les tests ```php bin/phpunit```
3. Lancer le code coverage ```XDEBUG_MODE=coverage bin/phpunit --coverage-html coverage``

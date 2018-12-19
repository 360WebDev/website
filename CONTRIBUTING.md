# Conventions de commits

<type>(sujet)

* Type définit le type de commit
    * build: Système de build (example : gulp, webpack, npm)
    * ci: Intégration continue (example scopes: Travis, Circle, BrowserStack, SauceLabs)
    * docs: Documentation
    * feat: Ajout d'une fonctionnalité
    * fix: Correction de bogue
    * perf: Amélioration des performances
    * refactor: Changement du code qui ne change rien au fonctionnement
    * style: Changement du style du code (sans changer la logique)
    * test: Modification des tests

* Sujet : Description de la fonctionnalité.

exemple: `feat(add space member)`    

# Conventions de codage :

Le projet respecte les conventions de codage du PSR-2 : (http://www.php-fig.org/psr/psr-2/).
Vous pouvez formater votre code à l'aide de PHP Code Sniffer, avant de commiter, lancer les commandes suivantes :

```bash
$ ./vendor/bin/phpcs && ./vendor/bin/phpcbf 
```

Il va formater votre code afin de respecter les normes du PSR-2.

*Notre si vous utilisez PHPStorm : https://confluence.jetbrains.com/display/PhpStorm/PHP+Code+Sniffer+in+PhpStorm*

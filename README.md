[![SymfonyInsight](https://insight.symfony.com/projects/85f2c9ca-e2d6-4f6f-9220-2d19f85aa89b/big.svg)](https://insight.symfony.com/projects/85f2c9ca-e2d6-4f6f-9220-2d19f85aa89b)

# PROJET 8
## Formation Développeur d'application - PHP/Symfony - OpenClassrooms

### Installation d'un environnement de développement

#### Prérequis
 1. [PHP 8.2](https://www.php.net/downloads) ou supérieur et ses extensions :
	 1. [Ctype](https://www.php.net/book.ctype)
	 2. [iconv](https://www.php.net/book.iconv)
	 3. [PCRE](https://www.php.net/book.pcre)
	 4. [Session](https://www.php.net/book.session)
	 5. [SimpleXML](https://www.php.net/book.simplexml)
	 6. [Tokenizer](https://www.php.net/book.tokenizer)
	 7. [Mbstring](https://www.php.net/book.mbstring)
	 8. [Intl](https://www.php.net/book.intl)
	 9. [PDO + PDO Mysql](https://www.php.net/book.pdo)
	 10. [OPcache](https://www.php.net/book.opcache)
	 11. [cURL](https://www.php.net/book.curl)
 2. [Composer](https://getcomposer.org/doc/00-intro.md)
 3. Git
 4. [Docker + Docker Compose](https://www.docker.com/)

### Démarrage de l'environnement de développement
```bash
composer install
docker-compose up -d
symfony console d:m:m --no-interaction
symfony console d:f:l --no-interaction
symfony serve -d
```

### Implémentation de l'authentification

L'authentification utilise les authentificators de symfony via leurs "Badges".

La configuration est la suivante :

- Les utilisateurs sont stockés en base de données
- Le mot de passe est haché.
- ``username`` est utilisé comme propriété unique pour l'authentification.

Toute la configuration se trouve dans le fichier ``config/packages/security.yaml``

Il n'y a qu'un seul authenticator implémenté : ``App\Security\LoginFormAuthenticator``.

L'authenticator est branché sur ``Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator`` et écoute les requêtes ``POST`` qui proviennent de ``/login``.

Si le mot de passe est correct suivant l'username, alors il délivre un badge valide et redirige vers la page d'accueil. Sinon, il génère l'erreur dans le formulaire et renvois vers la page de login.

@see https://symfony.com/doc/current/security.html

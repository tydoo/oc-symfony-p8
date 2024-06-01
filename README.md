[![Codacy Badge](https://api.codacy.com/project/badge/Grade/ac43a375ec1d44428bfedb145ea5b031)](https://app.codacy.com/gh/tydoo/oc-symfony-p8?utm_source=github.com&utm_medium=referral&utm_content=tydoo/oc-symfony-p8&utm_campaign=Badge_Grade)
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

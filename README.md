apiArticles
=======
#Load data fixtures for env dev and test
Create data fixtures
```
 composer load-fixture
```
#For PSR-1 and PSR-2
Run PHPCS-Fixer
```
 bin/php-cs-fixer fix --verbose
```
 
#Run tests
```
 composer behat
```

#Run PHPCS-Fixer
```
 vendor/bin/php-cs-fixer fix --verbose
```


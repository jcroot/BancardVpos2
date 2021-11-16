# BancardVpos2

## Requirements

- Doctrine ORM
- bancard-checkout.js https://github.com/Bancard/bancard-checkout-js
- SDK Bancard 2.0 for PHP (another flavors Woocommerce, Prestashop you can contact me)

## Install localhost
```
git clone git@github.com:jcroot/BancardVpos2.git
cd BancardVpos2/
composer install
php vendor/bin/doctrine orm:schema-tool:create 
update your bootstrap.php (database configuration)
cd public_html
php -S localhost:8000
``` 

## Docker
````
git clone git@github.com:jcroot/BancardVpos2.git
cd BancardVpos2/
docker compose up -d
````

## Author
- Jean Claude Adams https://github.com/jcroot

## Thanks
- Ignacio Rojas (imroca) https://github.com/imroca/Bancard (idea)

## License

This project is licensed under the terms of the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details
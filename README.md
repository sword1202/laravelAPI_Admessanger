# coreui-laravel
CoreUI-laravel is simple adaptation to Laravel the most beautiful free Bootstrap 4 admin template created by Lukasz Holeczek

![alt text](https://raw.githubusercontent.com/taboritis/coreui-laravel/master/sample_charts.png)

### Instalation in 5 steps
```bash
git clone https://github.com/taboritis/coreui-laravel.git
cd coreui-laravel
composer install
cp .env.example .env
php artisan key:generate
```

- You have to register and login to app (database needed)
- If you are user MySQL you can paste this to your .env file:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coreui
DB_USERNAME=root
DB_PASSWORD=
```

- To create table in database
```bash
	php artisan migrate
```
(or to create table with exemplary user 'John Doe')
```bash
	php artisan migrate:fresh --seed
``` 



##### That's all. Enjoy.

### Change log
##### v 1.0.6a
##### v 1.0.6
- Update to CoreUI 1.0.6
- In gulpfile.js prepared scripts to import libraries and app files to /public directory

## Screenshots

![alt text](https://raw.githubusercontent.com/taboritis/coreui-laravel/master/sample_charts.png)
![alt text](https://raw.githubusercontent.com/taboritis/coreui-laravel/master/sample_cards.png)
![alt text](https://raw.githubusercontent.com/taboritis/coreui-laravel/master/sample_charts.png)
![alt text](https://raw.githubusercontent.com/taboritis/coreui-laravel/master/sample_switches.png)
![alt text](https://raw.githubusercontent.com/taboritis/coreui-laravel/master/sample_tabs.png)
![alt text](https://raw.githubusercontent.com/taboritis/coreui-laravel/master/sample_widgets.png)

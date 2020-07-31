# DUCS Office Automation

This project aims at automating the office work in the Department of Computer Science, University of Delhi (aka DUCS).

## Local Development
This is project is built with Laravel & Vue.js. The Laravel framework has a few system requirements. All of these requirements are satisfied by the [Laravel Homestead](https://laravel.com/docs/6.x/homestead) virtual machine.

However, if you are not using Homestead, you will need to make sure your local development server meets the following requirements:

### Installing Prerequisites

You can find the server prequisites listed in [laravel docs](https://laravel.com/docs/6.x#server-requirements), Additionally, you would require to install [composer](https://getcomposer.org/) & [nodejs](https://nodejs.org/en/) to pull in all the project dependencies.

##### Using `apt` package manager (Debian/Ubuntu)
Before you begin installing make sure you run `sudo apt update` to get the latest version available.

```bash

# if you do not have mysql installed on your system
sudo apt install mysql-server

# php & required extensions
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update
sudo apt-get install php7.3 php7.3-mysql php7.3-xml php7.3-mbstring php7.3-bcmath php7.3-sqlite php7.3-json

# composer & nodejs
sudo apt install nodejs composer
```

### Clone Project
You can simply clone the project using `git` as:

```bash
git clone https://github.com/ducs-office/ducs-office-automation
```

### Install project dependencies

go to your project directory and install php dependencies using `composer`:

```bash
composer install
```

`npm` to install all the required JavaScript dependencies.

```bash
npm install
```

To compile down the frontend assets like stylesheets (CSS) & javascript files using,

```
npm run dev
```

Or you can also run a watcher to automatically compile the assets, whenever the files are changed.

```
npm run watch
```

### Application Configuration

Create a duplicate file of `.env.example` as `.env`.

```bash
cp .env.example .env
```

To generate an application key use: 
`php artisan key:generate` this will add an application to your `.env` file.

Create new `mysql` user and database
```bash
mysql -u root -h localhost -p
mysql> CREATE USER 'username'@'localhost' IDENTIFIED BY 'password';
mysql> GRANT ALL PRIVILEGES ON office_automation.* TO 'username'@'localhost' WITH GRANT OPTION;
mysql> ALTER USER 'username'@'localhost' IDENTIFIED WITH mysql_native_password by 'password';
mysql> \q
mysql -u username -h localhost -p
mysql> CREATE DATABASE office_automation;
```

Setup `Database` connection in `.env` file:

```env
...
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=office_automation
DB_USERNAME=username
DB_PASSWORD=password
...
```
Make sure you change database configuration according to your credentials. Mostly, you'd need to change values for these variables:

- `DB_DATABASE` - This is the name of database, you must change this and make sure the database name you provide exists, or would get an error.
- `DB_USERNAME` - This is your mysql user.
- `DB_PASSWORD` - This is your mysql password for that user.

That's pretty much it. You're done with the configuration.

### Add some default data for testing the app
To create all the tables & seed your database with dummy data, run:

```
php artisan migrate --seed
```

### Start Local Development Server

To begin browsing & testing the portal you'd need to start a local development server.

```bash
php artisan serve
```

This will serve your website at `localhost:8000`, you can now open this up in your browser.

### Default login credentials

```
Email: admin@cs.du.ac.in
Password: password
```

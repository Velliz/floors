<img align="left" src="https://github.com/Velliz/floors/blob/master/assets/image/floors-icon-50.png">

# Floors [ON GOING]

> Floors is on going to beta version on June 2017.

Login is the most implemented and boring feature because its repeated in every single web app i ever build.
Floors is PHP based **login as-a service** platform for **single sign on** distributed web apps that solving boring problem i faced.
Built with credentials integrations support with facebook, google and twitter out of the box with only configuration without coding.
Let's make login feature fun again with floors!

### Installations

Wanna try? just hit:
```
composer create-project -s dev velliz/floors project_name
```

### Setup

- Database setup:

Import provided **floors.sql** into your MySQL or MariaDB engine. 
Then setup the database connection from **config/database.php**

```php
return array(
    'dbType' => 'mysql',
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'dbName' => 'floors',
    'port' => 3306
);
```

- First use setup:

To add a default operator access and apps you can open the **http://localhost/setup**

### Main features

```
* thrid party app management
* authorization
* users log
* roles and credentials
```

### Login features

```
* Facebook
* Google Accounts
* Twitter
* Floors Account
```

### About

Floors is build on top [Puko Framework](https://github.com/Velliz/pukoframework)
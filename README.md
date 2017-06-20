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

- Installation

Download code via composer:
```
composer create-project -s dev velliz/floors project_name
```

- Database setup:

Import provided floors.sql into your MySQL or MariaDB engine. 
Then setup the database connection from config/database.php

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

- Administrator account:

To add a default administrator access you need to hit this query:
```sql
insert into operator(created, fullname, username, password, roles) values (now(), 'full name', 'username', md5('password'), 'admin');
```

for login with credentials
user: roles\username
password: password

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
# G6GLP CMS Installation Guide

## Requirements

- Linux server
- Apache web server
- PHP
- MySQL or MariaDB database server

## Installation

Clone the repository:

git clone git@github.com:tony-g6glp/g6glp-website.git


Place the files in the Apache web directory.

Example:
/var/www/html/g6glp


## Database Setup

Create the database.

Import the structure:
mysql -u username -p database < database/G6GLP_STRUCTURE.sql


## Configuration

Create:
include/config.php


Example:

```php
<?php

define('DB_HOST','server_address');
define('DB_NAME','database_name');
define('DB_USER','database_user');
define('DB_PASS','database_password');

?>
Do not upload this file to GitHub.

First Administrator

Create the first administrator account using the database.

Passwords must be stored using PHP password hashing:
password_hash()

Apache Configuration

Ensure PHP files are executed and the site directory has suitable permissions.

Backup

Recommended backups:

Git repository for source code
SQL database dump for data
Separate backup of configuration files
Updating

After changes:
git pull

or after local development:

git add .
git commit -m "Description of changes"
git push

Save and exit.

---

Then check:

```bash
git status

You should see:
Untracked files:
    CHANGELOG.md
    INSTALL.md

Then:
git add CHANGELOG.md INSTALL.md
git commit -m "Add installation and changelog documentation"
git push

# G6GLP Website and CMS

Personal website and content management system for Tony Rider - G6GLP.

This project provides a PHP/MySQL based website with a custom CMS for managing blog content, categories, tags and administration.

## Features

### Public Website

- Home page
- Software project pages
- Blog system
- Blog categories
- Blog tags
- Static content pages

### CMS Administration

- Secure administrator login
- Database user authentication
- Blog post management
- Category management
- Tag management
- Publish/unpublish controls
- Image support

## Technology

- PHP
- MySQL / MariaDB
- Apache
- HTML5
- CSS
- Linux

## Project Structure
admin/ Administration area
blog/ Public blog pages
include/ Shared PHP libraries and configuration
database/ Database structure files
software/ Software project pages
uploads/ Uploaded content


## Database

The database structure is provided:
database/G6GLP_STRUCTURE.sql

The database connection settings are stored separately in:
include/config.php

This file is not included in GitHub as it contains local database credentials.

Example:

```php
define('DB_HOST','localhost');
define('DB_NAME','database');
define('DB_USER','username');
define('DB_PASS','password');

Installation
1,Clone the repository.
2,Create a MySQL/MariaDB database.
3,Import:
database/G6GLP_STRUCTURE.sql

4,Create:
include/config.php
with the correct database settings.

5,Configure Apache/PHP.
6,Create the first administrator account.

Version

Current version:
1.0

Backup

The project uses:

GitHub for source code backup
SQL structure backup for database design

Database content should be backed up separately.

Author

Tony Rider
Amateur Radio Callsign: G6GLP

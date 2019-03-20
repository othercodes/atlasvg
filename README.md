# AtlasVG

[![Build Status](https://travis-ci.com/othercodes/atlasvg.svg?branch=master)](https://travis-ci.com/othercodes/atlasvg) [![codecov](https://codecov.io/gh/othercodes/atlasvg/branch/master/graph/badge.svg)](https://codecov.io/gh/othercodes/atlasvg)


## Requirements

* php >= 7.2
* php-zip
* php-xml
* php-mbstring
* php-sqlite3

## Installation

First clone the repo:

```bash
git clone git@github.com:othercodes/atlasvg.git
```

Now run composer to install all dependencies:

```bash
composer install
```

Once composer finish check the presence of `.env` and `database/database.sqlite` files.

Run the following files if anyone is missing: 

```bash
composer run-script post-install-cmd
```

Now is time to deploy the database: 

```bash
php artisan migrate
```

To install the demo data run: 

```bash
php artisan db:seed
```

To install database schema and install demo data in a single command use: 

```bash
php artisan migrate:refresh --seed
```

To configure integration with Azure:
* create an application in https://apps.dev.microsoft.com/ specifying redirect url as "http://localhost:8000/app/callback"
* use .env.example for project configuration as example, putting OAUTH_APP_ID and OAUTH_APP_PASSWORD for the application you have created
* specify OFFICE_LOCATION for scope limiting
* after installation navigate to /signin to authenticate in Azure
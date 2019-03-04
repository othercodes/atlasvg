# AtlasVG

## Installation

First clone the repo:

```bash
git clone git@github.com:othercodes/atlasvg.git
```

Now run composer to install all dependencies:

```bash
compser install
```

Once composer finish check the presence of `.env` and `database/satabase.sqlite` files.

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
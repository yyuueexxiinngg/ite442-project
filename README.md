# ITE442 DB Project

A database normalization project for ITE442 in STIU. 

Contains PHP backend for fetching and changing data. 
Vue based frontend interface.  

## Frontend Build Setup

``` bash
cd Frontend

# install dependencies
npm install

# development serve
npm run dev

# build for production
npm run build
```

## Backend setup

``` bash
cd Backend

# install dependencies
composer install
```

Create config.php inside Backend folder using code below:

```php
<?php
// Host address of the DB. By default is 127.0.0.1 / localhost
define('DB_HOST','');

// Username for DB
define('DB_USER','');

// Password
define('DB_PASSWORD','');

// Here is DB name (Schema name)
define('DB_NAME','');
```

## TODO *Needs to update after decide all pages*
### Backend
- [x] Login
- [ ] View
    - [x] Receipt
    - [ ] Repair Status
    - [x] In Progress
    - [x] Repair Order
    - [ ] Custom
- [x] Insert
- [x] Update

### Frontend
#### Customer
- [x] Receipt
- [ ] Repair Status

#### Employee
- [x] Login
- [x] Receipt
- [ ] Repair Status
- [x] In Progress
- [x] Repair Order
- [x] Update Repair Form
- [x] Create Repair Form
- [ ] Custom

#### Manager
- [ ] Manager Page

### License
[PrintJS](https://github.com/crabbly/Print.js/blob/master/LICENSE)


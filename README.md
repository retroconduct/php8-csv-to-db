# php8-csv-to-db

## PHP

php8.1

## Extensions

php8.1-mysql (will require mysqli, pdo_mysql)

## Commands

### Help
```
php user_upload.php --help
```

### Run
```
php user_upload.php -u user -p password -h 127.0.0.1 --file users.csv
```

### Dry Run
```
php user_upload.php -u user -p password -h 127.0.0.1 --file users.csv --dry_run
```

### Create Table (only runs this process is option is provided)
```
php user_upload.php -u user -p password -h 127.0.0.1 --create_table
```

### Delete Table (only runs this process is option is provided)
```
php user_upload.php -u user -p password -h 127.0.0.1 --delete_table
```

## Instructions

```
cd into folder
run docker-compose up --build
run php user_upload.php --help
```
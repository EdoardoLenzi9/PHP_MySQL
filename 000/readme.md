# setup

```sh
sudo apt update
sudo apt install php php-cli php-mysql php-xdebug
php -m | grep pdo
```

# run

```sh
php -S localhost:8000   # web mode
php -S localhost:8000 -t src/
php script.php          # script mode, terminal execution, no web server
```

# call flow

```
Browser → http://localhost:8000/index.php
        ↓
PHP executes code
        ↓
Outputs HTML
        ↓
Browser renders page
```

# SQL
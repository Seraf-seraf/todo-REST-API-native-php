## Установка
```bash
git clone https://github.com/Seraf-seraf/todo-REST-API-native-php.git
cd todo-REST-API-native-php
```

## Настройте подключение к бд в файле config.php
```php
<?php
$dbConnection = 'mysql';
$dbHost = 'localhost';
$dbName = 'todo-rest';
$dbUser = 'root';
$dbPass = '';
$dsn = "$dbConnection:host=$dbHost;dbname=$dbName;charset=utf8";
```

## Выполните команду, которая создаст таблицу в бд
```php
./cli migrate
```

### Документация находится в папке docs

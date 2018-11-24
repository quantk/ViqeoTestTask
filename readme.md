# Тестовое задание от Viqeo на позицию Middle Laravel-разработчик
Необходимо сделать restful-backend на laravel, который уменьшает картинку и показывает ее уменьшенный url, асинхронно, через очередь. ответы ожидаются в json. нужно реализовать два запроса:

1) POST /image, на вход должна прийти картинка png/jpg (в виде файла из form-data), и размеры (width/height). если width или height null, то нужно уменьшать картинку в соответствие с пропорциями. по этому запросу нужно создать задачу в очередь и в ответ дать абстрактный идентификатор задачи. требуется ограничить размер картинки - не более 2000 пикселей в любую сторону, не более 10 мб размер файла.

2) GET /image/ID, где ID идентификатор из предыдущего ответа. выдавать состояние задачи, если задача готова - выдавать url (!) картинки измененного размера.


# Установка
```bash
git clone git@github.com:drumser/ViqeoTestTask.git viqeo
cd viqeo
composer install
php artisan migrate
```

# Настройка
```bash
cp .env.example .env
php artisan storage:link
crontab -l | { cat; echo "* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1"; } | crontab -
php artisan queue:work
```

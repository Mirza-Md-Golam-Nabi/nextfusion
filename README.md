# NextFusion

## প্রয়োজনীয়তা

- PHP 8.3+ এবং Composer
- Node.js ও npm
- MySQL

---

## লোকাল সার্ভারে চালানো

**১. প্রোজেক্ট clone করুন**

```bash
git clone <repository-url> nextfusion
cd nextfusion
```

**২. Dependency install করুন**

```bash
composer install
npm install
```

**৩. `.env` ফাইল তৈরি করুন**

```bash
cp .env.example .env
php artisan key:generate
```

**৪. Database সেটআপ করুন**

MySQL-এ `nextfusion` নামে একটি database তৈরি করুন, তারপর `.env`-এ তথ্য দিন:

```env
APP_NAME=NextFusion
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nextfusion
DB_USERNAME=root
DB_PASSWORD=
```

**৫. Migration, seed ও storage link**

```bash
php artisan migrate --seed
php artisan storage:link
```

**৬. প্রোজেক্ট চালু করুন**

```bash
composer run dev
```

এটি একসাথে Laravel server, queue listener এবং Vite dev server চালু করে।

- ওয়েবসাইট: http://localhost:8000
- Admin panel: http://localhost:8000/admin
- Admin login: `admin@nextfusion.com` / `password`

---

## লাইভ সার্ভারে আপলোড (Production)

**১. সার্ভারে প্রোজেক্ট clone করুন**

```bash
cd /var/www
git clone <repository-url> nextfusion
cd nextfusion
```

**২. Dependency install ও frontend build করুন**

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

> সার্ভারে Node.js না থাকলে লোকালে `npm run build` চালিয়ে তৈরি হওয়া `public/build` ফোল্ডারটি সার্ভারে আপলোড করুন।

**৩. `.env` ফাইল তৈরি করুন**

```bash
cp .env.example .env
php artisan key:generate
```

`.env`-এ production মান দিন:

```env
APP_NAME=NextFusion
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS="info@your-domain.com"
```

> `APP_URL` সঠিক domain দিতে হবে — school-এর `.env`-এ যে `CENTRAL_URL` দেওয়া হয় সেটি এখান থেকেই আসে, আর upload করা ছবির link-ও এটি দিয়ে তৈরি হয়।

**৪. Migration, seed ও storage link**

```bash
php artisan migrate --seed --force
php artisan storage:link
```

> Seed করার পর অবশ্যই admin panel-এ লগইন করে `admin@nextfusion.com` এর password পরিবর্তন করুন।

**৫. Folder permission দিন**

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

**৬. Cache ও optimize করুন**

```bash
php artisan optimize
php artisan filament:optimize
```

**৭. Web server কনফিগার করুন**

Domain-এর document root অবশ্যই প্রোজেক্টের **`public`** ফোল্ডারে দিতে হবে। Nginx-এর উদাহরণ:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/nextfusion/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

তারপর SSL চালু করুন (যেমন: `sudo certbot --nginx -d your-domain.com`)।

> cPanel / shared hosting-এ domain-এর document root প্রোজেক্টের `public` ফোল্ডারে সেট করুন।

**৮. Queue worker চালু রাখুন**

`QUEUE_CONNECTION=database` ব্যবহার হয়, তাই Supervisor দিয়ে worker চালু রাখুন। `/etc/supervisor/conf.d/nextfusion-worker.conf`:

```ini
[program:nextfusion-worker]
command=php /var/www/nextfusion/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/nextfusion/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start nextfusion-worker:*
```

**৯. Scheduler-এর জন্য cron যোগ করুন**

```bash
crontab -e
```

```cron
* * * * * cd /var/www/nextfusion && php artisan schedule:run >> /dev/null 2>&1
```

---

## লাইভ সার্ভারে আপডেট (নতুন কোড deploy)

```bash
cd /var/www/nextfusion
php artisan down

git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build

php artisan migrate --force
php artisan optimize
php artisan filament:optimize
php artisan queue:restart

php artisan up
```

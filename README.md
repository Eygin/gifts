<h1 align="center">Aplikasi Gift</h1>

## Requirement Aplikasi

1. VSCode
2. PHP 7.3 atau PHP 8
3. Laravel 8
4. Postman
5. Composer

## Modul Aplikasi

1. Gift
2. User
3. Role
4. Permission

## Setup documentation

1. Setup Aplikasi:
    - Pertama jalankan : <code>composer install</code>
    - Duplikat file .env.example dan ubah nama file yang sudah di duplikat tadi menjadi .env
    - Jalankan perintah : <code>php artisan key:generate</code>
    - Isi bagian DB_DATABASE, DB_USERNAME, dan DB_PASSWORD: <br>
    <code>
        DB_CONNECTION=mysql <br>
        DB_HOST=127.0.0.1 <br>
        DB_PORT=3306 <br>
        DB_DATABASE=laravel <br>
        DB_USERNAME=root <br>
        DB_PASSWORD= <br>
    </code>
    di .env sesuaikan dengan nama dan password koneksi kalian
    - Jalankan perintah : <code>php artisan config:cache</code>
    - Kemudian <code>php artisan migrate</code> untuk menjalankan perintah migrasi ke database
    - Kemudian jalankan seeder dengan perintah <code>php artisan db:seed --class=UserSeeder</code> dan <code>php artisan db:seed --class=GiftSeeder</code> untuk dummy data ke database
    - Setelah itu jalankan perintah : <code>php artisan jwt:secret</code> untuk generate string hash yang digunakan untuk JWT, dan juga tambahkan code <code>JWT_SHOW_BLACKLIST_EXCEPTION = true</code> dibawah code hasil generate tadi
    - Lakukan kembali perintah : <code>php artisan config:cache</code> untuk clear cache file .env tadi
    - Kemudian jalankan perintah <code>php artisan serve</code> untuk menjalankan aplikasi di lokal

<i>Catatan: untuk point migrasi ke database bisa menggunakan 2 opsi, bisa melalui perintah <code>php artisan serve</code> atau import ke database menggunakan file .sql yang sudah di sediakan di folder docs </i>


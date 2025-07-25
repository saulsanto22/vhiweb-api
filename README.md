# VhiWEB Backend Developer Test Project

## Project Overview

Ini adalah implementasi dari tes backend developer untuk **VhiWEB**. Aplikasi ini dibangun dengan **Laravel 12** dan menggunakan arsitektur clean code.

Fitur utama:
- Register dan login user
- Register vendor (satu user hanya bisa punya satu vendor)
- CRUD produk milik vendor user
- Pencarian produk dan pagination dinamis
- Token-based auth menggunakan Laravel Sanctum

---

## Tech Stack

- Laravel 12
- Sanctum
- MySQL / PostgreSQL
- Laravel Resource
- FormRequest Validation
- Service Layer Architecture
- Response Macro

---

## API Features

###  Authentication
- `POST /register` â†’ Register user
- `POST /login` â†’ Login dan dapatkan token
- `POST /logout` â†’ Logout dan revoke token

###  Vendor
- `GET /vendors` â†’ Ambil vendor milik user login
- `POST /vendors` â†’ Daftarkan vendor baru (user hanya bisa punya 1)

###  Product
- `GET /products` â†’ List produk (pagination & search)
- `POST /products` â†’ Tambah produk
- `PUT /products/{id}` â†’ Update produk (cek pemilik)
- `DELETE /products/{id}` â†’ Hapus produk (cek pemilik)

---

##  Clean Code Structure

###   Service Layer
Semua logic disimpan di folder `app/Services`, agar controller tetap bersih dan mudah diuji.

###  FormRequest
Validasi tidak ditulis di controller, tapi dipisahkan di folder `Http/Requests`.

###  Response Macro
Menggunakan helper custom:

```php
response()->success('Message', $data);
response()->error('Message', 400);
```

---

##  Database Relationships

### User â†’ Vendor (hasOne)
- Karena user hanya bisa mendaftarkan **satu** vendor, maka relasinya adalah `hasOne`.

```php
// User.php
public function vendor() {
    return $this->hasOne(Vendor::class);
}
```

### Vendor â†’ Products (hasMany)
```php
// Vendor.php
public function products() {
    return $this->hasMany(Product::class);
}
```

---

## Keamanan

- Semua route dilindungi oleh middleware `auth:sanctum`
- Cek kepemilikan produk sebelum update/hapus
- Tidak bisa tambah produk jika user belum punya vendor

---

## Search & Pagination

- Pencarian produk berdasarkan `name` dan `description`:
  ```
  GET /products?search=keyboard
  ```
- Pagination dinamis dengan `per_page`:
  ```
  GET /products?per_page=10
  ```

---

##  Cara Menjalankan Project

1. Clone project
```bash
git clone <repo-url>
cd vhiweb-api
```

2. Install dependencies
```bash
composer install
```

3. Setup `.env` dan generate key
```bash
cp .env.example .env
php artisan key:generate
```

4. Jalankan migrasi database
```bash
php artisan migrate
```

5. Jalankan project
```bash
php artisan serve
```

---

##  Testing

Gunakan Postman atau REST client:
1. Register user
2. Login â†’ salin token
3. Tambahkan token ke header:
   ```
   Authorization: Bearer {token}
   ```
4. Akses endpoint vendor & produk

---

##  Penutup

Project ini dibangun untuk menjawab soal dengan pendekatan clean architecture:
- Code rapi, scalable
- Validasi terpisah
- Response konsisten

Siap dikembangkan lebih lanjut dengan fitur import/export, unit test, atau dokumentasi API otomatis.

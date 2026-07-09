# Kanban Board API

RESTful API profesional untuk aplikasi Kanban Board (seperti Trello) yang dibangun menggunakan **Laravel 11** dan **PHP 8.5**. API ini dilengkapi dengan sistem keamanan berbasis token menggunakan **Laravel Sanctum**, validasi hak kepemilikan data yang ketat (authorization), serta fitur pengelolaan manajemen proyek yang dinamis.

* **Nama Project:** Mini Kanban Board API
* **Nama Peserta:** Marga Reta Novia Putri

---

## 🛠️ Teknologi & Environment yang Digunakan
* **Backend Framework:** Laravel 11
* **Language Version:** PHP 8.5
* **Authentication:** Laravel Sanctum (Token-Based Authentication)
* **Database:** MySQL
* **API Testing Tool:** Postman

---

## 🚀 Fitur Utama
1.  **Authentication & Profile:** Registrasi, Login, proteksi Route dengan Middleware Sanctum, serta manajemen data profil pengguna.
2.  **Board Management (Papan Kanban):** CRUD Papan Proyek dengan otorisasi ketat—pengguna hanya dapat melihat, mengedit, dan menghapus Papan miliknya sendiri.
3.  **Column Management (Kolom):** Penambahan dan pengubahan sekat kolom secara dinamis (Default: *Backlog, To Do, In Progress, Done*). Kolom memiliki proteksi *Cascade* aman, serta **tidak dapat dihapus jika masih terdapat Kartu tugas di dalamnya**.
4.  **Card Management (Kartu Tugas):** Pengelolaan penuh kartu tugas yang mencakup Judul tugas, Deskripsi, Prioritas (*High/Medium/Low*), dan tenggat waktu (*Deadline*).
5.  **Move Card Endpoint:** Fitur perpindahan kartu antar kolom secara dinamis dengan validasi lintas papan untuk memastikan integritas data.

---

## ⚙️ Cara Instalasi & Menjalankan Backend

Ikuti langkah-langkah di bawah ini untuk memasang dan menjalankan proyek secara lokal:

1. **Clone Repository**
   ```bash
   git clone https://github.com/margaretanp/kanban_api.git 
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin file template `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   *Buka file `.env` menggunakan teks editor lalu pastikan konfigurasi database MySQL lokal Anda sudah sesuai:*
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kanban_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi & Seed Database**
   Perintah ini akan menyusun ulang seluruh struktur tabel secara berurutan (*Boards -> Columns -> Cards*) serta mengeksekusi robot pengisi data otomatis (*Seeder*):
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```
   Server lokal akan berjalan di alamat `http://127.0.0.1:8000`.

---

## 🔐 Credential Login (Hasil Seeder)
Anda dapat menggunakan akun uji coba di bawah ini pada endpoint `POST /api/login` untuk mendapatkan *Bearer Token*:
* **Akun 1 (Admin/User 1):** `admin@admin.com` | Password: `password123`
* **Akun 2 (User 2):** `user1@example.com` | Password: `password123`

---

## 🧪 Cara Testing API & Collection Postman
1. Buka aplikasi **Postman**.
2. Klik tombol **Import** di pojok kiri atas, pilih file **`postman_collection.json`** yang terletak di root folder proyek ini.
3. Jalankan request **Login** di bawah folder *1. Authentication & Profile*.
4. Salin string `token` panjang yang didapatkan dari response sukses.
5. Pada request lainnya, masuk ke tab **Authorization**, pilih tipe **Bearer Token**, lalu tempelkan (*paste*) token tersebut ke kolom yang tersedia untuk membuka gembok akses API.

---

## 📡 Daftar Endpoint API

### 1. Authentication & Profile
| Method | Endpoint | Deskripsi | Auth (Bearer) |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/register` | Mendaftarkan pengguna baru | Tidak |
| `POST` | `/api/login` | Autentikasi pengguna & mendapatkan token | Tidak |
| `GET` | `/api/profile` | Mengambil data profil pengguna yang login | **Ya** |
| `PUT` | `/api/profile` | Memperbarui data profil pengguna | **Ya** |
| `POST` | `/api/logout` | Menghapus token aktif & keluar dari sistem | **Ya** |

### 2. Boards (Papan Kanban)
| Method | Endpoint | Deskripsi | Auth (Bearer) |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/boards` | Melihat semua papan milik pengguna aktif | **Ya** |
| `POST` | `/api/boards` | Membuat papan kanban baru | **Ya** |
| `GET` | `/api/boards/{id}` | Melihat detail papan tertentu beserta seluruh Kolom & Kartunya | **Ya** |
| `PUT` | `/api/boards/{id}` | Mengubah judul papan (Otorisasi Pemilik) | **Ya** |
| `DELETE` | `/api/boards/{id}` | Menghapus papan beserta seluruh isi di dalamnya (*Cascade*) | **Ya** |

### 3. Columns (Sekat Kolom)
| Method | Endpoint | Deskripsi | Auth (Bearer) |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/boards/{id}/columns` | Menambahkan kolom baru ke dalam papan tertentu | **Ya** |
| `PUT` | `/api/columns/{id}` | Mengubah nama sekat kolom | **Ya** |
| `DELETE` | `/api/columns/{id}` | Menghapus sekat kolom **(Hanya jika kosong dari kartu)** | **Ya** |

### 4. Cards (Kartu Tugas)
| Method | Endpoint | Deskripsi | Auth (Bearer) |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/cards` | Membuat kartu tugas baru di kolom tertentu | **Ya** |
| `PUT` | `/api/cards/{id}` | Memperbarui rincian data kartu tugas | **Ya** |
| `PATCH` | `/api/cards/{id}/move` | Memindahkan kartu tugas ke kolom target | **Ya** |
| `DELETE` | `/api/cards/{id}` | Menghapus kartu tugas secara permanen | **Ya** |

---

## 📝 Contoh Request dan Response Realistis

### 1. Login Pengguna (`POST /api/login`)
* **Request Body (JSON):**
    ```json
    {
        "email": "admin@admin.com",
        "password": "password123"
    }
    ```
* **Response Sukses (200 OK):**
    ```json
    {
        "message": "Login success",
        "token": "1|sanctum_generated_token_string_xyz...",
        "user": {
            "id": 1,
            "name": "Admin",
            "email": "admin@admin.com"
        }
    }
    ```

### 2. Memperbarui Profil (`PUT /api/profile`)
* **Request Body (JSON):**
    ```json
    {
        "name": "Margareta Novia",
        "email": "admin@admin.com"
    }
    ```
* **Response Sukses (200 OK):**
    ```json
    {
        "message": "Profile updated successfully",
        "user": {
            "id": 1,
            "name": "Margareta Novia",
            "email": "admin@admin.com"
        }
    }
    ```

### 3. Membuat Kartu Baru (`POST /api/cards`)
* **Request Body (JSON):**
    ```json
    {
        "board_id": 1,
        "column_id": 1,
        "title": "Membuat Halaman Login",
        "description": "Membuat form input email dan password terproteksi Sanctum",
        "priority": "High",
        "deadline": "2026-07-20"
    }
    ```
* **Response Sukses (201 Created):**
    ```json
    {
        "message": "Card created successfully",
        "data": {
            "id": 5,
            "board_id": 1,
            "column_id": 1,
            "title": "Membuat Halaman Login",
            "description": "Membuat form input email dan password terproteksi Sanctum",
            "priority": "High",
            "deadline": "2026-07-20",
            "updated_at": "2026-07-10T06:20:00.000000Z",
            "created_at": "2026-07-10T06:20:00.000000Z"
        }
    }
    ```

### 4. Memindahkan Kartu Antar Kolom (`PATCH /api/cards/{id}/move`)
* **URL Endpoint:** `http://127.0.0.1:8000/api/cards/1/move`
* **Request Body (JSON):**
    ```json
    {
        "target_column_id": 2
    }
    ```
* **Response Sukses (200 OK):**
    ```json
    {
        "message": "Card moved successfully",
        "card": {
            "id": 1,
            "title": "Tugas contoh 1",
            "column_id": 2,
            "updated_at": "2026-07-10T06:20:00.000000Z"
        }
    }
    ```

### 5. Validasi Error Hapus Kolom yang Berisi Kartu (`DELETE /api/columns/{id}`)
* **URL Endpoint:** `http://127.0.0.1:8000/api/columns/1`
* **Response Gagal (400 Bad Request):**
    ```json
    {
        "message": "Kolom tidak dapat dihapus karena masih memiliki card"
    }
    ```
# Task 1: Otentikasi dan Manajemen Pengguna

## Deskripsi Fitur
Menyesuaikan sistem autentikasi dan manajemen pengguna (User Management) yang sudah ada di Laravel agar selaras dengan PRD. Fokus utama adalah pada penerapan peran (role) pengguna yaitu: **Superadmin**, **Admin**, dan **Dokter**.

## Requirement (Kebutuhan)
1. **Penyesuaian Struktur Tabel**:
   - Modifikasi tabel `users` (atau tabel relasi yang sudah ada) untuk mengakomodasi field `role` (Superadmin, Admin, Dokter) sesuai dengan spesifikasi ERD pada PRD.
2. **Visualisasi Data (Seeder)**:
   - Perbarui file seeder user yang sudah ada (`UserSeeder`) untuk menyuntikkan (insert) data dummy. Pastikan terdapat minimal satu representasi akun untuk setiap role (1 Superadmin, 1 Admin, 1 Dokter) guna mempermudah testing.
3. **Penyesuaian Logika CRUD**:
   - Sesuaikan logika pada Controller, Request, dan View pada modul Manajemen Pengguna yang saat ini sudah berjalan.
   - Pastikan proses *Create, Read, Update, dan Delete* dapat mengenali field role baru dan memprosesnya dengan benar.
4. **Konsistensi Kode (Wajib)**:
   - Wajib mengikuti standar coding style, pola arsitektur, dan konvensi penamaan yang *sudah ada* di dalam repositori (misalnya nama controller, format form, penggunaan Datatables, dll). 
   - Dilarang membuat pola atau arsitektur baru yang tidak konsisten dengan modul user saat ini.

## Acceptance Criteria
- [x] Terdapat file migration untuk memperbarui struktur tabel user terkait peran (role).
- [x] `UserSeeder` berhasil menjalankan data dummy untuk 3 role secara terpisah.
- [x] Halaman manajemen pengguna (User CRUD) berfungsi normal dan form sudah memuat opsi role yang baru.
- [x] Kode ditulis sesuai dengan struktur asli repositori tanpa merombak arsitektur secara mendasar.

# Task 3: Pelayanan dan Antrian Pasien

## Deskripsi Fitur
Sistem antrian harian untuk pasien yang datang berkunjung. Modul ini menghubungkan Pasien dengan Dokter pemeriksa pada hari yang ditentukan.

## Requirement (Kebutuhan)
1. **Struktur Tabel**:
   - Buat tabel `visits` yang mencatat relasi antara pasien (`patient_id`), dokter (`doctor_id`), tanggal kunjungan, nomor antrian, dan status (`Waiting`, `Examining`, `Done`, `Cancelled`).
2. **Visualisasi Data (Seeder)**:
   - Buat `VisitSeeder` untuk menyuntikkan data antrian dummy. Buat agar ada antrian hari ini dengan variasi status.
3. **Modul Pendaftaran Antrian**:
   - Antarmuka (khusus Admin) untuk mendaftarkan pasien ke dokter tertentu di tanggal berjalan.
   - Sistem akan meng-generate nomor antrian otomatis berdasarkan poli/dokter pada tanggal kunjungan tersebut (reset setiap hari).
4. **Modul Monitor Antrian**:
   - Halaman daftar antrian aktif untuk memantau status pasien.

## Acceptance Criteria
- [ ] Tabel `visits` berelasi sempurna dengan tabel pasien dan pengguna (role dokter).
- [ ] Antrian nomor dapat digenerate otomatis secara urut (auto-increment harian).
- [ ] Seeder berhasil membuat data dummy antrian pasien.
- [ ] Daftar antrian dapat ditampilkan di dashboard Admin dan Dokter.

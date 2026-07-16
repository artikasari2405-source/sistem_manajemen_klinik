# Task 2: Master Data (Pasien, Obat, dan Tindakan)

## Deskripsi Fitur
Membuat modul Master Data yang mencakup pencatatan data Pasien, inventaris Obat, dan daftar Layanan/Tindakan medis beserta tarifnya.

## Requirement (Kebutuhan)
1. **Struktur Tabel**:
   - Buat tabel `patients` (No RM, Nama, Jenis Kelamin, Tgl Lahir, Telepon, Alamat).
   - Buat tabel `medicines` (Kode Obat, Nama, Stok, Harga).
   - Buat tabel `treatments` (Nama Tindakan, Tarif/Harga).
2. **Visualisasi Data (Seeder)**:
   - Buat seeder untuk masing-masing entitas (misal: `PatientSeeder`, `MedicineSeeder`, `TreatmentSeeder`).
   - Generate minimal 10-20 data dummy untuk tiap entitas menggunakan library Faker.
3. **Modul CRUD (Create, Read, Update, Delete)**:
   - Antarmuka CRUD untuk data Pasien.
   - Antarmuka CRUD untuk data Obat.
   - Antarmuka CRUD untuk data Tindakan.
   - *Styling* wajib mengikuti struktur form dan Datatables bawaan NiceAdmin yang sudah dipakai di sistem.

## Acceptance Criteria
- [x] Tabel `patients`, `medicines`, dan `treatments` berhasil dibuat melalui migration.
- [x] Seeder berfungsi dengan baik untuk mengisi data dummy awal.
- [x] Role Superadmin dan Admin dapat mengakses, menambah, mengubah, dan menghapus data pada tiga master data tersebut dengan sempurna.

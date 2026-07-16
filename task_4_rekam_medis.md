# Task 4: Rekam Medis (Electronic Medical Record / EMR)

## Deskripsi Fitur
Modul bagi Dokter untuk melakukan pemeriksaan kepada pasien, mencatat diagnosa, meresepkan obat, serta menginput tindakan medis yang dilakukan selama pemeriksaan.

## Requirement (Kebutuhan)
1. **Struktur Tabel**:
   - Buat tabel `medical_records` (keluhan/symptom, diagnosa, catatan). Terhubung dengan `visits`.
   - Buat tabel `prescriptions` (pivot resep obat: id obat, kuantitas, aturan pakai, subtotal).
   - Buat tabel `record_treatments` (pivot tindakan: id tindakan, subtotal).
2. **Visualisasi Data (Seeder)**:
   - Buat `MedicalRecordSeeder` yang mensimulasikan riwayat pemeriksaan pasien lengkap dengan obat dan tindakannya.
3. **Modul Pemeriksaan Dokter (EMR)**:
   - Antarmuka khusus dokter: mengubah status antrian dari `Waiting` menjadi `Examining`.
   - Form input Rekam Medis utama (keluhan, diagnosa).
   - Fitur dynamic input (multiple rows) untuk memasukkan beberapa Obat dan Tindakan sekaligus.
   - Otomatis mengurangi stok di tabel master Obat setelah resep tersimpan.
   - Otomatis merubah status visit menjadi `Done` jika rekam medis selesai disimpan.

## Acceptance Criteria
- [ ] Relasi rekam medis dengan resep obat dan tindakan (One-to-Many pivot) terekam dengan benar di database.
- [ ] Stok pada tabel `medicines` berkurang sesuai quantity yang ada di `prescriptions`.
- [ ] Terdapat riwayat pemeriksaan dummy dari seeder yang valid secara logika bisnis.
- [ ] Form dynamic untuk penambahan obat/tindakan berjalan lancar di tampilan (UI).

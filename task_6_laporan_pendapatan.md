# Task 6: Laporan Pendapatan dan Kunjungan

## Deskripsi Fitur
Menyediakan dashboard laporan operasional dan finansial klinik untuk Superadmin / Pemilik Klinik guna mendukung pengambilan keputusan.

## Requirement (Kebutuhan)
1. **Dashboard Laporan**:
   - Widget ringkasan total pendapatan hari ini dan bulan ini.
   - Widget ringkasan jumlah kunjungan pasien hari ini dan bulan ini.
2. **Filter & Tabel**:
   - Halaman detail laporan yang memungkinkan filter berdasarkan rentang tanggal (Start Date - End Date).
   - Menampilkan detail per transaksi/kunjungan pada tabel data.
3. **Ekspor Laporan**:
   - Tombol ekspor laporan ke format PDF menggunakan `laravel-dompdf` (sesuai readme).
4. **Visualisasi Data (Seeder)**:
   - Gunakan generator acak pada `VisitSeeder` dan `TransactionSeeder` dari Task sebelumnya untuk membuat pola kunjungan yang beragam (acak tanggal selama 1 bulan terakhir) agar dashboard tidak kosong dan grafiknya dapat terlihat.

## Acceptance Criteria
- [ ] Halaman laporan berfungsi dan merender data pendapatan secara akurat.
- [ ] Fungsi Export PDF dapat mendownload file tanpa error (CSS/tampilan PDF rapi).
- [ ] Karena ada seeder dummy historical, grafik/widget langsung menunjukkan data yang bisa dianalisa secara visual ketika sistem pertama kali diinstall.

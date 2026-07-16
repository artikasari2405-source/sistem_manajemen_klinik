# Task 5: Pembayaran dan Kasir

## Deskripsi Fitur
Modul untuk kalkulasi akhir total biaya tagihan berdasarkan rekam medis dan memproses pembayaran oleh pasien di bagian kasir.

## Requirement (Kebutuhan)
1. **Struktur Tabel**:
   - Buat tabel `transactions` yang menyimpan nilai `total_amount`, `payment_amount`, `status` (`Unpaid`, `Paid`), dan metode pembayaran.
2. **Visualisasi Data (Seeder)**:
   - Buat `TransactionSeeder` yang berisi data dummy riwayat pembayaran (baik yang unpaid maupun paid) dari pasien-pasien dummy sebelumnya.
3. **Modul Tagihan & Pembayaran**:
   - Halaman daftar pasien yang telah selesai diperiksa (`Done` di visits) untuk ditagihkan.
   - Auto-kalkulasi biaya total = (Total Jasa/Tindakan di record_treatments) + (Total Obat di prescriptions). Jika ada biaya Jasa Dokter tetap, tambahkan juga.
   - Form untuk menginput jumlah pembayaran (nominal uang diterima) dan secara otomatis menampilkan hitungan kembalian.
   - Cetak struk/resi pembayaran secara sederhana.

## Acceptance Criteria
- [x] Biaya tagihan dapat dihitung dengan presisi (akurat sesuai subtotal data resep & tindakan).
- [x] Admin/Kasir dapat menginput nominal pembayaran.
- [x] Terdapat mekanisme cetak (PDF atau browser window print) untuk invoice transaksi.
- [x] Seeder berhasil menampilkan simulasi transaksi kasir.

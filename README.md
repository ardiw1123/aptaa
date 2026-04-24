# APTAA System

Selamat datang di sistem informasi manajemen APTAA! 

Sistem ini dirancang khusus untuk mendigitalisasi dan mempermudah pengelolaan rantai pasok (Supply Chain), inventaris gudang, penjualan, hingga manajemen kehadiran pegawai berbasis lokasi (*Geolocation*).

Dokumentasi ini adalah panduan lengkap bagi pengguna untuk memahami alur kerja dan cara menggunakan fitur-fitur di dalam aplikasi.

---

## Daftar Isi
1. [Akses Sistem (Demo Login)](#-akses-sistem-demo-login)
2. [Alur Kerja Sistem (System Workflow)](#-alur-kerja-sistem)
3. [Panduan Penggunaan (Langkah-demi-Langkah)](#-panduan-penggunaan-langkah-demi-langkah)
4. [FAQ & Solusi Kendala](#-faq--solusi-kendala)

---

## Situs Website: https://aptaa-main-krok8v.free.laravel.cloud

---

## Akses Sistem (Demo Login)

Sistem ini membagi hak akses ke dalam beberapa peran (*Role*). Untuk mencoba fitur secara menyeluruh, silakan *login* menggunakan informasi akun berikut pada halaman utama:

* **Manajer** | Email: `manajer@aptaa.com` | Password: `password123`
* **Admin** | Email: `admin@aptaa.com` | Password: `password123`
* **Tim Marketing** | Email: `marketing@aptaa.com` | Password: `password123`
* **Tim Gudang** | Email: `gudang@aptaa.com` | Password: `password123`
* **Tim Barang** | Email: `timbarang@aptaa.com` | Password: `password123`

*(Catatan: Kredensial di atas adalah data sampel untuk keperluan uji coba).*

---

## Alur Kerja Sistem

Untuk memahami cara sistem APTAA bekerja, pengguna dapat mengikuti 3 alur utama operasional bisnis:

1. **Alur Barang Masuk:** Tim Gudang mencatat stok yang tiba di lokasi ➔ Data masuk ke antrean verifikasi ➔ Admin mengecek kesesuaian data stok dan menekan tombol verifikasi ➔ Stok utama (Master) bertambah.
2. **Alur Permintaan:** Tim Marketing menginput pesanan (PO) dari pelanggan ➔ Admin memantau kebutuhan barang melalui sistem ➔ Admin menginput data permintaan stok ➔ Manajer memverifikasi dan mengunduh data permintaan stok.
3. **Alur Penjualan:** Admin/Kasir menginput transaksi penjualan ➔ Stok utama otomatis berkurang ➔ Manajer melihat laporan penjualan dan analitik harian.

---

## Panduan Penggunaan (Langkah-demi-Langkah)

### 1. Fitur HR: Melakukan Absensi GPS (Semua Role)
1. *Login* ke dalam sistem. Anda akan langsung diarahkan ke halaman **Dashboard**.
2. Jika *browser* memunculkan *pop-up* permintaan izin lokasi ("Allow Location" / "Izinkan Akses Lokasi"), pastikan Anda menekan **Allow / Izinkan**.
3. Klik tombol biru **"Absen Masuk"**. Sistem akan secara otomatis mengunci kordinat Anda dan mencatat waktu kedatangan.
4. Setelah jam kerja selesai, kembali ke Dashboard dan klik tombol kuning **"Absen Pulang"**. 

### 2. Tim Gudang: Mencatat Kedatangan Barang Baru
1. *Login* sebagai **Tim Gudang** dan masuk ke menu **Input Stok Masuk**.
2. Pilih nama barang, masukkan tanggal masuk, jumlah (Ekor), dan berat (Kg).
3. Klik **Simpan**. 
* **PENTING:** Barang yang baru diinput *tidak akan* langsung menambah stok master sebelum divalidasi oleh Admin.

### 3. Admin: Verifikasi (ACC) Stok Masuk
1. *Login* sebagai **Admin**, buka menu **Stok Masuk**.
2. Cari tanggal yang memiliki label peringatan kuning **"Butuh ACC"**. Klik tombol **Lihat Detail** pada baris tersebut.
3. Anda akan melihat rincian barang. Lakukan pencocokan dengan fisik barang di gudang dan data permintaan stok sebelumnya.
4. Jika sudah sesuai, klik tombol hijau **Verifikasi**. Status akan berubah menjadi "Sudah Valid" dan data barang masuk pada stok master siap untuk dijual.

### 4. Admin: Input Penjualan & Cek Riwayat
1. Buka menu **Input Penjualan**.
2. Pilih barang yang akan dijual, masukkan kuantitas, dan pastikan total harga sudah sesuai.
3. Klik **Simpan Transaksi**. Stok fisik di menu Monitor Stok akan otomatis berkurang.
4. Untuk melihat transaksi yang sudah berlalu, buka menu **Riwayat Penjualan**.

### 5. Tim Barang: Opname Data Ketersediaan Stok
1. *Login* sebagai **Tim Barang**, buka menu **Input Data Stok**.
2. Masukkan tanggal opname, pilih jenis barang/produk, masukan sisa stok fisik yang ada (ekor/kg), tambahkan catatan jika perlu & klik simpan.
3. Anda bisa melihat riwayat pada menu **Riwayat Cek Fisik** berdasarkan jenis item yang anda input.
4. Anda bisa mengedit data riwayat sebelum admin melakukan verifikasi.

### 6. Admin: Monitor & Verifikasi Stok
1. *Login* sebagai **Admin**, buuka menu **Monitor Stok**.
2. Anda akan melihat data stok dalam sistem dan data stok fisik yang sudah diopname **Tim Barang**.
3. Anda bisa melihat perbedaan antara data dalam sistem dan data fisik.
4. Anda akan melihat status/info berupa status validasi dan info stok(kritis, menipis, aman).
5. Klik **"Acc Fisik"** pada produk yang sudah diopname.

### 7. Tim Marketing: Input Pesanan Pelanggan
1. *Login* sebagai **Tim Marketing**, buka menu **Input Pesanan Pelanggan**.
2. Masukkan tanggal, nama pemesan, tipe pesanan(B2B, B2C, Eceran, Partai Besar), masukkan jumlah (kg/ekor) pada jenis barang yang dipesan klik simpan.
3. Anda bisa melihat riwayat pada menu **Daftar Pesanan Pelanggan** berdasarkan no pesanan yang anda buat.
4. Anda bisa mengedit data pesanan sebelum anda mengirimkan ke admin.
5. Kirim data ke admin, sistem akan mengunci data agar tidak bisa diedit.

### 8. Admin: Memantau Permintaan Marketing 
1. *Login* sebagai **Admin**, buka menu **Pesanan Pelanggan**.
2. Anda akan melihat daftar pesanan dari pelanggan secara ringkas.
3. Klik tombol **Lihat Detail** pada nomor PO tertentu untuk melihat rincian jenis barang, target kuantitas (Ekor), dan estimasi berat (Kg) yang harus segera disiapkan dalam dokumen permintaan stok.

### 9. Admin: Input Data Permintaan Stok (PO)
1. *Login* sebagai **Admin**, buka menu ** Data Permintaan Stok**.
2. Anda akan melihat riwayat permintaan stok yang pernah anda buat.
3. Klik **"Buat PO baru"**.
4. Masukkan tanggal request, catatan (opsional), masukkan jumlah (ekor/kg) pada barang yang anda butuhkan, klik buat permintaan stok.
5. Anda akan melihat bahwa data permintaan perlu di-acc oleh manajer.
6. Anda bisa mengunduh data dalam bentuk file PDF/Excel.

### 10. Manajer: Verifikasi Permintaan Stok
1. *Login* sebagai **Manajer**, lalu buka menu **Permintaan Stok**.
2. Anda akan melihat daftar permintaan stok dari admin berdasarkan no request.
3. Klik **"Review"** untuk melihat detail data permintaan stok.
4. Anda akan melihat daftar barang dan jumlah data permintaan yang dibuat oleh admin.
5. Klik **"ACC"** jika anda setuju, **"klik Tolal"** jika anda tidak setuju.
6. Anda bisa mengunduh data dalam bentuk file PDF/Excel.

### 11. Manajer: Memantau Laporan
1. *Login* sebagai **Manajer**, lalu buka menu **Dashboard Laporan**.
2. Anda dapat melihat metrik penjualan secara *real-time* dan melihat detail transaksi yang telah berhasil.

### 12. Manajer: Manajemen Data Barang
1. *Login* sebagai **Manajer**, lalu buka menu **Data Barang**
2. Anda akan melihat data master barang yang ada dalam bisnis anda.
3. Disini anda juga bisa melihat ketersediaan stok secara real-time.
4. Anda bisa menambahkan barang baru dengan fitur **Tambah Barang Baru**
5. Pada halaman form anda harus memasukkan kode SKU, Nama Produk/Barang, Kategori(Dropdown) dan Satuan Utama(Kg/Ekor)
6. Saat anda mengklik simpan data barang, maka database akan diperbarui otomatis
7. Selain itu, anda juga bisa melakukan edit data jika dirasa ada kesalahan pada data barang dengan fitur pensil pada kolom aksi
8. Di halaman ini tidak jauh beda dengan halaman form input tetapi disini sudah ada data sebelumnya yang ingin diedit
9. Saat anda mengklik simpan perubahan, maka database akan diperbarui otomatis

---

## ❓ FAQ & Solusi Kendala

**Q: Mengapa saya gagal melakukan absen atau muncul pesan Error Lokasi?**
> **A:** Fitur absensi mewajibkan perangkat Anda mengirimkan kordinat GPS. Pastikan fitur *Location / GPS* di HP atau Laptop Anda sudah menyala. Jika menggunakan browser seperti Chrome/Safari, pastikan Anda tidak memblokir izin akses lokasi untuk situs ini di pengaturan *browser*.

**Q: Tombol Absen saya hilang, bagaimana cara absen masuk?**
> **A:** Sistem mendeteksi bahwa Anda sudah melakukan "Absen Masuk" dan "Absen Pulang" pada hari ini. Tombol akan otomatis muncul kembali keesokan harinya.

**Q: Saya sudah menginput barang masuk, tapi mengapa barangnya tidak ada saat saya ingin membuat transaksi penjualan?**
> **A:** Setiap barang yang diinput oleh Tim Gudang akan berstatus *Pending* sebagai bentuk keamanan sistem. Silakan minta **Admin Gudang** untuk melakukan *Verifikasi (ACC)* data tersebut pada menu Detail Stok Masuk agar stok bertambah.

**Q: Mengapa waktu absen yang tercatat berbeda dengan jam di HP saya?**
> **A:** Hal ini dapat terjadi jika pengaturan zona waktu pada server aplikasi belum disesuaikan. Silakan laporkan hal ini kepada Tim IT / Administrator Sistem untuk menyesuaikan konfigurasi zona waktu (WIB/WITA/WIT).

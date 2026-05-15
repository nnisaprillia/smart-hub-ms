# Testing Guide — Smart-Hub Management System

Panduan ini berisi langkah pengujian lengkap untuk semua fitur web admin dan REST API tablet. Setiap item dilengkapi checklist agar kamu bisa mencentang hasil pengujian.

---

## 1. Persiapan Pengujian

- [ ] Pastikan server Laravel berjalan:
  - `php artisan serve --host=127.0.0.1 --port=8000`
- [ ] Pastikan asset telah dibuild jika ada perubahan front-end:
  - `npm install`
  - `npm run build`
- [ ] Pastikan database sudah diisi data test:
  - `php artisan migrate:fresh --seed`

> Gunakan browser untuk web admin dan Postman/Insomnia atau HTTP client untuk API.

---

## 2. Pengujian Otentikasi Web Admin

### 2.1 Login Admin

- [ ] Buka `http://127.0.0.1:8000/login`
- [ ] Masukkan email admin
- [ ] Masukkan password admin
- [ ] Klik tombol login
- [ ] Pastikan masuk ke dashboard Admin
- [ ] Pastikan akses ke menu `Rooms`, `Equipment`, `Borrowings`, `Users`, `Check-ins` tersedia

### 2.2 Register (jika ada)

- [ ] Buka `http://127.0.0.1:8000/register`
- [ ] Daftarkan akun baru
- [ ] Pastikan akun baru dapat login
- [ ] Pastikan akun default memiliki role `member`

### 2.3 Profile Update

- [ ] Buka halaman profile
- [ ] Ubah nama dan email
- [ ] Simpan perubahan
- [ ] Pastikan data tersimpan dan ditampilkan kembali dengan benar

---

## 3. Pengujian Dashboard Admin

### 3.1 Tampilan Dashboard

- [ ] Pastikan tampilan dashboard terbuka tanpa error
- [ ] Pastikan ada kartu statistik: Total Ruang, Total Peralatan, Peminjaman Aktif, Total Pengguna
- [ ] Pastikan ada bagian aktivitas terbaru
- [ ] Pastikan ada quick action button seperti `Tambah Ruang`, `Tambah Peralatan`, `Buat Peminjaman`, `Check-in`

### 3.2 Konten Dinamis

- [ ] Pastikan angka statistik sesuai dengan data pada tabel database
- [ ] Pastikan recent activity menampilkan peminjaman terbaru
- [ ] Pastikan quick action menavigasi ke form yang benar

---

## 4. Pengujian Room Management

### 4.1 List Room

- [ ] Buka halaman `Rooms`
- [ ] Pastikan daftar ruang tampil
- [ ] Pastikan tabel dan kartu mobile responsif
- [ ] Pastikan informasi nama, kapasitas, lokasi, dan status tampil

### 4.2 Tambah Room

- [ ] Klik tombol `Tambah Ruang`
- [ ] Isi form `name`, `capacity`, `location`, `status`, `description`
- [ ] Submit form
- [ ] Pastikan ruang baru muncul di daftar
- [ ] Pastikan notifikasi berhasil tampil

### 4.3 Edit Room

- [ ] Klik tombol `Edit` pada satu ruang
- [ ] Ubah beberapa field dan submit
- [ ] Pastikan perubahan tersimpan dan tampil benar di daftar

### 4.4 Hapus Room

- [ ] Klik tombol `Hapus`
- [ ] Konfirmasi penghapusan
- [ ] Pastikan ruang hilang dari daftar
- [ ] Pastikan tidak ada error

---

## 5. Pengujian Equipment Management

### 5.1 List Equipment

- [ ] Buka halaman `Equipment`
- [ ] Pastikan daftar peralatan tampil
- [ ] Pastikan status, stock, kondisi, dan kategori ditampilkan
- [ ] Pastikan responsif di mobile dan desktop

### 5.2 Tambah Equipment

- [ ] Klik `Tambah Peralatan`
- [ ] Isi form lengkap dengan `name`, `category`, `stock`, `condition`, `status`, `description`
- [ ] Upload gambar (jika tersedia)
- [ ] Submit form
- [ ] Pastikan data peralatan baru muncul
- [ ] Pastikan gambar tampil jika ada

### 5.3 Edit Equipment

- [ ] Klik `Edit` pada peralatan
- [ ] Ubah field dan submit
- [ ] Pastikan perubahan tampil benar

### 5.4 Hapus Equipment

- [ ] Klik `Hapus`
- [ ] Konfirmasi penghapusan
- [ ] Pastikan peralatan hilang dari daftar

---

## 6. Pengujian Borrowing Management

### 6.1 List Borrowings

- [ ] Buka halaman `Borrowings`
- [ ] Pastikan daftar peminjaman tampil
- [ ] Pastikan status dan user peminjam sudah benar

### 6.2 Buat Peminjaman

- [ ] Klik `Tambah Peminjaman`
- [ ] Pilih `Room` atau `Equipment`
- [ ] Isi `start_date` dan `end_date`
- [ ] Tambahkan `notes` jika perlu
- [ ] Submit
- [ ] Pastikan peminjaman baru tampil dengan status `pending`

### 6.3 Approve Borrowing

- [ ] Klik tombol `Approve` pada peminjaman `pending`
- [ ] Pastikan status berubah menjadi `approved`

### 6.4 Cancel Borrowing

- [ ] Klik tombol `Cancel` pada peminjaman yang belum selesai
- [ ] Pastikan status berubah menjadi `cancelled`

### 6.5 Hapus Borrowing

- [ ] Klik `Hapus`
- [ ] Konfirmasi
- [ ] Pastikan peminjaman hilang dari daftar

---

## 7. Pengujian User Management

### 7.1 List User

- [ ] Buka halaman `Users`
- [ ] Pastikan daftar user tampil
- [ ] Pastikan role user tampil

### 7.2 Tambah User

- [ ] Klik `Tambah User`
- [ ] Isi nama, email, password, konfirmasi password, role
- [ ] Submit form
- [ ] Pastikan user baru muncul di daftar

### 7.3 Edit User

- [ ] Klik `Edit` pada user
- [ ] Ubah nama, email, role, atau password
- [ ] Submit dan pastikan data berubah

### 7.4 Hapus User

- [ ] Klik `Hapus`
- [ ] Pastikan user dihapus tanpa error

---

## 8. Pengujian Check-in Management

### 8.1 List Check-ins

- [ ] Buka halaman `Check-ins`
- [ ] Pastikan daftar check-in tampil
- [ ] Pastikan status, user, dan equipment tampil

### 8.2 Buat Check-in

- [ ] Klik `Tambah Check-in`
- [ ] Pilih peminjaman yang approved
- [ ] Pilih equipment dari peminjaman tersebut
- [ ] Submit
- [ ] Pastikan check-in tersimpan

### 8.3 Check-out / Update Status

- [ ] Klik update status untuk check-out
- [ ] Pastikan status berubah dari `checked_in` ke `checked_out`

---

## 9. Pengujian REST API Tablet

### 9.1 Login API

- [ ] Request `POST /api/login`
- [ ] Body:
  - `email`
  - `password`
- [ ] Pastikan respons berisi `token`
- [ ] Simpan `Authorization: Bearer {token}` untuk request berikut

### 9.2 Register API

- [ ] Request `POST /api/register`
- [ ] Body:
  - `name`
  - `email`
  - `password`
  - `password_confirmation`
- [ ] Pastikan mendapat `token` dan data user

### 9.3 Authenticated User

- [ ] Request `GET /api/me` dengan header `Authorization`
- [ ] Pastikan data user dikembalikan

### 9.4 Room API

- [ ] Request `GET /api/rooms`
- [ ] Pastikan data ruang tampil
- [ ] Request `GET /api/rooms/{id}` dengan id valid
- [ ] Pastikan detail ruang tampil

### 9.5 Equipment API

- [ ] Request `GET /api/equipment`
- [ ] Pastikan data equipment tampil
- [ ] Request `GET /api/equipment/{id}`
- [ ] Pastikan detail equipment tampil

### 9.6 Borrowing API

- [ ] Request `GET /api/borrowings`
- [ ] Pastikan daftar peminjaman user sendiri (jika member) atau semua (jika admin)
- [ ] Request `POST /api/borrowings` dengan body:
  - `room_id` atau `equipment` array
  - `start_date`
  - `end_date`
  - `notes`
- [ ] Pastikan peminjaman tersimpan dan status `pending`
- [ ] Request `PATCH /api/borrowings/{id}/cancel`
- [ ] Pastikan status berubah menjadi `cancelled`
- [ ] Jika admin, request `PATCH /api/borrowings/{id}/approve`
- [ ] Pastikan status berubah menjadi `approved`

### 9.7 Check-in API

- [ ] Request `GET /api/check-ins`
- [ ] Pastikan daftar check-in tampil
- [ ] Request `POST /api/check-ins` dengan body:
  - `borrowing_id`
  - `equipment_id`
  - `notes`
- [ ] Pastikan respons status `201` dan data check-in
- [ ] Request `PATCH /api/check-ins/{id}/out`
- [ ] Pastikan `status` berubah menjadi `checked_out`

### 9.8 Token dan Error Handling

- [ ] Pastikan request API tanpa token mengembalikan 401
- [ ] Pastikan token invalid mengembalikan 401
- [ ] Pastikan request validasi yang salah mengembalikan 422

---

## 10. Pengujian Responsif & UX

- [ ] Cek tampilan di desktop
- [ ] Cek tampilan di mobile (chrome devtools)
- [ ] Pastikan tabel dan kartu tampil rapi di ukuran kecil
- [ ] Pastikan semua tombol action mudah diklik
- [ ] Pastikan pesan sukses dan error tampil jelas

---

## 11. Ringkasan Checklist

- [ ] Persiapan pengujian
- [ ] Login admin
- [ ] Register user
- [ ] Profile update
- [ ] Dashboard tampil
- [ ] Room CRUD
- [ ] Equipment CRUD
- [ ] Borrowing CRUD
- [ ] User CRUD
- [ ] Check-in CRUD
- [ ] API login/register
- [ ] API room endpoints
- [ ] API equipment endpoints
- [ ] API borrowing endpoints
- [ ] API check-in endpoints
- [ ] Token / error handling
- [ ] Responsif mobile

---

## 12. Catatan Tambahan

- Jika ada error validation, cek field yang wajib dan format tanggal.
- Gunakan `Bearer` token di header Authorization untuk semua request API berbayar.
- Untuk pengujian API, gunakan JSON body dan pastikan `Content-Type: application/json`.
- Jika ingin uji cepat, jalankan `php artisan tinker` untuk memeriksa data model langsung.

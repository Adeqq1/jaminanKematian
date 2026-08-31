Sistem Informasi Pelayanan Data Kepesertaan JKN Berbasis Website di BPJS Ketenaga kerjaan Kabupaten Bungo

## DBMS

Sistem menggunakan **MySQL** sebagai DBMS dengan konfigurasi koneksi melalui environment Laravel:

- Host: `127.0.0.1`
- Port: `3306`
- Database: `jkn`
- Charset: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`

### Rancangan tabel inti

#### `peserta`

- `id` (BIGINT, primary key)
- `nama` (VARCHAR)
- `no_hp` (VARCHAR)
- `nik` (VARCHAR, unique)
- `foto_ktp` (VARCHAR, nullable)
- `created_at`, `updated_at`

#### `pengajuan_klaim`

- `id` (BIGINT, primary key)
- `peserta_id` (BIGINT, foreign key ke `peserta.id`)
- `nama_ahli_waris` (VARCHAR)
- `no_hp_peserta` (VARCHAR)
- `no_hp_ahli_waris` (VARCHAR)
- `nik_peserta` (VARCHAR)
- `akta_kematian` (VARCHAR)
- `kartu_bpjs` (VARCHAR)
- `keterangan_ahli_waris` (TEXT)
- `nomor_rekening_ahli_waris` (VARCHAR)
- `buku_rekening` (VARCHAR)
- `created_at`, `updated_at`

#### `nomor_antrian`

- `id` (BIGINT, primary key)
- `pengajuan_klaim_id` (BIGINT, foreign key ke `pengajuan_klaim.id`)
- `nomor_urut` (INT)
- `jenis_pelayanan` (VARCHAR)
- `nomor_loket` (VARCHAR)
- `waktu_pengambilan` (DATETIME)
- `created_at`, `updated_at`

Dokumen dan foto disimpan pada filesystem aplikasi; MySQL menyimpan lokasi file dan metadata pengajuan.

role :

- Peserta
- admin

attribute peserta :

- Nama
- No HP
- NIK
- Foto KTP

activity
peserta -> halaman pendaftaran -> sudah daftar -> dashboard

halaman pendaftaran

- Nama
- No HP
- NIK
- Foto KTP

Dashboard

- ambil nomor antrian Klaim Jaminan Kematian
- isi beberapa informasi sebelum ambil nomor antrian. Nama Peserta & ahli waris, no hp peserta & ahli waris, NIK peserta, Akta Kematian, Kartu BPJS, Keterangan Ahli Waris, Nomor Rekening Ahli waris, Buku Rekening.
- menampilkan nomor antrian, yang berisikan nomor urut, jenis pelayanan, nomor loket, waktu pengambilan.
- menampilkan output berupa pdf atas informasi yang telah ditambahkan pada saat pengambilan nomor oleh peserta

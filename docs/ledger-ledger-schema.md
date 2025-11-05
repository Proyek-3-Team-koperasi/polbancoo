# Rancangan Ledger Umum

Dokumen ini merangkum skema basis data untuk modul buku besar (ledger) yang akan mengonsolidasikan transaksi simpanan, penjualan, dan pembiayaan koperasi.

---

## Tujuan Utama
- Menyediakan struktur fleksibel untuk mencatat jurnal debit/kredit.
- Memungkinkan pelacakan keterkaitan jurnal dengan transaksi sumber (simpanan, pesanan, cicilan).
- Menjaga integritas data keuangan (total debit = total kredit per jurnal, periode penutupan, dsb).

---

## Diagram Tingkat Tinggi

```
ledger_accounts 1 ──< ledger_lines >── 1 ledger_journals
                                    └──(optional) member_user_id → members.user_id
ledger_journals ──(reference)──> transaksi sumber (savings_transactions, orders, installment_bills, ...)
```

---

## Rincian Tabel

### 1. `ledger_accounts`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint, PK | Identitas akun |
| `code` | string(20), unik | Kode akun (001-xxx) |
| `name` | string(100) | Nama akun |
| `type` | enum (`Asset`,`Liability`,`Equity`,`Revenue`,`Expense`) | Klasifikasi utama |
| `normal_balance` | enum (`Debit`,`Credit`) | Penanda saldo normal |
| `parent_id` | bigint, nullable, FK → `ledger_accounts.id` | Hierarki akun (opsional) |
| `group_id` | bigint, nullable, FK → `ledger_account_groups.id` | Klasifikasi laporan (opsional) |
| `is_active` | boolean, default true | Status penggunaan |
| `created_at` / `updated_at` | timestamp | Audit bawaan Laravel |

### 2. `ledger_journals`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint, PK | Identitas jurnal |
| `journal_number` | string(30), unik | Nomor bukti jurnal |
| `journal_date` | date | Tanggal jurnal |
| `description` | text, nullable | Deskripsi umum |
| `reference_type` | string, nullable | Nama tabel sumber (`savings_transactions`, `orders`, dll) |
| `reference_id` | bigint, nullable | ID transaksi sumber |
| `created_by` | bigint, FK → `users.id` | User pembuat jurnal |
| `posted_at` | timestamp, nullable | Waktu jurnal diposting |
| `created_at` / `updated_at` | timestamp | Audit bawaan Laravel |

> Index yang disarankan: `(reference_type, reference_id)` untuk lookup cepat dari transaksi sumber.

### 3. `ledger_lines`
| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint, PK | Identitas baris jurnal |
| `journal_id` | bigint, FK → `ledger_journals.id` | Header jurnal |
| `account_id` | bigint, FK → `ledger_accounts.id` | Akun yang terkena |
| `member_user_id` | bigint, nullable, FK → `members.user_id` | Mengikat ke anggota (opsional) |
| `debit` | bigint, default 0 | Nilai debit dalam rupiah |
| `credit` | bigint, default 0 | Nilai kredit dalam rupiah |
| `memo` | string(150), nullable | Catatan baris |
| `created_at` / `updated_at` | timestamp | Audit bawaan Laravel |

> Validasi penting: `debit >= 0`, `credit >= 0`, dan total debit = total kredit per `journal_id` sebelum `posted_at` diisi.

---

## Integrasi dengan Modul Eksisting

| Tabel Sumber | Penyesuaian | Tujuan |
| --- | --- | --- |
| `savings_transactions` | Tambah `ledger_journal_id` (FK nullable) | Menandai transaksi simpanan yang sudah dibukukan |
| `orders` | Tambah `ledger_journal_id` | Pencatatan penjualan tunai/kredit |
| `installment_bills` | Tambah `ledger_journal_id` | Menautkan tagihan cicilan |

> Kolom FK ini bisa diisi setelah proses posting jurnal selesai; gunakan constraint `nullOnDelete()` agar histori tetap konsisten.

---

## Tabel Opsional

### `ledger_account_groups`
Gunakan bila perlu struktur multi-level di laporan.

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint, PK | Identitas grup |
| `name` | string(100) | Nama grup |
| `code` | string(20), unik | Kode grup |
| `parent_id` | bigint, nullable | Hierarki grup |
| `created_at` / `updated_at` | timestamp | Audit |

Relasi ke `ledger_accounts.group_id` bersifat opsional, namun sudah tersedia di skema untuk mempermudah agregasi laporan.

### `ledger_closings`
Menandai periode yang sudah ditutup agar tidak ada jurnal baru/ubah.

| Kolom | Tipe |
| --- | --- |
| `id` | bigint, PK |
| `period_start` | date |
| `period_end` | date |
| `closed_by` | bigint, FK → `users.id` |
| `closed_at` | timestamp |
| `notes` | text, nullable |

### `ledger_balances` (cache saldo per periode)

| Kolom | Tipe |
| --- | --- |
| `id` | bigint, PK |
| `account_id` | bigint, FK |
| `period` | string(7) (format `YYYY-MM`) |
| `opening_balance` | bigint |
| `debit_total` / `credit_total` | bigint |
| `closing_balance` | bigint |

Unique key `(account_id, period)` untuk menjaga satu rekaman per akun per periode.

---

## Proses Bisnis Utama
- **Penyusunan Jurnal**: Modul aplikasi membuat satu `ledger_journals` dengan ≥2 `ledger_lines` untuk menyeimbangkan debit/kredit.
- **Posting**: Setelah divalidasi, set `posted_at`. Bisa tambahkan middleware agar jurnal dengan `posted_at` tidak bisa diedit.
- **Pelacakan Sumber**: Kolom `reference_type`/`reference_id` menjaga keterhubungan ke transaksi awal, memudahkan drill-down laporan.
- **Penutupan Periode**: Buat entri di `ledger_closings` dan blokir jurnal baru jika tanggalnya berada dalam rentang tertutup.

---

## Catatan Implementasi
- Gunakan migration terpisah per tabel agar mudah dikelola.
- Pertimbangkan seeding awal untuk daftar akun dasar (kas, piutang, margin penjualan).
- Simpan seluruh nominal dalam satuan rupiah (integer) sehingga presisi terjaga tanpa desimal.
- Tambahkan test kasus untuk memastikan constraint debit/kredit terpenuhi.
- Siapkan repository pattern/service layer untuk konversi transaksi → jurnal agar mudah diuji dan di-maintain.

---

## DBML (dbdiagram.io)

```dbml
Table ledger_account_groups {
  id bigint [pk]
  name varchar(100)
  code varchar(20) [unique]
  parent_id bigint [ref: > ledger_account_groups.id]
  created_at timestamp
  updated_at timestamp
}

Table ledger_accounts {
  id bigint [pk]
  code varchar(20) [unique]
  name varchar(100)
  type varchar(20)
  normal_balance varchar(10)
  parent_id bigint [ref: > ledger_accounts.id]
  group_id bigint [ref: > ledger_account_groups.id]
  is_active boolean
  created_at timestamp
  updated_at timestamp
}

Table ledger_journals {
  id bigint [pk]
  journal_number varchar(30) [unique]
  journal_date date
  description text
  reference_type varchar(255)
  reference_id bigint
  created_by bigint [ref: > users.id]
  posted_at timestamp
  created_at timestamp
  updated_at timestamp
}

Table ledger_lines {
  id bigint [pk]
  journal_id bigint [ref: > ledger_journals.id]
  account_id bigint [ref: > ledger_accounts.id]
  member_user_id bigint [ref: > members.user_id]
  debit bigint
  credit bigint
  memo varchar(150)
  created_at timestamp
  updated_at timestamp
}

Table ledger_closings {
  id bigint [pk]
  period_start date
  period_end date
  closed_by bigint [ref: > users.id]
  closed_at timestamp
  notes text
  created_at timestamp
  updated_at timestamp
}

Table ledger_balances {
  id bigint [pk]
  account_id bigint [ref: > ledger_accounts.id]
  period varchar(7)
  opening_balance bigint
  debit_total bigint
  credit_total bigint
  closing_balance bigint
  created_at timestamp
  updated_at timestamp
}
```

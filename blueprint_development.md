# 📘 Blueprint Development — Smart-Hub Management System
> Laravel 13 · MySQL · REST API

---

## 🗺️ Gambaran Besar Sistem

Smart-Hub Management System melayani **dua jenis pengguna**:

| Pengguna | Akses | Media |
|----------|-------|-------|
| **Admin** | Dashboard Web (CRUD penuh) | Browser |
| **Member** | Check-in peralatan, lihat inventaris | Aplikasi Tablet via REST API |

---

## 🏗️ Arsitektur Sistem

```
smart-hub/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Web/          ← Controller untuk Dashboard Admin
│   │   │   └── Api/          ← Controller untuk REST API Tablet
│   │   ├── Middleware/
│   │   └── Requests/         ← Form Request Validation
│   ├── Models/               ← Eloquent Models
│   └── Services/             ← Business Logic (opsional, nilai plus)
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/          ← Blade Templates (Dashboard Admin)
└── routes/
    ├── web.php               ← Route Dashboard
    └── api.php               ← Route REST API
```

---

## 📦 PHASE 0 — Persiapan & Setup (Hari 1)

### 0.1 Install Laravel Breeze (Auth Starter)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade   # pilih blade untuk web admin
npm install && npm run build
php artisan migrate
```
- [x] Breeze terpasang
- [x] Scaffolding Blade auth dibuat
- [x] Front-end dependencies diinstall dan build berhasil
- [x] Migrasi awal dijalankan

### 0.2 Install Laravel Sanctum (API Token Auth)
> Sanctum sudah termasuk dalam Laravel 11+/12+/13 secara default.
> Cukup pastikan sudah ter-publish config-nya:
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```
- [x] Sanctum config dipublish ke `config/sanctum.php`
- [x] Migrasi token API berhasil dijalankan
---

## 🗄️ PHASE 1 — Database Design & Migration (Hari 1–2)

### Skema Relasi Antar Tabel (ERD)

```
users ─────────────────────────────────────────────┐
  │ (id, name, email, role, password)               │
  │                                                 │
  │ hasMany                                         │ hasMany
  ▼                                                 ▼
borrowings ──────────────── borrowing_equipment   check_ins
  │ (id, user_id,            (borrowing_id,         (id, equipment_id,
  │  room_id,                 equipment_id,           user_id,
  │  start_date,              quantity)               checked_in_at,
  │  end_date,                                        status)
  │  status,
  │  notes)
  │ belongsTo
  ▼
rooms                      equipment
  (id, name,                 (id, name,
   capacity,                  category,
   location,                  stock,
   status,                    condition,
   description)               description,
                              status)
```

### Daftar Migration (urutan pembuatan)

#### Migration 1 — Tambah kolom `role` ke tabel `users`
```bash
php artisan make:migration add_role_to_users_table --table=users
```
```php
// File: add_role_to_users_table
Schema::table('users', function (Blueprint $table) {
    $table->enum('role', ['admin', 'member'])->default('member')->after('email');
});
```

---

#### Migration 2 — Tabel `rooms` (Ruang Kerja)
```bash
php artisan make:migration create_rooms_table
```
```php
Schema::create('rooms', function (Blueprint $table) {
    $table->id();
    $table->string('name');                                   // Nama ruang
    $table->integer('capacity');                              // Kapasitas orang
    $table->string('location')->nullable();                   // Lokasi/lantai
    $table->enum('status', ['available', 'maintenance'])
          ->default('available');
    $table->text('description')->nullable();
    $table->timestamps();
    $table->softDeletes();                                    // Soft delete
});
```

---

#### Migration 3 — Tabel `equipment` (Peralatan)
```bash
php artisan make:migration create_equipment_table
```
```php
Schema::create('equipment', function (Blueprint $table) {
    $table->id();
    $table->string('name');                                   // Nama peralatan
    $table->string('category');                               // Kamera, Tripod, dll
    $table->integer('stock')->default(1);                     // Jumlah stok
    $table->enum('condition', ['good', 'damaged', 'lost'])
          ->default('good');
    $table->enum('status', ['available', 'borrowed'])
          ->default('available');
    $table->text('description')->nullable();
    $table->string('image')->nullable();                      // Foto peralatan
    $table->timestamps();
    $table->softDeletes();
});
```

---

#### Migration 4 — Tabel `borrowings` (Jadwal Peminjaman)
```bash
php artisan make:migration create_borrowings_table
```
```php
Schema::create('borrowings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
    $table->dateTime('start_date');
    $table->dateTime('end_date');
    $table->enum('status', ['pending', 'approved', 'active', 'completed', 'cancelled'])
          ->default('pending');
    $table->text('notes')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

---

#### Migration 5 — Tabel Pivot `borrowing_equipment`
```bash
php artisan make:migration create_borrowing_equipment_table
```
```php
Schema::create('borrowing_equipment', function (Blueprint $table) {
    $table->id();
    $table->foreignId('borrowing_id')->constrained()->onDelete('cascade');
    $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
    $table->integer('quantity')->default(1);
    $table->timestamps();
});
```

---

#### Migration 6 — Tabel `check_ins`
```bash
php artisan make:migration create_check_ins_table
```
```php
Schema::create('check_ins', function (Blueprint $table) {
    $table->id();
    $table->foreignId('borrowing_id')->constrained()->onDelete('cascade');
    $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->enum('status', ['checked_in', 'checked_out'])->default('checked_in');
    $table->timestamp('checked_in_at')->nullable();
    $table->timestamp('checked_out_at')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});
```

---

### Jalankan Semua Migration
```bash
php artisan migrate
```
- [x] Semua migration domain utama sudah dibuat dan dijalankan

---

## 🤖 PHASE 2 — Eloquent Models & Relasi (Hari 2)
- [x] Model User, Room, Equipment, Borrowing, dan CheckIn dibuat dan direlasikan

### Model: `User`
```bash
# Model sudah ada dari Breeze, tinggal tambahkan relasi
```
```php
// app/Models/User.php
protected $fillable = ['name', 'email', 'password', 'role'];

// Relasi
public function borrowings(): HasMany
{
    return $this->hasMany(Borrowing::class);
}

public function checkIns(): HasMany
{
    return $this->hasMany(CheckIn::class);
}

// Helper
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

---

### Model: `Room`
```bash
php artisan make:model Room
```
```php
// app/Models/Room.php
protected $fillable = ['name', 'capacity', 'location', 'status', 'description'];
use SoftDeletes;

public function borrowings(): HasMany
{
    return $this->hasMany(Borrowing::class);
}
```

---

### Model: `Equipment`
```bash
php artisan make:model Equipment
```
```php
// app/Models/Equipment.php
protected $fillable = ['name', 'category', 'stock', 'condition', 'status', 'description', 'image'];
use SoftDeletes;

public function borrowings(): BelongsToMany
{
    return $this->belongsToMany(Borrowing::class, 'borrowing_equipment')
                ->withPivot('quantity')
                ->withTimestamps();
}

public function checkIns(): HasMany
{
    return $this->hasMany(CheckIn::class);
}
```

---

### Model: `Borrowing`
```bash
php artisan make:model Borrowing
```
```php
// app/Models/Borrowing.php
protected $fillable = ['user_id', 'room_id', 'start_date', 'end_date', 'status', 'notes'];
use SoftDeletes;

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

public function room(): BelongsTo
{
    return $this->belongsTo(Room::class);
}

public function equipment(): BelongsToMany
{
    return $this->belongsToMany(Equipment::class, 'borrowing_equipment')
                ->withPivot('quantity')
                ->withTimestamps();
}

public function checkIns(): HasMany
{
    return $this->hasMany(CheckIn::class);
}
```

---

### Model: `CheckIn`
```bash
php artisan make:model CheckIn
```
```php
// app/Models/CheckIn.php
protected $fillable = ['borrowing_id', 'equipment_id', 'user_id', 'status', 'checked_in_at', 'checked_out_at', 'notes'];

public function borrowing(): BelongsTo
{
    return $this->belongsTo(Borrowing::class);
}

public function equipment(): BelongsTo
{
    return $this->belongsTo(Equipment::class);
}

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

---

## 🌱 PHASE 3 — Seeder & Factory (Hari 2)
- [x] Seeder dan factory dibuat untuk User, Room, dan Equipment
- [x] Data admin dan member di-seed
- [x] `php artisan db:seed` berhasil dijalankan

```bash
php artisan make:seeder DatabaseSeeder
php artisan make:seeder UserSeeder
php artisan make:seeder RoomSeeder
php artisan make:seeder EquipmentSeeder
```

```php
// UserSeeder — buat 1 admin + beberapa member
User::create([
    'name'     => 'Admin SmartHub',
    'email'    => 'admin@smarthub.com',
    'password' => bcrypt('password'),
    'role'     => 'admin',
]);
```

```bash
php artisan db:seed
```
- [x] Seeder database berjalan dan data awal terisi

---

## 🌐 PHASE 4 — Web Dashboard Admin (Hari 3–4)

### Buat Resource Controllers (Web)

```bash
php artisan make:controller Web/RoomController --resource --model=Room
php artisan make:controller Web/EquipmentController --resource --model=Equipment
php artisan make:controller Web/BorrowingController --resource --model=Borrowing
php artisan make:controller Web/UserController --resource --model=User
php artisan make:controller Web/CheckInController --resource --model=CheckIn
```
- [x] Semua resource controllers Web dibuat (Room, Equipment, Borrowing, User, CheckIn)

### Middleware Admin

```bash
php artisan make:middleware EnsureIsAdmin
```
- [x] Middleware `EnsureIsAdmin` dibuat dan diregister di `bootstrap/app.php`

### Routes Web (`routes/web.php`)

```php
// Routes yang butuh login
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin only
    Route::middleware('admin')->group(function () {
        Route::resource('rooms', Web\RoomController::class);
        Route::resource('equipment', Web\EquipmentController::class);
        Route::resource('borrowings', Web\BorrowingController::class);
        Route::resource('users', Web\UserController::class);
        Route::resource('check-ins', Web\CheckInController::class);

        // Aksi khusus
        Route::patch('borrowings/{borrowing}/approve', [Web\BorrowingController::class, 'approve'])->name('borrowings.approve');
        Route::patch('borrowings/{borrowing}/cancel',  [Web\BorrowingController::class, 'cancel'])->name('borrowings.cancel');
    });

    // Member
    Route::get('my-borrowings', [Web\BorrowingController::class, 'myBorrowings'])->name('borrowings.mine');
});
```
- [x] Routes Web diperbarui dengan semua resource routes dan middleware admin

### Daftar CRUD Web

| Entitas | Index | Create | Edit | Delete | Aksi Tambahan |
|---------|-------|--------|------|--------|---------------|
| Room | ✅ | ✅ | ✅ | ✅ (soft) | — |
| Equipment | ✅ | ✅ | ✅ | ✅ (soft) | Upload foto |
| Borrowing | ✅ | ✅ | ✅ | ✅ (soft) | Approve / Cancel |
| User | ✅ | ✅ | ✅ | ✅ | Set role |
| CheckIn | ✅ | ✅ | ✅ | ✅ | — |

### Form Request Validation

```bash
php artisan make:request StoreEquipmentRequest
php artisan make:request StoreRoomRequest
php artisan make:request StoreBorrowingRequest
```

```php
// StoreEquipmentRequest
public function rules(): array
{
    return [
        'name'        => 'required|string|max:255',
        'category'    => 'required|string',
        'stock'       => 'required|integer|min:0',
        'condition'   => 'required|in:good,damaged,lost',
        'status'      => 'required|in:available,borrowed',
        'image'       => 'nullable|image|max:2048',
        'description' => 'nullable|string',
    ];
}
```
- [x] Form request validation dibuat dan diisi rules untuk Equipment, Room, dan Borrowing

### UI Dashboard Admin
- [x] Dashboard dengan statistik lengkap (total ruang, peralatan, peminjaman aktif, pengguna)
- [x] Menu admin dropdown di navigation bar
- [x] Recent activities dan role-based content
- [x] Responsive design dengan Tailwind CSS
- [x] Semua views CRUD lengkap (index, create, edit, show)
- [x] Form validation dengan error display
- [x] Success/error flash messages
- [x] Image upload untuk equipment

---

## 📡 PHASE 5 — REST API untuk Aplikasi Tablet (Hari 4–5)

### Buat API Controllers

```bash
php artisan make:controller Api/AuthController
php artisan make:controller Api/EquipmentController --model=Equipment
php artisan make:controller Api/BorrowingController --model=Borrowing
php artisan make:controller Api/CheckInController --model=CheckIn
php artisan make:controller Api/RoomController --model=Room
```

### API Resources (format JSON)

```bash
php artisan make:resource EquipmentResource
php artisan make:resource BorrowingResource
php artisan make:resource CheckInResource
php artisan make:resource RoomResource
php artisan make:resource UserResource
```

### Routes API (`routes/api.php`)

```php
// routes/api.php

// ── Public Routes (tanpa token) ──────────────────────────────
Route::post('/login',    [Api\AuthController::class, 'login']);
Route::post('/register', [Api\AuthController::class, 'register']);

// ── Protected Routes (butuh Sanctum token) ───────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [Api\AuthController::class, 'logout']);
    Route::get('/me',      [Api\AuthController::class, 'me']);

    // Inventaris (read only untuk member)
    Route::get('/equipment',       [Api\EquipmentController::class, 'index']);
    Route::get('/equipment/{id}',  [Api\EquipmentController::class, 'show']);
    Route::get('/rooms',           [Api\RoomController::class, 'index']);
    Route::get('/rooms/{id}',      [Api\RoomController::class, 'show']);

    // Peminjaman
    Route::get('/borrowings',            [Api\BorrowingController::class, 'index']);
    Route::post('/borrowings',           [Api\BorrowingController::class, 'store']);
    Route::get('/borrowings/{id}',       [Api\BorrowingController::class, 'show']);
    Route::patch('/borrowings/{id}/cancel', [Api\BorrowingController::class, 'cancel']);

    // Check-in (fitur utama tablet)
    Route::get('/check-ins',             [Api\CheckInController::class, 'index']);
    Route::post('/check-ins',            [Api\CheckInController::class, 'store']);    // check-in
    Route::patch('/check-ins/{id}/out',  [Api\CheckInController::class, 'checkOut']); // check-out

    // Admin only
    Route::middleware('admin')->group(function () {
        Route::apiResource('/equipment', Api\EquipmentController::class)->except(['index', 'show']);
        Route::apiResource('/rooms',     Api\RoomController::class)->except(['index', 'show']);
        Route::patch('/borrowings/{id}/approve', [Api\BorrowingController::class, 'approve']);
    });
});
```

### Daftar Endpoint API Lengkap

| Method | Endpoint | Auth | Akses | Deskripsi |
|--------|----------|------|-------|-----------|
| POST | `/api/login` | ❌ | Semua | Login, dapat token |
| POST | `/api/register` | ❌ | Semua | Daftar akun member |
| POST | `/api/logout` | ✅ | Semua | Logout, hapus token |
| GET | `/api/me` | ✅ | Semua | Info user login |
| GET | `/api/equipment` | ✅ | Semua | List semua peralatan |
| GET | `/api/equipment/{id}` | ✅ | Semua | Detail 1 peralatan |
| POST | `/api/equipment` | ✅ | Admin | Tambah peralatan |
| PUT | `/api/equipment/{id}` | ✅ | Admin | Edit peralatan |
| DELETE | `/api/equipment/{id}` | ✅ | Admin | Hapus peralatan |
| GET | `/api/rooms` | ✅ | Semua | List semua ruang |
| GET | `/api/rooms/{id}` | ✅ | Semua | Detail 1 ruang |
| POST | `/api/rooms` | ✅ | Admin | Tambah ruang |
| PUT | `/api/rooms/{id}` | ✅ | Admin | Edit ruang |
| DELETE | `/api/rooms/{id}` | ✅ | Admin | Hapus ruang |
| GET | `/api/borrowings` | ✅ | Semua | List peminjaman user |
| POST | `/api/borrowings` | ✅ | Member | Buat peminjaman baru |
| GET | `/api/borrowings/{id}` | ✅ | Semua | Detail peminjaman |
| PATCH | `/api/borrowings/{id}/approve` | ✅ | Admin | Setujui peminjaman |
| PATCH | `/api/borrowings/{id}/cancel` | ✅ | Semua | Batalkan peminjaman |
| GET | `/api/check-ins` | ✅ | Semua | Riwayat check-in |
| POST | `/api/check-ins` | ✅ | Member | Check-in peralatan |
| PATCH | `/api/check-ins/{id}/out` | ✅ | Member | Check-out peralatan |

### Contoh Response JSON

```json
// GET /api/equipment — 200 OK
{
  "success": true,
  "message": "Data peralatan berhasil diambil",
  "data": [
    {
      "id": 1,
      "name": "Kamera Sony A7III",
      "category": "Kamera",
      "stock": 3,
      "condition": "good",
      "status": "available",
      "description": "Kamera mirrorless full-frame"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 10
  }
}

// POST /api/check-ins — 201 Created
{
  "success": true,
  "message": "Check-in berhasil",
  "data": {
    "id": 5,
    "borrowing_id": 3,
    "equipment": "Kamera Sony A7III",
    "status": "checked_in",
    "checked_in_at": "2026-05-16T09:30:00.000000Z"
  }
}

// 401 Unauthorized
{
  "success": false,
  "message": "Unauthenticated. Token tidak valid atau tidak ada."
}
```

### Implementasi Login API

```php
// Api/AuthController.php — method login()
public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah.',
        ], 401);
    }

    $user  = Auth::user();
    $token = $user->createToken('tablet-app-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'data'    => [
            'user'  => new UserResource($user),
            'token' => $token,
        ],
    ]);
}
```

---

## 🌿 PHASE 6 — Git Strategy (Sesuai Soal) (Sepanjang Development)

### Struktur Branch

```
main (production-ready)
├── develop          ← branch utama development
│   ├── feature/room-crud
│   ├── feature/equipment-crud
│   ├── feature/borrowing-crud
│   ├── feature/api-auth
│   ├── feature/api-equipment
│   ├── feature/api-checkin
│   └── feature/email-notification   ← dikerjakan paralel (sesuai soal)
```

### Workflow Harian

```bash
# 1. Mulai fitur baru
git checkout develop
git pull origin develop
git checkout -b feature/equipment-crud

# 2. Kerjakan fitur, lalu commit
git add .
git commit -m "feat: add equipment model and migration"
git commit -m "feat: add EquipmentController with CRUD"
git commit -m "test: verify equipment CRUD endpoints"

# 3. Selesai — merge ke develop
git checkout develop
git merge feature/equipment-crud
git push origin develop

# 4. Hapus branch fitur (opsional)
git branch -d feature/equipment-crud
```

### Konvensi Commit Message

| Prefix | Kapan Dipakai |
|--------|--------------|
| `feat:` | Tambah fitur baru |
| `fix:` | Memperbaiki bug |
| `chore:` | Setup, konfigurasi |
| `refactor:` | Refactor kode |
| `test:` | Tambah/ubah testing |
| `docs:` | Update dokumentasi |

---

## ✅ PHASE 7 — Testing & Review (Hari 5–6)

### Testing API dengan Postman / Insomnia

**Urutan testing:**
1. POST `/api/register` → daftar akun member
2. POST `/api/login` → simpan token dari response
3. GET `/api/equipment` → masukkan token di Header: `Authorization: Bearer {token}`
4. POST `/api/borrowings` → buat peminjaman
5. POST `/api/check-ins` → simulasi check-in dari tablet
6. PATCH `/api/check-ins/{id}/out` → check-out

### Checklist Sebelum Submit

- [ ] Semua migration berjalan tanpa error (`php artisan migrate:fresh --seed`)
- [ ] CRUD Web berfungsi: Room, Equipment, Borrowing, User, CheckIn
- [ ] API Login mengembalikan token
- [ ] Endpoint GET equipment bisa diakses dengan token
- [ ] Endpoint POST check-in berfungsi
- [ ] Endpoint tanpa token mengembalikan 401
- [ ] Git log bersih dan terstruktur
- [ ] Branch `feature/email-notification` sudah ada
- [ ] `.env` tidak di-commit (ada di `.gitignore`)

---

## 🗓️ Timeline Pengerjaan (Estimasi 6 Hari)

| Hari | Phase | Target |
|------|-------|--------|
| 1 | Phase 0–1 | Setup project, Breeze, Sanctum, semua migration |
| 2 | Phase 2–3 | Semua Model + relasi + Seeder |
| 3 | Phase 4 | Web Controller + Blade Views (Room, Equipment) |
| 4 | Phase 4 | Web Controller + Blade Views (Borrowing, User, CheckIn) |
| 5 | Phase 5 | REST API + Auth + semua endpoint |
| 6 | Phase 6–7 | Git cleanup + Testing + Review + Submit |

---

## 📌 Urutan Perintah Artisan Lengkap (Quick Reference)

```bash
# === INSTALL ===
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build

# === MIGRATION ===
php artisan make:migration add_role_to_users_table --table=users
php artisan make:migration create_rooms_table
php artisan make:migration create_equipment_table
php artisan make:migration create_borrowings_table
php artisan make:migration create_borrowing_equipment_table
php artisan make:migration create_check_ins_table
php artisan migrate

# === MODEL ===
php artisan make:model Room
php artisan make:model Equipment
php artisan make:model Borrowing
php artisan make:model CheckIn

# === SEEDER ===
php artisan make:seeder UserSeeder
php artisan make:seeder RoomSeeder
php artisan make:seeder EquipmentSeeder
php artisan db:seed

# === WEB CONTROLLER ===
php artisan make:controller Web/RoomController --resource --model=Room
php artisan make:controller Web/EquipmentController --resource --model=Equipment
php artisan make:controller Web/BorrowingController --resource --model=Borrowing
php artisan make:controller Web/UserController --resource --model=User
php artisan make:controller Web/CheckInController --resource --model=CheckIn

# === API CONTROLLER ===
php artisan make:controller Api/AuthController
php artisan make:controller Api/EquipmentController --model=Equipment
php artisan make:controller Api/BorrowingController --model=Borrowing
php artisan make:controller Api/CheckInController --model=CheckIn
php artisan make:controller Api/RoomController --model=Room

# === API RESOURCE ===
php artisan make:resource EquipmentResource
php artisan make:resource BorrowingResource
php artisan make:resource CheckInResource
php artisan make:resource RoomResource
php artisan make:resource UserResource

# === MIDDLEWARE ===
php artisan make:middleware EnsureIsAdmin

# === FORM REQUEST ===
php artisan make:request StoreRoomRequest
php artisan make:request StoreEquipmentRequest
php artisan make:request StoreBorrowingRequest
php artisan make:request StoreCheckInRequest
```

---

> 💡 **Tips:** Kerjakan phase per phase dan lakukan `git commit` di setiap milestone kecil. Ini menjaga riwayat Git tetap bersih dan memudahkan review dosen.

---
*Blueprint dibuat untuk keperluan UTS Pemrograman Fullstack — Universitas Dian Nusantara 2025/2026*

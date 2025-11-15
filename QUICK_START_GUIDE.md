# 🚀 Quick Start Guide - New Features

## ⚡ TL;DR (Too Long; Didn't Read)

**2 Fitur Baru Sudah Siap:**
1. **Privasi Akun** - Toggle privacy settings
2. **Wishlist** - Save favorite events

**Yang Perlu Dilakukan:**
```bash
php artisan migrate
```

**Selesai!** Semua fitur langsung bisa digunakan. 🎉

---

## 📦 What's New?

### 1. Privasi Akun (Privacy Settings)
- **URL**: `/settings/privacy`
- **Fitur**: Toggle on/off untuk privacy options
- **Data**: Tersimpan di database (bukan cuma tampilan!)

### 2. Wishlist
- **URL**: `/wishlist`
- **Fitur**: Save & manage favorite events
- **Data**: Relasi database User ↔ Event

### 3. Bug Fixes
- ✅ Sidebar tidak melayang lagi saat scroll
- ✅ Footer tidak overlap content
- ✅ Semua menu sidebar konsisten di semua halaman
- ✅ Semua link berfungsi (tidak ada placeholder `#`)

---

## 🎯 Step-by-Step Setup

### Step 1: Run Migration
```bash
cd c:\xampp\htdocs\DIANTARA-main
php artisan migrate
```

**Output yang diharapkan:**
```
Migrating: 2025_10_07_001400_add_privacy_fields_to_users_table
Migrated:  2025_10_07_001400_add_privacy_fields_to_users_table (XX.XXms)
Migrating: 2025_10_07_002000_create_wishlists_table
Migrated:  2025_10_07_002000_create_wishlists_table (XX.XXms)
```

### Step 2: Test Privacy Settings
1. Login ke akun
2. Klik **"Privasi Akun"** di sidebar
3. Toggle on/off kedua opsi
4. Klik **"Simpan Perubahan"**
5. ✅ Lihat flash message hijau: "Pengaturan privasi berhasil diperbarui"

### Step 3: Test Wishlist
1. Klik **"Wishlist"** di sidebar
2. ✅ Lihat empty state (jika belum ada wishlist)
3. Klik **"Jelajahi Event"**
4. (Tombol add to wishlist akan ditambahkan di update berikutnya)

### Step 4: Verify Sidebar
1. Buka halaman: Profile, Password, Privacy, My Events, Wishlist
2. ✅ Pastikan menu **"Privasi Akun"** dan **"Wishlist"** ada di semua halaman
3. ✅ Klik setiap menu - pastikan tidak ada yang hilang

---

## 🧪 Quick Test Commands

### Check Database:
```sql
-- Check privacy columns added
DESCRIBE users;

-- Check wishlists table created
DESCRIBE wishlists;

-- Check privacy settings
SELECT id, name, profile_public, show_profile_in_events FROM users;

-- Check wishlist data
SELECT * FROM wishlists;
```

### Test URLs:
```
✅ http://127.0.0.1:8000/settings/privacy
✅ http://127.0.0.1:8000/wishlist
✅ http://127.0.0.1:8000/settings/profile
✅ http://127.0.0.1:8000/settings/password
✅ http://127.0.0.1:8000/my-events
```

---

## 🎨 Visual Guide

### Sidebar Menu (Semua Halaman):
```
┌─────────────────────────┐
│  👤 Hai, [Nama User]    │
│  Atur akun kamu disini  │
├─────────────────────────┤
│ 👤 Pengaturan Akun      │
│ 🎫 Transaksi Event      │
│ 🎭 Transaksi Atraksi    │
│ 🔒 Atur Kata Sandi      │
│ 🛡️ Privasi Akun    ← NEW│
│ ❤️ Wishlist         ← NEW│
├─────────────────────────┤
│ 🚪 Log Out              │
└─────────────────────────┘
```

### Privacy Settings Page:
```
┌──────────────────────────────────────┐
│ Privasi Akun                         │
├──────────────────────────────────────┤
│ Bikin profil jadi publik       [ON]  │
│ Kalau kamu bikin jadi publik...      │
│                                      │
│ Tampilkan profil pada...       [ON]  │
│ Kalau kamu aktifkan...               │
│                                      │
│         [Batal] [Simpan Perubahan]   │
└──────────────────────────────────────┘
```

### Wishlist Page:
```
┌──────────────────────────────────────┐
│ Wishlist Saya                        │
│ Event yang kamu simpan untuk nanti   │
├──────────────────────────────────────┤
│ ┌────────┐  ┌────────┐              │
│ │ Event  │  │ Event  │              │
│ │   1    │  │   2    │              │
│ │  ❤️    │  │  ❤️    │              │
│ └────────┘  └────────┘              │
└──────────────────────────────────────┘
```

---

## 🐛 Troubleshooting

### Migration Error?
```bash
# Clear cache first
php artisan cache:clear
php artisan config:clear

# Try migrate again
php artisan migrate
```

### Page Not Found (404)?
```bash
# Clear route cache
php artisan route:clear

# Check routes
php artisan route:list | grep wishlist
php artisan route:list | grep privacy
```

### Sidebar Menu Not Showing?
```bash
# Clear view cache
php artisan view:clear

# Hard refresh browser (Ctrl + F5)
```

### Database Connection Error?
1. Check XAMPP MySQL is running
2. Check `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=diantara
   DB_USERNAME=root
   DB_PASSWORD=
   ```

---

## 📊 Database Schema Quick Reference

### Table: `users` (Updated)
```sql
-- New columns
profile_public BOOLEAN DEFAULT 1
show_profile_in_events BOOLEAN DEFAULT 1
```

### Table: `wishlists` (New)
```sql
id BIGINT AUTO_INCREMENT PRIMARY KEY
user_id BIGINT NOT NULL
event_id BIGINT NOT NULL
created_at TIMESTAMP
updated_at TIMESTAMP
UNIQUE KEY (user_id, event_id)
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
```

---

## 🎯 Feature Status

| Feature | Status | URL | Notes |
|---------|--------|-----|-------|
| Privacy Settings | ✅ READY | `/settings/privacy` | Fully functional |
| Wishlist | ✅ READY | `/wishlist` | Fully functional |
| Add to Wishlist Button | ⏳ TODO | - | Will be added to catalog |
| Transaksi Atraksi | ⏳ TODO | `#` | Placeholder |

---

## 💡 Pro Tips

### For Testing:
1. **Use different browsers** untuk test multiple users
2. **Check browser console** untuk AJAX errors
3. **Use Postman** untuk test API endpoints
4. **Check database** setelah setiap action

### For Development:
1. **Always run migration** setelah pull code baru
2. **Clear cache** jika ada perubahan config/routes
3. **Use `dd()` or `Log::info()`** untuk debugging
4. **Check Laravel logs** di `storage/logs/laravel.log`

### For Production:
1. **Backup database** sebelum migrate
2. **Test di local** dulu sebelum deploy
3. **Use `.env.production`** untuk production settings
4. **Enable maintenance mode** saat migrate: `php artisan down`

---

## 📞 Need Help?

### Common Issues:

**Q: Migration error "table already exists"?**
A: Table sudah ada. Skip atau rollback dulu: `php artisan migrate:rollback`

**Q: Privacy settings tidak tersimpan?**
A: Check browser console untuk error. Pastikan CSRF token valid.

**Q: Wishlist tidak muncul?**
A: Pastikan sudah login dan migration sudah dijalankan.

**Q: Sidebar menu hilang?**
A: Hard refresh browser (Ctrl + F5) atau clear view cache.

---

## ✅ Checklist Before Moving On

- [ ] Migration berhasil dijalankan
- [ ] Privacy settings bisa diakses dan berfungsi
- [ ] Wishlist page bisa diakses
- [ ] Sidebar menu konsisten di semua halaman
- [ ] Tidak ada error di browser console
- [ ] Database tables & columns sudah ada

**Jika semua checklist ✅, you're good to go!** 🚀

---

## 🎉 What's Next?

Setelah semua berfungsi, prioritas berikutnya:

1. **Add Wishlist Button** di event cards (catalog & detail)
2. **Session Timeout** 5 menit (UKK requirement)
3. **Admin Dashboard** dengan charts
4. **Export to CSV/Excel**
5. **Certificate Generation** (PDF)

**Happy Coding!** 💻✨

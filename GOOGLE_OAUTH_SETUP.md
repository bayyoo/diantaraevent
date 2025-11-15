# 🔐 Google OAuth Setup Guide

## 📋 Prerequisites
- Laravel Socialite package (already installed via composer)
- Google Cloud Console account

---

## 🚀 Setup Instructions

### **Step 1: Create Google Cloud Project** (10 minutes)

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Click **"Select a project"** → **"New Project"**
3. Enter project name: `Diantara Event Management`
4. Click **"Create"**

### **Step 2: Enable Google+ API**

1. In your project, go to **"APIs & Services"** → **"Library"**
2. Search for **"Google+ API"**
3. Click on it and press **"Enable"**

### **Step 3: Create OAuth 2.0 Credentials**

1. Go to **"APIs & Services"** → **"Credentials"**
2. Click **"Create Credentials"** → **"OAuth client ID"**
3. If prompted, configure OAuth consent screen:
   - User Type: **External**
   - App name: `Diantara`
   - User support email: Your email
   - Developer contact: Your email
   - Click **"Save and Continue"**
   - Scopes: Skip (click "Save and Continue")
   - Test users: Add your email
   - Click **"Save and Continue"**

4. Back to Create OAuth client ID:
   - Application type: **Web application**
   - Name: `Diantara Web Client`
   
5. **Authorized JavaScript origins:**
   ```
   http://127.0.0.1:8000
   http://localhost:8000
   ```

6. **Authorized redirect URIs:**
   ```
   http://127.0.0.1:8000/auth/google/callback
   http://localhost:8000/auth/google/callback
   ```

7. Click **"Create"**
8. **COPY** your **Client ID** and **Client Secret**

---

### **Step 4: Configure Laravel .env**

Add these lines to your `.env` file:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your-client-id-here.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URL=http://127.0.0.1:8000/auth/google/callback
```

**Replace:**
- `your-client-id-here` with your actual Client ID
- `your-client-secret-here` with your actual Client Secret

---

### **Step 5: Run Migration**

```bash
php artisan migrate
```

This will add the `google_id` column to your `users` table.

---

### **Step 6: Clear Cache**

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

---

## ✅ Testing

1. Go to `http://127.0.0.1:8000/login`
2. Click **"Lanjutkan dengan Google"**
3. Select your Google account
4. Grant permissions
5. You should be redirected back and logged in!

---

## 🎯 How It Works

### **New User Flow:**
1. User clicks "Lanjutkan dengan Google"
2. Redirected to Google login
3. Google returns user info (name, email, google_id)
4. System creates new user with:
   - `email_verified_at` = now (auto-verified)
   - `google_id` = Google user ID
   - Random password (not used)
5. User is logged in automatically

### **Existing User Flow:**
1. If email already exists in database
2. System updates `google_id` if not set
3. User is logged in

---

## 🔒 Security Notes

- Password is nullable for Google users
- Email is auto-verified for Google OAuth
- Google handles authentication security
- Random password generated (24 characters)

---

## 🌐 Production Setup

For production, update your `.env`:

```env
GOOGLE_REDIRECT_URL=https://yourdomain.com/auth/google/callback
```

And add to Google Cloud Console:
- Authorized JavaScript origins: `https://yourdomain.com`
- Authorized redirect URIs: `https://yourdomain.com/auth/google/callback`

---

## 🐛 Troubleshooting

### Error: "redirect_uri_mismatch"
- Check that redirect URI in Google Console matches exactly with `.env`
- Must include protocol (http:// or https://)
- No trailing slash

### Error: "Client ID not found"
- Verify Client ID in `.env` is correct
- Run `php artisan config:clear`

### Error: "Access blocked"
- Add your email to Test Users in OAuth consent screen
- Or publish the app (not recommended for development)

---

## 📝 Files Modified

1. `database/migrations/2025_11_12_020500_add_google_id_to_users_table.php` - Migration
2. `config/services.php` - Google config
3. `app/Http/Controllers/Auth/GoogleAuthController.php` - Controller
4. `routes/web.php` - Routes
5. `resources/views/auth/login.blade.php` - Login button
6. `resources/views/auth/register.blade.php` - Register button

---

## 🎉 Done!

Your Google OAuth is now ready to use! Users can login/register with just one click! 🚀

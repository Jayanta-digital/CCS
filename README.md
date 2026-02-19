# TechVision Computer Institute – Website Documentation

## 📦 Project Overview

A complete, production-ready website for a private computer coaching institute.  
Built with HTML5, CSS3, Vanilla JavaScript, and PHP (backend optional).

---

## 📁 Folder Structure

```
institute/
├── index.html                  ← Home page
├── pages/
│   ├── about.html              ← About the institute
│   ├── courses.html            ← All courses listing
│   ├── admission.html          ← Admission form (Google Form embed)
│   ├── referral.html           ← Referral program form
│   ├── verify.html             ← Certificate verification
│   ├── student-zone.html       ← Downloads, notices, results
│   └── contact.html            ← Contact page + map
├── assets/
│   ├── css/
│   │   └── style.css           ← Main stylesheet (all pages)
│   ├── js/
│   │   ├── main.js             ← Core JS (header, popups, counters)
│   │   ├── verify.js           ← Certificate verification logic
│   │   └── components.js       ← Site config reference
│   └── images/                 ← Put all images here
├── php/
│   ├── verify.php              ← Certificate verification API
│   ├── referral.php            ← Referral form submission
│   ├── contact.php             ← Contact form submission
│   └── admin-referrals.php     ← Admin panel to view referrals
├── data/                       ← Auto-created on first form submit
│   ├── referrals.json          ← Stored referral submissions
│   └── enquiries.json          ← Stored contact enquiries
└── README.md                   ← This file
```

---

## 🚀 How to Host

### Option A: Static Hosting (GitHub Pages, Netlify, Vercel)
- Upload all files **except the `php/` folder**
- Certificate verification will use the JavaScript-only demo mode
- Contact and referral forms won't submit to backend (show demo success)

### Option B: Shared PHP Hosting (Recommended for full functionality)
1. Purchase shared hosting (Hostinger, Bluehost, Namecheap, etc.)
2. Upload all files to the `public_html/` directory
3. PHP features will work automatically
4. Set folder permissions: `data/` → 755, files → 644

### Option C: Local Testing
```bash
# With PHP built-in server
cd /path/to/institute
php -S localhost:8000

# Or use XAMPP / WAMP
```

---

## ✏️ How to Change Institute Details

All institute-specific details are spread across the pages. Search and replace these values:

| Find | Replace With |
|------|-------------|
| `TechVision` | Your Institute Name |
| `Computer Institute` | Your Tagline |
| `+91 98765 43210` | Your Phone Number |
| `+91 87654 32109` | Your Second Phone |
| `info@techvisioninstitute.in` | Your Email |
| `verify@techvisioninstitute.in` | Your Verify Email |
| `Gorakhpur, Uttar Pradesh` | Your City, State |
| `273001` | Your PIN Code |
| `123, Main Market Road...` | Your Full Address |
| `2019` | Your Established Year |
| `REG/MSME/UP/2019/12345` | Your MSME Reg Number |
| `919876543210` | WhatsApp Number (country code + number, no +) |

> 💡 **Tip:** Use VS Code's **Find & Replace** (Ctrl+H) with "Replace All Files" to do this in seconds.

---

## 🎨 How to Change Colors / Branding

Open `assets/css/style.css` and edit the CSS variables at the top:

```css
:root {
  --primary: #1a3a6e;        /* Main blue color */
  --primary-dark: #0f2449;   /* Darker shade */
  --primary-light: #2756a8;  /* Lighter shade */
  --accent: #e8a020;         /* Gold/Orange accent */
  /* ... */
}
```

---

## 📝 How to Change Google Form Link (Admission Form)

1. Create your Google Form for admissions
2. Click **Send** → **Embed** tab → Copy the `src` URL from the iframe
3. Open `pages/admission.html`
4. Find this line:
   ```html
   src="https://docs.google.com/forms/d/e/REPLACE_WITH_YOUR_GOOGLE_FORM_ID/viewform?embedded=true"
   ```
5. Replace the entire `src` value with your Google Form embed URL

---

## ✅ How to Add Certificates

### Method 1: Edit the JavaScript (for static hosting)

Open `assets/js/verify.js` and add to the `CERT_DATA` object:

```javascript
const CERT_DATA = {
  "TVI-2025-100": { 
    name: "Student Full Name", 
    course: "Course Name Here", 
    year: "2025", 
    grade: "A (88%)" 
  },
  // Add more...
};
```

### Method 2: Edit the PHP file (for PHP hosting – recommended)

Open `php/verify.php` and add to the `$certificates` array:

```php
$certificates = [
  'TVI-2025-100' => [
    'name'   => 'Student Full Name',
    'course' => 'DCA – Diploma in Computer Applications',
    'year'   => '2025',
    'grade'  => 'A (88%)',
    'status' => 'VALID',
  ],
  // Add more...
];
```

### Method 3: MySQL Database (for large numbers of certificates)

See the commented-out database section in `php/verify.php`. Uncomment and configure with your database credentials.

**SQL Table:**
```sql
CREATE TABLE certificates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cert_number VARCHAR(30) UNIQUE NOT NULL,
  name VARCHAR(150) NOT NULL,
  course VARCHAR(200) NOT NULL,
  year YEAR NOT NULL,
  grade VARCHAR(30),
  status ENUM('VALID','INVALID','REVOKED') DEFAULT 'VALID',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO certificates (cert_number, name, course, year, grade) VALUES
('TVI-2025-001', 'Student Name', 'DCA', 2025, 'A (88%)');
```

---

## 👁️ How to View Referrals (Admin)

### Method 1: Admin Panel (PHP hosting)
Visit: `yourdomain.com/php/admin-referrals.php?pass=TechVision@2025`

> ⚠️ **Change the password** in `php/admin-referrals.php` before going live!

### Method 2: Direct file access
Download and open: `data/referrals.json`

### Method 3: Export CSV
Use the **Export CSV** button in the admin panel.

---

## 🔧 WhatsApp Button Configuration

Open `assets/js/main.js` and update:

```javascript
const SITE_CONFIG = {
  whatsappNumber: "919876543210",  // Country code + number (no + sign)
  whatsappMessage: "Hello! I'm interested in your courses.",
  // ...
};
```

---

## 🌐 How to Deploy on Shared Hosting (Step by Step)

1. **Purchase hosting** from Hostinger/Bluehost/etc. (min ₹99/month works)
2. **Purchase domain** (e.g., techvisioninstitute.in)
3. **Connect domain** to hosting via cPanel → Domains
4. **Upload files:**
   - Login to cPanel → File Manager
   - Navigate to `public_html/`
   - Upload all project files (or use File Manager ZIP upload)
5. **Set permissions:**
   - `data/` folder → 755
   - PHP files → 644
6. **Test:**
   - Visit your domain
   - Try certificate verification
   - Submit a test referral
   - Check admin panel

---

## 🔐 Security Checklist

- [ ] Change admin panel password in `php/admin-referrals.php`
- [ ] Add `.htpasswd` protection to `/php/` folder  
- [ ] Protect `/data/` folder with `.htaccess`
- [ ] Enable HTTPS (free with Let's Encrypt in cPanel)
- [ ] Update Google Form ID in admission page

Add to `data/.htaccess`:
```apache
Order allow,deny
Deny from all
```

---

## 🎨 How to Reuse for Another Institute

1. Find & Replace all institute-specific text (see table above)
2. Update logo (replace `logo-icon` emoji or add `<img>` tag)
3. Update courses in `pages/courses.html`
4. Update certificate numbers in `php/verify.php` and `assets/js/verify.js`
5. Update Google Form link in `pages/admission.html`
6. Update map embed in `pages/contact.html`
7. Update social media links in footer

---

## 📞 Support

For help customizing this for your institute, contact:  
📧 info@techvisioninstitute.in  
📞 +91 98765 43210

---

*Built for career-focused education. © 2025 TechVision Computer Institute.*

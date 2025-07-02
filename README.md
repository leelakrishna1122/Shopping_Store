# 🛍️ KITTU’S STORE – PHP E-Commerce Demo

## 📚 Reference Used
* Started from a **basic PHP e-commerce template** (product list, add-to-cart, login / register).
* Built on top of that scaffold and **added new features & UI improvements** with my own ideas and AI-assisted coding.

---

## 🚀 Key Features

| Area | What It Does |
|------|--------------|
| **User flow** | Browse → Add to Cart → Checkout form (name, mobile, city, address, payment method). |
| **Dynamic Cart** | Update quantity / remove items in-place. |
| **Checkout** | After form submit, order summary is built and **emailed via PHPMailer**. |
| **Smart Admin routing** | App picks a **random admin in the same city** (fallback: any random admin) and sends the order. |
| **Admin duties** | Admin replies to customer by **email / SMS** to confirm payment and shipping. |
| **Admin panel** | Manage products (CRUD) & view orders (DB-driven). |
| **Intro overlay** | “😊 Happy Shopping” splash on homepage load. |
| **Custom styles** | Modern cards, gradient backgrounds, Unsplash hero image on checkout. |
| **Security** | Passwords hashed (`password_hash()`), admin verification requires pass-code **I_AM_VERIFIED**. |

---

## ⚙️ Tech Stack

| Layer | Tech |
|-------|------|
| Backend | **PHP 8**, **PDO** (prepared statements) |
| Database | **MySQL** |
| Mail | **PHPMailer 6** + Gmail SMTP (App Password) |
| Front-end | HTML5, CSS3, vanilla JS |
| Icons & Images | Emojis 😊 🎉, Unsplash background |


---



## 🔄 Application Flow

1. **Home** – product cards rendered from `products` table.  
2. **Add to Cart** – inserts/updates `cart` table.  
3. **Cart** – quantity update & removal in real time.  
4. **Checkout** – collects user data, builds order summary.  
5. **Admin selection**  
   * `SELECT * FROM users WHERE role='admin' AND city=? ORDER BY RAND() LIMIT 1`  
   * Fallback: any admin `ORDER BY RAND()`.  
6. **Email dispatch** via `send_mail.php` (PHPMailer SMTP).  
7. **User sees success screen** → informed to await confirmation by mail/SMS.

---

## ▶️ Running Locally

1. Clone repo & `cd` into it.  
2. Import `database.sql` into MySQL.  
3. Update `/includes/db.php` with local DB creds.  
4. Install deps:

   ```bash
   composer install        # installs phpmailer/phpmailer


# Event Management System

A modern, role-based Event Management System built with PHP and MySQL. It supports multi-role access (Admin, Manager, Attendee), dynamic event creation with singers and add-on activities, digital ticketing, and fully customizable branding with PKR (Pakistani Rupees) as the default currency.

---

## 🔑 Demo Login Credentials

After running the seed script (`config/seed.php`), the following demo accounts are available:

| Role          | Email              | Password   | Notes                          |
| ------------- | ------------------ | ---------- | ------------------------------ |
| **Super Admin** | `admin@demo.com`   | `12345678` | Full system & site settings    |
| **Manager**     | `manager@demo.com` | `12345678` | Event & resources management   |
| **Attendee**    | `user@demo.com`    | `12345678` | Ticket booking & viewing       |

> **Important (Production Use):** Change these credentials and passwords before deploying to a live environment.

---

## ✨ Key Features

### 🔐 Role-Based Access Control

| Role           | Capabilities |
| -------------- | ------------ |
| **Super Admin** | Manage users, reset passwords, configure site settings (logo, colors, name), view global analytics. |
| **Manager**      | Create/update/delete events, manage singers and activities, view attendees, monitor event revenue. |
| **Attendee**     | Browse events, select additional activities, book tickets, view/download tickets. |

### 🎤 Event & Resource Management (Manager)

- **Singers Management**
  - Add, edit, delete singers/performers
  - Store genre and per-singer pricing
- **Activities Management**
  - Add, edit, delete add-on activities (e.g., VIP seating, backstage pass)
  - Each activity has description and price
- **Event Creation**
  - Create events with title, description, date/time, location, ticket price
  - Assign one or more singers
  - Attach multiple activities as add-ons
  - Configure total and available tickets
- **Attendee Overview**
  - View all bookings per event
  - See revenue and ticket utilization

### ⚙️ Admin Settings (Super Admin)

- Configure **site name**, **logo**, **primary color**, and **secondary color**
- Control branding globally (navbar, buttons, accents)
- Reset user passwords
- View high-level stats and charts (users, events, bookings, revenue)

### 🎟️ Digital Ticketing (Attendee)

- Book events with optional activities (add-ons)
- See a breakdown of:
  - Base ticket price
  - Selected activities pricing
  - Total price
- Tickets are displayed in a digital, printable format
- All pricing is shown in **PKR**

### 🎨 Modern UI/UX

- Dark theme by default with **light mode toggle**
- Smooth animations for cards, buttons, and hero section
- Responsive layout using **Bootstrap 5**
- Theme colors pulled dynamically from admin-configured settings
- Animated navbar, stat cards, and event cards

---

## 🛠️ Technology Stack

- **Frontend**
  - PHP (server-rendered views)
  - HTML5, CSS3, Bootstrap 5
  - Custom responsive CSS (`assets/css/style.css`)
  - Font Awesome icons
  - Chart.js for admin analytics

- **Backend**
  - PHP 8+ (Recommended)
  - MySQL (MariaDB compatible)

- **Server Requirements**
  - Apache/Nginx with PHP support
  - MySQL / MariaDB
  - `mod_rewrite` enabled (for `.htaccess` features)

---

## 📦 Installation (Localhost with XAMPP / WAMP / MAMP)

### 1. Clone / Copy the Project

Place the project into your web root:

- **XAMPP (Windows/macOS):** `htdocs/Event-Management-System`
- **WAMP:** `www/Event-Management-System`
- **MAMP:** `htdocs/Event-Management-System`

Example path (XAMPP):
```bash
/Applications/XAMPP/xamppfiles/htdocs/Event-Management-System
```

### 2. Create the Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database:
   - **Name:** `event_management_system`
3. Import the schema:
   - Go to the database
   - Click **Import**
   - Select `config/setup.sql`
   - Click **Go**

### 3. Configure Database Connection

Open [`config/db.php`](config/db.php) and verify:

```php
$host = 'localhost';
$user = 'root';
$pass = ''; // XAMPP/WAMP default
$dbname = 'event_management_system';
```

Adjust credentials as needed for your environment.

### 4. Seed Demo Users (Optional but Recommended)

This creates the three demo accounts shown above.

From terminal/command prompt, inside the project root:

```bash
php config/seed.php
```

Or, for XAMPP on macOS:

```bash
/Applications/XAMPP/xamppfiles/bin/php config/seed.php
```

You should see output indicating that **SuperAdmin**, **DemoManager**, and **DemoUser** were created/updated with password `12345678`.

### 5. Run the Application

In your browser, open:

```text
http://localhost/Event-Management-System/
```

Login page:

```text
http://localhost/Event-Management-System/auth/login.php
```

---

## 🌐 Deployment & Portability

This project is designed to work seamlessly regardless of where it is hosted:

- Root domain: `https://yourdomain.com/`
- Subdirectory: `https://yourdomain.com/events/`
- Subdomain: `https://events.yourdomain.com/`

### Dynamic Base URL

The file [`config/config.php`](config/config.php) **auto-detects**:

- Protocol (`http` or `https`)
- Hostname
- Installation directory

It defines:

```php
define('BASE_URL', $base_url);   // e.g. "" or "/Event-Management-System"
define('SITE_URL', $site_url);   // Full URL (protocol + host + base)
```

And provides helper functions:

```php
url('auth/login.php');   // Returns a path with BASE_URL
redirect('dashboard/admin.php'); // Safe redirect helper
```

All major navigation links use `url()` and `BASE_URL`, so **no code changes** are needed when you move the project to another host or subdirectory.

### .htaccess (Apache)

The project includes a `.htaccess` file with:

- Security headers (XSS, clickjacking, MIME sniffing protection)
- Disabled directory browsing
- Gzip compression
- Browser caching for static assets

You can optionally enable HTTPS redirection once an SSL certificate is installed.

---

## 🧱 Database Overview

Key tables (created by `config/setup.sql`):

- `users`
  - `id`, `username`, `full_name`, `email`, `phone`, `password`, `role`, `created_at`
- `singers`
  - `id`, `name`, `genre`, `price`
- `activities`
  - `id`, `name`, `description`, `price`
- `events`
  - `id`, `title`, `description`, `event_date`, `location`, `price`, `total_tickets`, `available_tickets`, `created_by`, `created_at`
- `event_singers`
  - Many-to-many: events ↔ singers
- `event_activities`
  - Many-to-many: events ↔ activities
- `bookings`
  - `id`, `user_id`, `event_id`, `total_price`, `booking_date`, `status`
- `booking_activities`
  - Many-to-many: bookings ↔ activities
- `site_settings`
  - `id`, `site_name`, `logo_path`, `primary_color`, `secondary_color`

For existing databases, [`config/migrate.sql`](config/migrate.sql) can be used to add missing columns or tables.

---

## 👥 Roles & User Flows

### 1. Super Admin

- Log in using:
  - **Email:** `admin@demo.com`
  - **Password:** `12345678`
- Access the Admin Dashboard (`dashboard/admin.php`):
  - View system stats: total users, events, bookings, revenue
  - View ticket sales chart per event
  - Manage users (view, delete others)
  - Access site settings to:
    - Change site name
    - Upload logo (stored under `assets/uploads/`)
    - Change primary and secondary colors

### 2. Manager

- Log in using:
  - **Email:** `manager@demo.com`
  - **Password:** `12345678`
- Access the Manager Dashboard (`dashboard/manager.php`):
  - Create and manage events
  - Assign singers and activities
  - View attendees per event
  - Monitor revenue and ticket counts

### 3. Attendee

- Log in using:
  - **Email:** `user@demo.com`
  - **Password:** `12345678`
- Access the Attendee Dashboard (`dashboard/attendee.php`):
  - Browse upcoming events
  - Select tickets and add-on activities
  - Book tickets
  - View booking history and tickets

---

## 🎨 Theming & UI Details

- **Dark Mode (default)** with a **light mode toggle** in the navbar
- Primary/secondary colors are controlled by the `site_settings` table and can be changed via the Admin settings page
- Custom CSS lives in [`assets/css/style.css`](assets/css/style.css):
  - Animated cards (`.card-custom`)
  - Themed buttons (`.btn-primary-custom`, `.btn-outline-custom`)
  - Stat cards, tables, modals, and utilities
  - Hero section animations and gradients
- Font Awesome icons are used for visual clarity (e.g., calendar, user, gear icons)

---

## 🔐 Security Considerations

- Passwords stored using PHP's `password_hash()` and `password_verify()`
- Basic session-based authentication with role checks on dashboard pages
- Prepared statements used for database queries in critical actions (e.g., login, registration)
- `.htaccess` protects sensitive files and disables directory listing
- Uploads directory limited to images; access is restricted with an `index.php` guard

> **Note:** For production, you should further harden security (CSRF protection, rate limiting, stronger password policies, etc.).

---

## 🧑‍💻 Project Structure

```text
├── actions/                  # Form handlers & backend actions
│   ├── activity_action.php   # CRUD for activities
│   ├── book_ticket.php       # Ticket booking logic
│   ├── create_event.php      # Event creation
│   ├── delete_event.php      # Delete events
│   ├── delete_user.php       # Admin user deletion
│   ├── reset_password.php    # Password reset
│   ├── singer_action.php     # CRUD for singers
│   ├── update_event.php      # Update event details
│   ├── update_profile.php    # Update user profile
│   └── update_settings.php   # Admin site settings update
│
├── assets/
│   ├── css/
│   │   └── style.css         # Main custom stylesheet
│   └── uploads/              # Uploaded logos/assets
│       ├── index.php         # Blocks direct directory listing
│       └── .gitkeep          # Keeps folder in version control
│
├── auth/                     # Authentication pages
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   └── auth_process.php      # Handles login & register POST
│
├── config/
│   ├── db.php                # Database connection
│   ├── setup.sql             # Initial schema & seed data
│   ├── migrate.sql           # Migration script for existing DBs
│   ├── seed.php              # Demo users seeder
│   ├── config.php            # Global BASE_URL/site URL helpers
│   └── env.template.php      # Environment config template
│
├── dashboard/
│   ├── admin.php             # Admin dashboard
│   ├── attendee.php          # Attendee dashboard
│   ├── manager.php           # Manager dashboard
│   ├── edit_event.php        # Edit events
│   ├── manage_activities.php # Manage activities
│   ├── manage_singers.php    # Manage singers
│   ├── profile.php           # User profile
│   ├── settings.php          # Site settings (admin)
│   ├── ticket.php            # Ticket view/print page
│   └── view_attendees.php    # Attendees per event
│
├── includes/
│   ├── header.php            # Global header & navbar
│   ├── footer.php            # Global footer & scripts
│   └── settings_loader.php   # Loads site settings & helpers
│
├── legacy/                   # Legacy/older code (not used in main app)
│
├── index.php                 # Public landing page
├── .htaccess                 # Apache config (security, caching, etc.)
└── README.md                 # Project documentation (this file)
```

---

## 🧪 Testing & Local Development Tips

- Ensure MySQL and Apache are running (if using XAMPP/WAMP/MAMP)
- Use the demo credentials to quickly verify:
  - Admin, Manager, and Attendee dashboards
  - Event creation, booking, and ticket generation
- Check browser developer tools (Network/Console) if CSS or assets do not load

---

## 📌 Notes

- All monetary values are displayed in **PKR (Pakistani Rupees)**.
- The system is designed as a learning and demonstration project but can be extended for real-world usage with further hardening.

---

**Developed by _Shunaid Ahmed_**

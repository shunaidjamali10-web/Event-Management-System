# Event Management System

A premium, full-stack Event Management System featuring role-based access control, dynamic pricing with singer/activity selection, admin site customization, and PKR currency.

## ✨ Key Features

### 🔐 Role-Based Access

| Role | Capabilities |
|------|--------------|
| **Super Admin** | Site settings (name, logo, colors), password reset, user management, analytics |
| **Manager** | CRUD Singers & Activities, create events, view attendees, track revenue |
| **Attendee** | Browse events, select add-ons, book tickets, print digital tickets |

### 🎤 Manager Features
- **Manage Singers**: Add, edit, delete performers with genre and pricing.
- **Manage Activities**: Add, edit, delete event add-ons with descriptions.
- **Event Creation**: Assign singers and activities when creating events.
- **Attendee List**: View all bookings for each event.

### ⚙️ Admin Settings
- **Site Name**: Customize the application title.
- **Logo Upload**: Upload a custom logo displayed in the navbar.
- **Color Scheme**: Pick primary and secondary colors that apply globally.
- **Password Reset**: Reset any user's password.

### 🎟️ Digital Ticketing
- Printable ticket with QR placeholder.
- Activity breakdown on ticket.
- Dynamic colors from admin settings.
- PKR currency throughout.

## 🛠️ Technology Stack
- **Frontend**: PHP, HTML5, Bootstrap 5, Custom CSS, Chart.js.
- **Backend**: PHP 8+.
- **Database**: MySQL.

## 🚀 Installation Guide

### 1. Database Setup
1. Open phpMyAdmin (`http://localhost/phpmyadmin`).
2. Create database: **`event_management_system`**.
3. Import `config/setup.sql`.

### 2. Demo Credentials
| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | `admin@demo.com` | `12345678` |
| **Manager** | `manager@demo.com` | `12345678` |
| **Attendee** | `user@demo.com` | `12345678` |

### 3. Sample Data
Pre-loaded:
- **5 Singers**: Rahat Fateh Ali Khan, Atif Aslam, Arijit Singh, Sanam Marvi, Ali Zafar.
- **5 Activities**: VIP Seating, Backstage Pass, Food Package, Photo Booth, Parking Pass.

## 📂 Directory Structure
```
├── actions/              # Backend handlers
│   ├── activity_action.php
│   ├── book_ticket.php
│   ├── create_event.php
│   ├── delete_event.php
│   ├── delete_user.php
│   ├── reset_password.php
│   ├── singer_action.php
│   └── update_settings.php
├── assets/
│   ├── css/style.css
│   └── uploads/          # Logo uploads
├── auth/                 # Login, Register, Logout
├── config/               # Database, setup SQL
├── dashboard/
│   ├── admin.php
│   ├── attendee.php
│   ├── manager.php
│   ├── manage_activities.php
│   ├── manage_singers.php
│   ├── settings.php
│   ├── ticket.php
│   └── view_attendees.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── settings_loader.php
└── index.php
```

## 💱 Currency
All prices are displayed in **PKR (Pakistani Rupees)**.

---
*Developed by **Shunaid Ahmed***

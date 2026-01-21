# Deployment Checklist for Event Management System

## Pre-Deployment Steps

### 1. Database Setup
- [ ] Import `config/setup.sql` to create fresh database
- [ ] OR run `config/migrate.sql` if updating existing database
- [ ] Verify all tables are created successfully
- [ ] Create at least one admin user account

### 2. Configuration Files
- [ ] Update database credentials in `config/db.php` if needed
- [ ] Review `config/config.php` - it auto-detects URLs (no changes needed)
- [ ] Check timezone settings for your location
- [ ] Optional: Create `config/env.production.php` from template

### 3. Security Settings
- [ ] Set `display_errors = Off` in production (already in `.htaccess`)
- [ ] Enable HTTPS redirect in `.htaccess` (uncomment lines 11-12)
- [ ] Change default database password from empty string
- [ ] Ensure `assets/uploads/` directory has write permissions (755)

### 4. File Permissions (Linux/Unix servers)
```bash
# Set proper permissions
chmod 755 /path/to/Event-Management-System
chmod 755 /path/to/Event-Management-System/assets/uploads
chmod 644 /path/to/Event-Management-System/.htaccess
chmod 600 /path/to/Event-Management-System/config/db.php
```

## Deployment Locations

### Option 1: Subdomain (e.g., events.yourdomain.com)
1. Upload all files to subdomain root directory
2. No configuration changes needed - auto-detects!
3. URL will be: `https://events.yourdomain.com/`

### Option 2: Subdirectory (e.g., yourdomain.com/events)
1. Upload all files to subdirectory
2. No configuration changes needed - auto-detects!
3. URL will be: `https://yourdomain.com/events/`

### Option 3: Root Domain (e.g., yourdomain.com)
1. Upload all files to public_html or www directory
2. No configuration changes needed - auto-detects!
3. URL will be: `https://yourdomain.com/`

## Post-Deployment Verification

### Test These Pages:
- [ ] Homepage loads with correct styling
- [ ] Login page works
- [ ] Registration works
- [ ] Admin dashboard accessible
- [ ] Manager dashboard accessible
- [ ] Attendee dashboard accessible
- [ ] Theme toggle (dark/light) works
- [ ] Logo upload works (if using)
- [ ] Event creation works
- [ ] Ticket booking works

### Check These URLs Work:
- [ ] `/index.php`
- [ ] `/auth/login.php`
- [ ] `/auth/register.php`
- [ ] `/dashboard/admin.php` (as admin)
- [ ] `/dashboard/manager.php` (as manager)
- [ ] `/dashboard/attendee.php` (as attendee)
- [ ] `/assets/css/style.css` loads correctly

## Common Hosting Platforms

### cPanel Hosting
1. Upload files via File Manager or FTP
2. Import database via phpMyAdmin
3. Update `config/db.php` with cPanel database credentials
4. Set folder permissions via File Manager

### Shared Hosting (Bluehost, HostGator, etc.)
1. Same as cPanel instructions
2. Database host might be `localhost` or specific hostname
3. Check hosting provider's documentation for database details

### VPS/Cloud (DigitalOcean, Linode, AWS)
1. Install LAMP/LEMP stack
2. Upload files to `/var/www/html/` or custom directory
3. Configure Apache/Nginx virtual host
4. Set up SSL certificate (Let's Encrypt)
5. Configure firewall rules

### Free Hosting (InfinityFree, 000webhost)
1. Upload via FTP or file manager
2. Create MySQL database in control panel
3. Some free hosts don't support `.htaccess` - features may be limited

## SSL Certificate Setup
```apache
# Add to .htaccess after getting SSL certificate:
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## Environment-Specific Settings

### Development (localhost)
- Debug mode: ON
- Error display: ON
- Database: local MySQL

### Production (live server)
- Debug mode: OFF
- Error display: OFF
- Database: production MySQL
- Enable HTTPS redirect
- Enable security headers

## Troubleshooting

### Issue: CSS not loading
- Check `BASE_URL` is correct in browser console
- Verify `assets/css/style.css` file exists
- Check file permissions (644)

### Issue: Database connection failed
- Verify credentials in `config/db.php`
- Check database exists
- Test database connection separately

### Issue: 404 errors on all pages
- Check `.htaccess` is uploaded
- Verify `mod_rewrite` is enabled on server
- Check file permissions

### Issue: Redirects not working
- Clear browser cache
- Check `config/config.php` is being loaded
- Verify PHP sessions are working

## Backup Recommendations

### Before Deployment:
- [ ] Backup local database
- [ ] Backup local files

### After Deployment:
- [ ] Set up automated daily database backups
- [ ] Set up weekly file backups
- [ ] Store backups off-site

## Performance Optimization

### After successful deployment:
- [ ] Enable Gzip compression (already in `.htaccess`)
- [ ] Enable browser caching (already in `.htaccess`)
- [ ] Optimize uploaded images
- [ ] Consider CDN for static assets
- [ ] Enable PHP OpCache if available

## Support & Maintenance

### Regular Maintenance:
- Weekly: Check error logs
- Monthly: Database backup
- Quarterly: Security updates
- Annually: Review and optimize database

---

**Note**: The system is designed to be portable. No hardcoded paths means it works anywhere without code changes! 🚀

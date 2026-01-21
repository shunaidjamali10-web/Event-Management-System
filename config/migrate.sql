-- Migration Script for Existing Databases
-- Run this ONLY if you already have the database set up
-- This adds missing columns to existing tables

-- Add full_name column to users table if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS full_name VARCHAR(100) DEFAULT NULL AFTER username;

-- Add phone column to users table if it doesn't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS phone VARCHAR(20) DEFAULT NULL AFTER email;

-- Create site_settings table if it doesn't exist
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(255) DEFAULT 'Event Management System',
    logo_path VARCHAR(255) DEFAULT NULL,
    primary_color VARCHAR(7) DEFAULT '#6366f1',
    secondary_color VARCHAR(7) DEFAULT '#a855f7'
);

-- Insert default site settings if not exists
INSERT INTO site_settings (id, site_name, logo_path, primary_color, secondary_color)
VALUES (1, 'Event Management System', NULL, '#6366f1', '#a855f7')
ON DUPLICATE KEY UPDATE id=id;

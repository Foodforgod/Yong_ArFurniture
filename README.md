# Web-Based AR Furniture Catalog System

A professional, mobile-responsive, and secure e-commerce visualization platform built from scratch using native **PHP 8.3+**, **MySQL (PDO)**, and **Google `<model-viewer>`**. Designed for zero heavy framework bloat, leveraging pure web standards for rapid loading and seamless **Augmented Reality (AR)** integration.

---

## Key Features

* **Interactive 3D & AR Viewer**: Inspect 3D furniture models (`.glb` / `.usdz`) directly in the browser and preview them at true-to-life room scale on mobile devices (Android Scene Viewer and iOS AR Quick Look).
* **Category Filtering**: Seamless catalog organization with active category slugs and fallback error prevention.
* **Instant Mobile AR Handoff**: Automatic dynamic QR code generator on the storefront and product pages to transition desktop users instantly to mobile AR.
* **Authenticated Admin Dashboard**: Secure CRUD panel for managing products, attributes (dimensions, price, currency), categories, and file uploads.
* **Environment Configuration (`.env`)**: Secure separation of database credentials and system settings.

---

## Project Directory Structure


Step-by-Step Installation & Setup Tutorial
Follow these steps to set up and run the system locally using XAMPP:

Step 1: Place Files in your Local Server
Ensure your XAMPP control panel is running and Apache and MySQL are started.

Navigate to your local server directory (typically C:\xampp\htdocs\).

Create a folder named Yong_ArFurniture and place your project folder ar-furniture inside it. Your project path should look like:
C:\xampp\htdocs\Yong_ArFurniture\ar-furniture\

Step 2: Create the Database
Open your browser and go to http://localhost/phpmyadmin/.

Click on New in the left sidebar to create a database.

Name your database ar_furniture and set the collation to utf8mb4_unicode_ci, then click Create.

Run the following SQL queries in the SQL tab to set up the necessary tables

Populate it with your database and app configuration

How to Use the Website
Part 1: Customer Storefront (Normal Version)
<img width="1917" height="1145" alt="image" src="https://github.com/user-attachments/assets/572f9c68-626f-4ee4-9790-128759328690" />

URL: http://localhost/Yong_ArFurniture/ar-furniture/index.php

Features:
<img width="1311" height="122" alt="image" src="https://github.com/user-attachments/assets/b872f087-7469-4aca-aaa7-6be13e1befe8" />

Filter products by category using the filter buttons at the top.
<img width="1917" height="917" alt="image" src="https://github.com/user-attachments/assets/b46f4542-dff3-445c-aace-1f789242b6aa" />

Click "View in your space" on any product card to open the interactive 3D viewer (product.php).
<img width="461" height="377" alt="image" src="https://github.com/user-attachments/assets/ced5ef32-2c7d-4555-ad42-b54a207fa54b" />

On a desktop, scan the dynamic QR code using your phone camera to instantly load and test the Augmented Reality view on mobile.

Part 2: Admin Dashboard (Admin Version)
URL: http://localhost/Yong_ArFurniture/ar-furniture/admin/login.php
<img width="852" height="617" alt="image" src="https://github.com/user-attachments/assets/c8624afc-1277-4147-9047-51b5893d4fcd" />

Credentials: Username: admin | Password: password123

Features:
<img width="1917" height="995" alt="image" src="https://github.com/user-attachments/assets/c765fd3f-e4ae-40a0-8175-eb5449398f53" />

Manage inventory, add new furniture products with custom 3D model files (.glb), edit details, or remove inactive products.

Easily switch back and forth between administrative controls and the public storefront.

Deployment Guide (cPanel / Production)
Upload Files: Zip your project directory and upload it to your cPanel hosting via the File Manager into your public_html directory (or a subdomain folder).

Database Import: Create a MySQL database and user through cPanel's MySQL Database Wizard, then import your database schema using phpMyAdmin.

Update .env: Edit your production .env file with your live hosting database credentials and update APP_URL to your actual domain name.

File Permissions: Ensure that the models/ and uploads/ directories have write permissions (755 or 777) to allow file uploads for 3D assets and thumbnails.

**Laravel Easy Installer** V1.0 is a lightweight and customizable installer module for Laravel-based web applications. This package allows developers or clients to set up their Laravel application from scratch through a step-by-step web interface. It can be integrated into new or existing Laravel projects to automate environment setup, database configuration, and admin user creation.

This document covers all aspects of the installation, including the software required to run the Laravel Easy Installer V1.0  successfully on workstations. This manual assumes that the reader has the necessary expertise for the installation process.

**Requirements**

To install this installer , installation environment should meet following requirements:

| Resource Type        | Name     | Version / Notes                                |
| -------------------- | -------- | ---------------------------------------------- |
| Web Server           | Apache   | Minimum version 2.4                            |
| Database             | MySQL    | Minimum version 8.0                            |
| Server-side Language | PHP      | PHP 8.1+ with `mysqli`, `pdo_mysql` extensions |
| Package Manager      | Composer | Recommended version 2.x                        |
| Ports                | 80 / 443 | Must be open in the server firewall            |

### 📁 Copy Source Files

Place source in: `C:/xampp/htdocs/installer`

Unzip and ensure contents are directly inside the `installer/` directory:
--text
installer/
├── app/
├── bootstrap/
├── config/
├── public/
├── routes/
├── vendor/
├── .env
├── composer.json
└── index.php

🔧 Create Configuration File


cp .env.example .env

# Generate Application Key












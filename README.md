# File Transfer

A PHP-based file transfer application with admin authentication, file upload, viewing, downloading, and deletion.

## Features

- **Multiple file upload** — Upload multiple files at once (up to 5 MB each)
- **Admin authentication** — Password-protected login with cookie-based session (5-minute expiry, refreshed on each request)
- **File manager** — View, download, and delete uploaded files
- **Image/PDF preview** — Open images and PDFs in a new tab
- **Drag & drop** — Drag files directly onto the upload zone
- **Upload notifications** — Success/failure notifications via URL parameters

## Requirements

- PHP 7.4+
- PHP extensions: `fileinfo`, `mbstring`

## Setup

1. Copy the environment template:

   ```bash
   cp .env.example .env
   ```

2. Edit `.env` and set your admin password:

   ```
   ADMIN_PASSWORD=your_password_here
   ```

3. Ensure the web server has write permissions on the `uploads/` directory.

## Files

| File | Purpose |
|------|---------|
| `index.html` | Main upload page |
| `upload.php` | Handles regular file uploads |
| `login.php` | Login form and authentication |
| `admin.php` | Admin file manager dashboard |
| `serve.php` | Streams files for view/download |
| `delete.php` | Deletes files and folders |
| `auth.php` | Cookie authentication and refresh |
| `config.php` | Loads environment variables |
| `logout.php` | Clears session cookie |
| `style.css` | Shared stylesheet |

## Usage

1. Visit `index.html` to upload files
2. Click **Login** and enter the admin password
3. From the admin dashboard you can view, download, and delete uploaded files

## Security Notes

- The `.env` file contains the admin password and is gitignored
- File extensions are validated against an allowlist
- File names are sanitized to prevent path traversal
- Uploads are stored outside the web root in `uploads/`
# LearSphere - Online Learning Platform

faculty-staff-directory-final is a faculty and course directory application.

![PHP](https://img.shields.io/badge/PHP-2563EB?style=flat-square) ![MySQL](https://img.shields.io/badge/MySQL-0F172A?style=flat-square)

## Overview

The data model covers `admin`, `courses`, `faculty`, `faculty_courses`.

## Application areas

| Area | Pages in the repository |
| --- | --- |
| Admin | [`admin/add_course.php`](admin/add_course.php), [`admin/add_faculty.php`](admin/add_faculty.php), [`admin/admin_dashboard.php`](admin/admin_dashboard.php), [`admin/edit_course.php`](admin/edit_course.php), [`admin/edit_faculty.php`](admin/edit_faculty.php) |
| Root Pages | [`adminlogin.php`](adminlogin.php) |

## Getting started

### 1. Get the project

```bash
git clone https://github.com/nasirabbas827/faculty-staff-directory-final.git
cd faculty-staff-directory-final
```

### 2. Configure PHP and the database

1. Use a local PHP server with MySQL or MariaDB, such as XAMPP.
2. Import [`Database/faculty_db.sql`](Database/faculty_db.sql) into your local database server.
3. Set your local database connection details in [`admin/config.php`](admin/config.php), [`config.php`](config.php).
4. Keep the existing asset and upload directory paths intact.

### 3. Start the application

With the database running, start a local development server from the repository root:

```bash
php -S 127.0.0.1:8000
```

Open http://127.0.0.1:8000/index.php. If using Apache/XAMPP instead, place the project in its document root and open the matching localhost folder.

## Repository guide

| Path | Purpose |
| --- | --- |
| [`CSS`](CSS) | Stylesheets |
| [`Database`](Database) | Database scripts |
| [`config.php`](config.php) | Application/database configuration |
| [`images`](images) | Image assets |
| [`index.php`](index.php) | Web entry page |

## Development notes

These setup instructions are based on the repository source. The application has not been executed as part of this documentation update; verify dependencies and configuration in your local environment.

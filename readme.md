# Unity Care Clinic - Système de Gestion de Clinique

## 📋 Description
Système de gestion simple pour une clinique médicale développé en PHP procédural et MySQL.

## 🎯 Fonctionnalités
## Unity Care Clinic — Clinic Management (Sprint 3)

Simple PHP application for managing patients, departments and doctors. This repository contains the backend model code and a minimal public entry (`public/index.php`) used during Sprint 3 development.

**This README covers:** setup, project structure, database configuration, and how to run the app locally.

---

## Quick overview
- **Language:** PHP (procedural)
- **Database:** MySQL (PDO)
- **Purpose:** Educational / sprint demo for a clinic management system

---

## Requirements
- PHP 8.0+ with PDO extension
- MySQL 5.7+ or MariaDB
- Composer (optional, if you later add dependencies)
- Docker & Docker Compose (optional — recommended for consistent local setup)

---

## Project structure

See the main folders that matter now:

- `src/config/Database.php` — database connection class
- `src/models/Departments.php` — department model
- `src/models/Medecins.php` — doctors model
- `src/models/Patients.php` — patients model
- `public/index.php` — public entry / simple router

Keep changes focused in `src/` when adding features or fixing bugs.

---

## Configuration

1. Copy or create an environment file at the project root named `.env` (if not present) and set DB credentials:

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=clinic_db
DB_USER=clinic_user
DB_PASSWORD=change_me
```

2. Update `src/config/Database.php` to read these values (or ensure the file contains matching credentials). The project uses PDO; ensure credentials match between `.env` and `Database.php`.

Note: This repo does not include a full .env loader. Either set environment variables in your server or adapt `Database.php` to read from `.env` if you add a loader library.

---

## Running locally (no Docker)

1. Create the database and tables (example SQL omitted here — check `init.sql` if present).

2. Start a local PHP server from the project root:

```bash
php -S 127.0.0.1:8080 -t public
```

3. Open http://127.0.0.1:8080 in your browser.

---

## Running with Docker (recommended)

If a `docker-compose.yml` is provided in the repo, start services with:

```bash
docker-compose up -d
```

Common commands:

```bash
docker-compose ps
docker-compose logs -f
docker-compose down
docker-compose down -v  # removes volumes (data)
```

Access the app (adjust ports from your compose file):

- App: http://localhost:8080
- phpMyAdmin (if included): http://localhost:8081

---

## Database notes

- Expected tables: `patients`, `departments`, `medecins` (see `src/models` for column usage).
- Use prepared statements (PDO) — models in `src/models` already use PDO patterns.

If an `init.sql` exists in the repo, you can load it into your MySQL instance to create sample data.

---

## Usage (developer notes)

- Add a new patient: look at `Patients.php` model for `create`/`insert` methods and `public` for form handling.
- Read/list patients: `getAll()` or `find()` style methods in `Patients.php`.
- Update/delete: models provide methods for updating and removing entities; ensure proper validation in controller code.

Keep presentation (views) separate from models where possible; currently this repository uses simple procedural controllers in `public/`.

---

## Troubleshooting

- Connection refused: verify DB host/port and credentials in `src/config/Database.php` and `.env` (if used).
- Port conflict: change the host port in `docker-compose.yml` or the `php -S` command.
- Empty DB after rebuild: if using Docker, use `docker-compose down -v` to wipe volumes and ensure `init.sql` runs if configured.

---

## Next steps / TODOs

1. Add a lightweight router and move controller logic out of `public/index.php`.
2. Add input validation helpers and central error handling.
3. Add Composer and PSR-4 autoloading for models.
4. Add unit/integration tests for model methods.

---

## Contributing

1. Fork the repo, create a feature branch, and open a pull request.
2. Keep changes limited to one concern per PR.

---

## License

Repository is provided for educational purposes. No explicit license file included.

---

If you want, I can also:
- add a sample `docker-compose.yml` snippet,
- create `init.sql` from model fields,
- or convert `Database.php` to use environment variables automatically.

File: [readme.md](readme.md)

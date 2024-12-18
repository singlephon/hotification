# Changelog

All notable changes to `hotification` will be documented in this file

## [2.0.0] - 2024-12-18
### Changed
- **Revised package structure for observers and schedulers:**
    - The `models` and `scheduled_notifications` configurations, previously located in `config/hotification.php`, are now moved to separate classes:
        - **Models:** `App\Hotification\Models`.
        - **Schedulers:** `App\Hotification\Schedules`.

### Breaking Changes
- Developers using this package must take the following steps:
    1. Create the `App\Hotification` directory if it does not already exist.
    2. Add a `Models` class to define observers (examples are provided in the [README](README.md)).
    3. Add a `Schedules` class to configure schedulers (examples are provided in the [README](README.md)).
    4. Remove the `models` and `scheduled_notifications` sections from the `config/hotification.php` configuration file.
- Or run `php artisan hotification:install`

### Added
- A new behavior to `php artisan hotification:install` command  was added. It automatically creates the `App\Hotification` directory and copies default `Models.php` and `Schedules.php` files.

### Removed
- Models and scheduler settings are no longer defined in `config/hotification.php`.
- README_RU.md documentation.
---

## 1.0.0 - 2024-09-05

- initial release


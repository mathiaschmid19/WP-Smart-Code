# WP Smart Code — WordPress Code Manager

A modern, secure WordPress plugin for managing and executing PHP, JavaScript, CSS, and HTML code snippets.

## 📋 Features

- ✅ **Secure Snippet Management** — Create, edit, delete code snippets safely
- ✅ **Multiple Languages** — Support for PHP, JavaScript, CSS, and HTML
- ✅ **Database-Driven** — All snippets stored in custom WordPress table
- ✅ **Type-Safe** — Strict PHP 7.4+ typing throughout
- ✅ **REST API** — Full CRUD via REST endpoints
- ✅ **Admin Interface** — Modern React-based admin panel (in progress)
- ✅ **Conditional Execution** — Run snippets based on page/post/user rules
- ✅ **Import/Export** — Backup and migrate snippets as JSON
- ✅ **WordPress Standards** — Follows all WordPress best practices

## 🚀 Quick Start

### Installation

1. Download or clone the plugin to `/wp-content/plugins/edge-code-snippets/`
2. Activate the plugin via **Plugins** menu
3. Navigate to **WP Smart Code** in the admin menu

### Basic Usage

```php
// Get the plugin instance
$plugin = \ECS\Plugin::instance();
$snippet = $plugin->get_snippet();

// Create a snippet
$id = $snippet->create([
    'title'   => 'My Snippet',
    'slug'    => 'my-snippet',
    'type'    => 'php',
    'code'    => 'echo "Hello World!";',
    'active'  => true,
]);

// Retrieve a snippet
$data = $snippet->get($id);

// Update a snippet
$snippet->update($id, [
    'code' => 'echo "Updated!";',
]);

// Delete a snippet
$snippet->delete($id);
```

## 📁 Project Structure

```
edge-code-snippets/
├── edge-code-snippets.php          Main plugin file
├── uninstall.php                   Uninstall handler
├── readme.txt                      Plugin metadata
├── includes/
│   ├── class-ecs-autoloader.php    PSR-4 Autoloader
│   ├── class-ecs-plugin.php        Main plugin class
│   ├── class-ecs-db.php            Database management
│   ├── class-ecs-snippet.php       CRUD operations
│   ├── admin/                      (Step 3) Admin UI
│   ├── rest/                       (Step 5) REST API
│   └── class-ecs-sandbox.php       (Step 4) Code execution
├── languages/                      Translations
├── assets/                         (Step 3+) CSS/JS
└── README.md                       This file
```

## 🔧 Development Stages

### ✅ Completed (Steps 1-2)

- [x] Plugin initialization & bootstrap
- [x] PSR-4 autoloading
- [x] Database table creation (`wp_ecs_snippets`)
- [x] Complete CRUD model with filtering
- [x] Prepared statement security
- [x] Singleton pattern architecture

## 🗄️ Database Schema

```sql
CREATE TABLE wp_ecs_snippets (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    type VARCHAR(50) NOT NULL,
    code LONGTEXT NOT NULL,
    active TINYINT(1) NOT NULL DEFAULT 0,
    conditions LONGTEXT DEFAULT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    KEY idx_type (type),
    KEY idx_active (active),
    KEY idx_author_id (author_id),
    KEY idx_created_at (created_at)
);
```

**Snippet Types:** `php`, `js`, `css`, `html`

## 🔒 Security

- ✅ **Prepared Statements** — All queries use `$wpdb->prepare()`
- ✅ **Strict Types** — PHP strict mode enabled (`declare(strict_types=1)`)
- ✅ **Escaping** — Proper data escaping for frontend output
- ✅ **Nonces** — CSRF protection on REST endpoints (Step 5)
- ✅ **Capabilities** — Uses `manage_options` for access control
- ✅ **Debug Logging** — WP_DEBUG aware error logging

## 💻 Requirements

- **WordPress:** 5.9 or higher
- **PHP:** 7.4 or higher
- **MySQL/MariaDB:** 5.7+

To verify database creation:

```php
$db = \ECS\Plugin::instance()->get_db();
echo $db->table_exists() ? '✅ Table created' : '❌ Table missing';
```

## 📝 Standards

- **Namespace:** `ECS\`
- **Coding Standard:** [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- **Architecture:** Singleton plugin manager with dependency injection
- **Database:** WordPress `$wpdb` abstraction layer
- **Hooks:** WordPress action/filter system

## 🔗 Resources

- [WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)
- [REST API Handbook](https://developer.wordpress.org/rest-api/)
- [Block Editor Handbook](https://developer.wordpress.org/block-editor/)
- [Security Handbook](https://developer.wordpress.org/plugins/security/)

## 📄 License

GPL-2.0-or-later

See LICENSE file for details.

## 👤 Author

Built with ❤️ for the WordPress community

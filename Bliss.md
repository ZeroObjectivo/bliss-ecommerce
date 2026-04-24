# BLISS Website Architecture Study

Updated from the current codebase on 2026-04-25.

## 1. Executive Summary

BLISS is a custom PHP + MySQL e-commerce website built without Laravel, Symfony, Composer packages, or a frontend build step. It follows a hand-rolled MVC structure:

- Apache serves `public/`
- `public/.htaccess` rewrites requests into `public/index.php`
- `app/core/App.php` resolves `controller/method/params`
- Controllers call models and include PHP views
- Views render HTML directly and often contain inline CSS and JavaScript

The application covers:

- Public storefront
- Authentication
- Favorites
- Session-based cart and checkout
- Customer profile and orders
- Customer support inbox
- Admin dashboard
- Superadmin product and homepage management

This is a real working codebase, but it also contains architecture drift: several database fields/tables are referenced in code but not provisioned by the runtime bootstrap, one controller calls a model method that does not exist, and some older files/routes appear to be leftover from a previous iteration.

## 2. Tech Stack

### Backend

- PHP running under XAMPP / Apache
- Native PHP sessions via `session_start()`
- PDO for MySQL access
- Custom MVC, no framework
- Apache `mod_rewrite` routing via `public/.htaccess`

### Database

- MySQL database name: `bliss_ecommerce`
- Connection defaults in code:
  - host: `127.0.0.1`
  - user: `root`
  - password: empty string

### Frontend

- Server-rendered PHP templates
- Plain HTML, CSS, and JavaScript
- No npm, no webpack/vite, no TypeScript
- Shared JS file: `public/js/main.js`
- Shared and page CSS in `public/css/`

### External Assets / Services

- Google Fonts: `Inter`
- Google Maps iframe on the store page
- Placeholder social links on contact page
- Product images can be local uploads or absolute URLs stored in `products.image_main`

### Missing Tooling

- No `composer.json`
- No `package.json`
- No test suite
- No migration system
- No `.env`

## 3. Runtime Assumptions

The current code assumes:

- The site is mounted under `/php/Webdev/public`
- Apache points requests to `public/`
- `public/uploads/` and `public/uploads/profiles/` are writable
- MySQL is available locally
- PDO MySQL extension is enabled
- Sessions are enabled

Hardcoded `/php/Webdev/public` paths are used throughout controllers, views, JavaScript, stored fallback links, and reset-link generation, so this app is tightly coupled to the current local folder/URL structure.

## 4. Repository Shape

### Counts

- Total tracked files under `app/` and `public/`: 101
- Controllers: 11
- Models: 7
- Views: 36
- CSS files: 12
- JS files: 1
- Uploaded product/profile assets currently in repo: 29 upload files total, 4 profile uploads

### Top-Level Structure

```text
Bliss.md
app/
  init.php
  core/
    App.php
    Controller.php
    Database.php
  controllers/
    Admin.php
    Auth.php
    Cart.php
    Catalog.php
    Favorites.php
    Help.php
    Home.php
    Product.php
    Profile.php
    Store.php
    SuperAdmin.php
  models/
    FavoriteModel.php
    MessageModel.php
    OrderModel.php
    ProductModel.php
    SettingsModel.php
    User.php
    UserModel.php
  views/
    admin/
    auth/
    cart/
    catalog/
    favorites/
    help/
    home/
    product/
    profile/
    store/
    templates/
public/
  .htaccess
  index.php
  css/
    admin.css
    auth.css
    cart.css
    catalog.css
    contact.css
    filter.css
    help.css
    product.css
    profile.css
    shipping_returns.css
    store.css
    style.css
  js/
    main.js
  uploads/
    *.png
    profiles/
      *.png
      *.jpg
```

## 5. Request Lifecycle

### Entry and Routing

Request flow:

1. Browser requests a URL under `public/`
2. `public/.htaccess` rewrites non-file and non-directory requests to `index.php?url=...`
3. `public/index.php` loads `../app/init.php`
4. `app/init.php` starts the PHP session and loads core files
5. `app/core/App.php` parses `$_GET['url']`
6. The first URL segment maps to a controller file in `app/controllers/`
7. The second URL segment maps to a method if it exists
8. Remaining URL segments become method parameters
9. Controller loads models and views manually

### Core Files

- `public/index.php`
  - App entrypoint
  - Only bootstraps and instantiates `App`

- `app/init.php`
  - Starts session
  - Includes `App.php`, `Controller.php`, `Database.php`

- `app/core/App.php`
  - Default controller: `Home`
  - Default method: `index`
  - Uses `ucfirst($url[0])` to find controller filenames
  - Uses `call_user_func_array()` for dispatch
  - No route registry, middleware pipeline, or HTTP verb routing

- `app/core/Controller.php`
  - `model($name)` does `require_once ../app/models/...`
  - `view($path, $data)` does `require_once ../app/views/...`
  - Does not extract `$data`; views read from `$data` directly

- `app/core/Database.php`
  - Creates database if missing on every connection attempt
  - Opens PDO with persistent connections
  - Includes limited auto-table/patch logic for messaging tables
  - Provides a minimal wrapper around prepared statements

## 6. Layered Architecture

### 6.1 Controllers

Controllers are the main orchestration layer. They do authentication checks, redirect handling, session mutation, model calls, and page rendering.

- `Home.php`
  - Homepage hero selection
  - Featured, new-arrival, and best-seller sections
  - Uses `ProductModel` and `SettingsModel`

- `Catalog.php`
  - Product listing by filter/category
  - Search JSON endpoint for live header search

- `Product.php`
  - Single product page by ID

- `Store.php`
  - Static "Find a Store" page

- `Auth.php`
  - Login, register, logout
  - Password reset token lookup and reset form submission
  - Loads favorite IDs into session on login

- `Favorites.php`
  - Favorite list page
  - Toggle favorite
  - Clear all favorites

- `Cart.php`
  - Cart page
  - Add/update/remove/clear items
  - Checkout page and order processing
  - Address creation during checkout
  - Order success page

- `Help.php`
  - Help center pages
  - Contact / shipping-return support message submission

- `Profile.php`
  - Customer account
  - Profile info, avatar, password
  - Address book
  - Orders and order detail
  - Confirm receipt
  - Support inbox, reply, archive, delete, polling endpoints

- `Admin.php`
  - Admin dashboard
  - Customer listing
  - Order management
  - Support inbox for staff

- `SuperAdmin.php`
  - Product inventory CRUD
  - Homepage hero settings
  - Fallback campaign CRUD
  - Announcement-bar management
  - Customer activation/deletion/password reset-link generation

### 6.2 Models

Each model creates its own `Database` instance.

- `User.php`
  - Small auth model
  - `findUserByEmail()`
  - `register()`

- `UserModel.php`
  - Admin/customer profile model
  - User lookup, reset tokens, profile updates
  - Address CRUD

- `ProductModel.php`
  - Product list/search/CRUD
  - Featured/new/best-seller queries
  - Size-stock deduction

- `OrderModel.php`
  - Order list/detail/status
  - Order creation and item creation
  - Archive/unarchive

- `FavoriteModel.php`
  - Favorite add/remove/list/clear

- `MessageModel.php`
  - Support ticket creation
  - Replies
  - Unread counts
  - Archive/delete/status changes

- `SettingsModel.php`
  - Homepage fallback campaigns
  - Announcement-bar content
  - Generic site settings

### 6.3 Views

Views are large PHP templates and are not "logic-free". Several views contain:

- inline `<style>` blocks
- inline `<script>` blocks
- loops and conditional display logic
- direct model access from templates

Important shared templates:

- `app/views/templates/header.php`
  - Public header
  - Loads most storefront CSS globally
  - Queries `SettingsModel`, `UserModel`, and `MessageModel` directly inside the view

- `app/views/templates/footer.php`
  - Footer
  - Injects the global quick-add modal
  - Loads `public/js/main.js`

- `app/views/templates/admin_header.php`
  - Admin shell with sidebar/topbar/theme toggle
  - Queries `MessageModel` directly for unread count

- `app/views/templates/admin_footer.php`
  - Closes admin layout

- `app/views/templates/product_card.php`
  - Reusable product grid card

## 7. Route Map

### Public Storefront

- `/`
  - `Home::index()`

- `/catalog`
  - `Catalog::index()`
  - Supports query params:
    - `?filter=featured`
    - `?filter=new`
    - `?filter=best`
    - `?category=Running` etc.

- `/catalog/search_ajax`
  - `Catalog::search_ajax()`
  - Returns JSON for header search dropdown

- `/product/detail/{id}`
  - `Product::detail($id)`

- `/store`
  - `Store::index()`

- `/help`
  - `Help::index()`

- `/help/contact`
  - `Help::contact()`

- `/help/shipping_returns`
  - `Help::shipping_returns()`

- `/help/send_concern`
  - `Help::send_concern()`

### Authentication

- `/auth/login`
- `/auth/register`
- `/auth/process_login`
- `/auth/process_register`
- `/auth/logout`
- `/auth/reset/{token}`
- `/auth/process_reset`

### Favorites

- `/favorites`
- `/favorites/toggle`
- `/favorites/clear`

### Cart / Checkout

- `/cart`
- `/cart/add`
- `/cart/update_quantity`
- `/cart/remove?key=...`
- `/cart/clear`
- `/cart/checkout`
- `/cart/add_address`
- `/cart/process`
- `/cart/success/{order_id}`

### Customer Profile

- `/profile`
- `/profile/update_info`
- `/profile/update_avatar`
- `/profile/update_password`
- `/profile/add_address`
- `/profile/edit_address/{id}`
- `/profile/delete_address/{id}`
- `/profile/set_default_address/{id}`
- `/profile/orders`
- `/profile/order_details/{id}`
- `/profile/confirm_receipt/{id}`
- `/profile/inbox`
- `/profile/reply_ticket`
- `/profile/fetch_new_replies/{ticket_id}/{last_id}`
- `/profile/mark_as_read/{id}`
- `/profile/message_archive/{id}`
- `/profile/message_unarchive/{id}`
- `/profile/message_delete/{id}`

### Admin

- `/admin`
  - actual admin dashboard route

- `/admin/customers`
- `/admin/orders`
- `/admin/order_detail/{id}`
- `/admin/order_update`
- `/admin/orders_clear_all`
- `/admin/inbox`
- `/admin/reply`
- `/admin/fetch_new_replies/{ticket_id}/{last_id}`
- `/admin/mark_as_read/{id}`
- `/admin/message_archive/{id}`
- `/admin/message_unarchive/{id}`
- `/admin/message_status`
- `/admin/message_delete/{id}`

### Superadmin

- `/superadmin/products`
- `/superadmin/product_add`
- `/superadmin/product_edit/{id}`
- `/superadmin/product_delete/{id}`
- `/superadmin/hero_settings`
- `/superadmin/fallback_add`
- `/superadmin/fallback_edit/{id}`
- `/superadmin/fallback_delete/{id}`
- `/superadmin/fallback_activate/{id}`
- `/superadmin/customer_status`
- `/superadmin/customer_delete/{id}`
- `/superadmin/customer_reset_pass`

## 8. Feature Modules

### Homepage / Landing

Files:

- `app/controllers/Home.php`
- `app/views/home/index.php`
- `public/css/style.css`
- `public/js/main.js`

Behavior:

- If a featured product exists in `featured_products`, it becomes the animated hero
- If not, the active fallback campaign is used
- Below the hero are product grids for:
  - featured products
  - new arrivals
  - best sellers

Important implementation detail:

- Hero animation is driven by shared JS in `public/js/main.js`
- Fallback CTA links are normalized in the controller with hardcoded base path logic

### Catalog

Files:

- `app/controllers/Catalog.php`
- `app/views/catalog/index.php`
- `public/css/catalog.css`
- `public/css/filter.css`

Behavior:

- Initial product set is server-rendered
- Sidebar category links are server-backed
- Price filter, stock filter, and sort are client-side only
- Catalog page search is client-side only
- Separate header live search uses AJAX endpoint `/catalog/search_ajax`

### Product Detail

Files:

- `app/controllers/Product.php`
- `app/views/product/view.php`
- `public/css/product.css`

Behavior:

- Shows image, brand, category, description, sizes
- Size buttons unlock `Add to Cart` and `Buy Now`
- Submission is AJAX
- "Buy Now" currently still routes to cart, not directly to checkout

### Favorites

Files:

- `app/controllers/Favorites.php`
- `app/models/FavoriteModel.php`
- `app/views/favorites/index.php`

Behavior:

- Favorites are persisted in MySQL
- Favorite IDs are cached in `$_SESSION['favorites_list']`
- Favorite page reuses cart-like layout
- "Move to Cart" opens the shared quick-add modal

### Cart and Checkout

Files:

- `app/controllers/Cart.php`
- `app/views/cart/index.php`
- `app/views/cart/checkout.php`
- `app/views/cart/success.php`
- `public/css/cart.css`
- `public/js/main.js`

Behavior:

- Cart is stored only in session, not in database
- Session shape:
  - key: `productId_size`
  - value: quantity
- Stock validation happens before quantity updates and during checkout
- Checkout is a 3-step client-side wizard:
  - address
  - shipping
  - payment
- Orders are persisted in MySQL, then stock is reduced per size

Important note:

- the live app has no database-backed cart model or query path; cart storage is session-only

### Help Center and Contact

Files:

- `app/controllers/Help.php`
- `app/views/help/index.php`
- `app/views/help/contact.php`
- `app/views/help/shipping_returns.php`
- `public/css/help.css`
- `public/css/contact.css`
- `public/css/shipping_returns.css`

Behavior:

- Help index includes FAQ search/filtering in JS
- Contact and shipping pages have AJAX support forms
- Support submission requires login

### Customer Profile and Orders

Files:

- `app/controllers/Profile.php`
- `app/views/profile/account.php`
- `app/views/profile/orders.php`
- `app/views/profile/order_detail.php`
- `public/css/profile.css`

Behavior:

- Personal info update
- Avatar upload
- Password change
- Address book management
- Orders grouped by status
- Delivered orders can be confirmed as completed

### Customer Support Inbox

Files:

- `app/controllers/Profile.php`
- `app/models/MessageModel.php`
- `app/views/profile/inbox.php`

Behavior:

- Ticket list + conversation detail layout
- Tickets grouped by active/resolved/archived
- AJAX reply submission
- Polling every 4 seconds for new replies
- Read/unread state updated via AJAX endpoint

### Admin Dashboard

Files:

- `app/controllers/Admin.php`
- `app/views/admin/dashboard.php`
- `public/css/admin.css`

Behavior:

- Aggregates totals:
  - products
  - orders
  - revenue
  - customers
- Shows:
  - recent orders
  - revenue by category
  - top selling products
  - low stock items
  - recent customers

### Admin Orders

Files:

- `app/controllers/Admin.php`
- `app/views/admin/orders.php`
- `app/views/admin/order_detail.php`

Behavior:

- Search
- Sort
- Status tab filters
- Status updates
- Detail page
- "Delete all orders" action

### Admin Inbox

Files:

- `app/controllers/Admin.php`
- `app/views/admin/inbox.php`

Behavior:

- Similar architecture to customer inbox
- Staff can reply, resolve, archive, delete
- Polls every 4 seconds for new replies

### Superadmin Product Management

Files:

- `app/controllers/SuperAdmin.php`
- `app/views/admin/products.php`
- `app/views/admin/product_add.php`
- `app/views/admin/product_edit.php`

Behavior:

- Product CRUD
- Image upload
- Size inventory stored as JSON
- Status flags stored as text inside category string:
  - `Featured`
  - `New Arrival`
  - `Best Seller`

### Superadmin Hero / Announcement Settings

Files:

- `app/controllers/SuperAdmin.php`
- `app/models/SettingsModel.php`
- `app/views/admin/hero_settings.php`
- `app/views/admin/fallback_add.php`
- `app/views/admin/fallback_edit.php`

Behavior:

- Set featured hero product
- Edit hero gradient
- Manage fallback homepage campaigns
- Manage announcement bar messages
- Toggle announcement bar visibility
- Save announcement bar background color

## 9. Database Design

This is the canonical runtime schema implied by the live codebase. It is derived from:

- SQL in `app/models/*`
- runtime behavior in `app/core/Database.php`
- controller expectations
- fields read by the views

### 9.1 Runtime Schema Contract

Engine assumptions for the SQL below:

- MySQL 8.x
- `InnoDB`
- `utf8mb4`

Canonical schema:

```sql
CREATE DATABASE IF NOT EXISTS bliss_ecommerce
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bliss_ecommerce;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('user', 'admin', 'superadmin') NOT NULL DEFAULT 'user',
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  reset_token VARCHAR(255) NULL,
  reset_expires DATETIME NULL,
  username VARCHAR(100) NULL,
  profile_picture VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_users_email (email),
  UNIQUE KEY uq_users_reset_token (reset_token),
  KEY idx_users_role_status (role, status),
  KEY idx_users_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT NULL,
  price DECIMAL(10, 2) NOT NULL,
  category VARCHAR(255) NULL,
  brand VARCHAR(100) NULL,
  sizes JSON NOT NULL,
  image_main TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_products_created_at (created_at),
  KEY idx_products_brand (brand)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE featured_products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id INT UNSIGNED NOT NULL,
  bg_gradient VARCHAR(255) NOT NULL DEFAULT 'linear-gradient(135deg, #0f172a 0%, #334155 100%)',
  UNIQUE KEY uq_featured_products_product_id (product_id),
  CONSTRAINT fk_featured_products_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favorites (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_favorites_user_product (user_id, product_id),
  KEY idx_favorites_created_at (created_at),
  CONSTRAINT fk_favorites_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_favorites_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE homepage_fallbacks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  campaign_name VARCHAR(100) NOT NULL,
  badge_text VARCHAR(50) NULL,
  hero_title VARCHAR(100) NULL,
  hero_subtitle VARCHAR(255) NULL,
  tagline VARCHAR(100) NULL,
  description TEXT NULL,
  category_pill VARCHAR(50) NULL,
  action_headline VARCHAR(100) NULL,
  btn1_text VARCHAR(50) NULL,
  btn1_link VARCHAR(255) NULL,
  btn2_text VARCHAR(50) NULL,
  btn2_link VARCHAR(255) NULL,
  num_buttons TINYINT UNSIGNED NOT NULL DEFAULT 2,
  bg_gradient VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 0,
  KEY idx_homepage_fallbacks_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_addresses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  street_address VARCHAR(255) NOT NULL,
  city VARCHAR(120) NOT NULL,
  province VARCHAR(120) NOT NULL,
  postal_code VARCHAR(20) NOT NULL,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  category VARCHAR(50) NOT NULL DEFAULT 'Home Address',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_user_addresses_user_default_created (user_id, is_default, created_at),
  CONSTRAINT fk_user_addresses_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE orders (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  shipping_method VARCHAR(50) NULL,
  payment_method VARCHAR(50) NULL,
  shipping_address TEXT NULL,
  status ENUM('pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled')
    NOT NULL DEFAULT 'pending',
  is_archived TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_orders_user_created (user_id, created_at),
  KEY idx_orders_status_archived (status, is_archived),
  CONSTRAINT fk_orders_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE order_items (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  size VARCHAR(20) NULL,
  quantity INT UNSIGNED NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  KEY idx_order_items_order (order_id),
  KEY idx_order_items_product (product_id),
  CONSTRAINT fk_order_items_order
    FOREIGN KEY (order_id) REFERENCES orders(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_order_items_product
    FOREIGN KEY (product_id) REFERENCES products(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  ticket_number VARCHAR(50) NOT NULL,
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('active', 'resolved') NOT NULL DEFAULT 'active',
  is_read_admin TINYINT(1) NOT NULL DEFAULT 0,
  is_read_user TINYINT(1) NOT NULL DEFAULT 0,
  is_archived_user TINYINT(1) NOT NULL DEFAULT 0,
  is_archived_admin TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_messages_ticket_number (ticket_number),
  KEY idx_messages_user_updated (user_id, updated_at),
  KEY idx_messages_admin_queue (is_read_admin, is_archived_admin, updated_at),
  KEY idx_messages_user_queue (is_read_user, is_archived_user, updated_at),
  CONSTRAINT fk_messages_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE message_replies (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  message_id INT UNSIGNED NOT NULL,
  sender_id INT UNSIGNED NOT NULL,
  reply_text TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_message_replies_message_created (message_id, created_at),
  KEY idx_message_replies_sender (sender_id),
  CONSTRAINT fk_message_replies_message
    FOREIGN KEY (message_id) REFERENCES messages(id)
    ON DELETE CASCADE,
  CONSTRAINT fk_message_replies_sender
    FOREIGN KEY (sender_id) REFERENCES users(id)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE announcements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  message VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  KEY idx_announcements_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE site_settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 9.2 Active Data Model Notes

- `products.sizes` is a JSON stock map keyed by size label, such as `{"US 7": 10, "US 8": 5}`.
- `products.category` is a denormalized comma-separated field that mixes merchandising categories with labels like `Featured`, `New Arrival`, and `Best Seller`.
- `featured_products` is the source of the homepage hero product and gradient metadata.
- `homepage_fallbacks` stores non-product homepage campaigns when no hero product is active.
- `messages` + `message_replies` implement the customer/admin threaded support inbox.
- `site_settings` currently stores at least:
  - `announcement_bg_color`
  - `announcement_bar_enabled`

### 9.3 Objects Not in the Active Database Contract

The live application does not use a database cart.

- there is no cart model
- there is no active cart query path
- cart state lives entirely in `$_SESSION['cart']`

So a `cart` table is not part of the active runtime architecture.

### 9.4 Known Schema/Migration Gap

The codebase still lacks a complete migration layer that provisions the entire schema above.

Current runtime gap:

- `app/core/Database.php` only bootstraps part of the messaging subsystem
- unread message columns used by `MessageModel` are not created there
- `user_addresses`, `announcements`, `site_settings`, `users.username`, and `users.profile_picture` are assumed to already exist
- `Database.php` still creates `messages.status` using an older `open/replied/closed` enum, while the active inbox flows use `active/resolved`

So the app has a real runtime database contract, but not a fully reliable in-repo migration implementation for it.

## 10. Session and State Management

Session keys used by the app:

- `user_id`
- `user_name`
- `user_role`
- `user_picture`
- `favorites_list`
- `cart`

### Cart State

- Stored in `$_SESSION['cart']`
- Structure: `productId_size => quantity`
- Example: `12_US 9 => 2`

### Favorites State

- Source of truth: MySQL `favorites` table
- Session cache: `$_SESSION['favorites_list']`
- Loaded on login and maintained during favorite/cart actions

### Auth / Authorization

- No dedicated middleware class
- Auth checks live in controller constructors or methods
- `Profile` constructor requires logged-in user
- `Admin` constructor requires `admin` or `superadmin`
- `SuperAdmin` constructor requires exact `superadmin`

## 11. Frontend Architecture

### CSS Organization

#### Global Public CSS

`header.php` loads these for all public pages:

- `style.css`
- `catalog.css`
- `auth.css`
- `product.css`
- `cart.css`
- `filter.css`
- `store.css`
- `help.css`
- `contact.css`
- `shipping_returns.css`

This means most storefront pages download CSS for unrelated sections.

#### Profile CSS

- `profile.css`
- Included directly inside profile views, not via the shared public header

#### Admin CSS

- `admin.css`
- Loaded only by `admin_header.php`
- Supports light and dark theme via `data-theme`

### JavaScript Organization

#### Shared JS: `public/js/main.js`

Handles:

- global header live search
- help page FAQ/category filtering
- homepage sticky hero scroll animation
- reveal-on-scroll via `IntersectionObserver`
- quick-add modal open/close and AJAX submit
- toast notifications
- cart quantity AJAX updates

#### Page-local JS inside views

Many pages define their own inline scripts:

- catalog filter/sort UI
- product detail size and cart flow
- checkout stepper
- help/contact AJAX forms
- customer inbox polling/replies
- admin inbox polling/replies
- admin orders and customers sort/filter
- hero settings gradient preview

### UI Composition

Public shell:

- top utility bar
- announcement bar
- sticky glass header
- page content
- footer
- shared quick-add modal

Admin shell:

- fixed sidebar
- sticky topbar
- content area
- theme toggle in `localStorage`

## 12. File-by-Folder Breakdown

### `app/core/`

- `App.php`
  - Router/dispatcher
- `Controller.php`
  - Base controller with model/view loaders
- `Database.php`
  - PDO wrapper and partial runtime schema bootstrap

### `app/controllers/`

- `Home.php`
  - Storefront landing page
- `Catalog.php`
  - Product catalog and search endpoint
- `Product.php`
  - Product detail
- `Store.php`
  - Physical store page
- `Auth.php`
  - Login/register/reset
- `Favorites.php`
  - Favorite list/toggle/clear
- `Cart.php`
  - Cart and checkout
- `Help.php`
  - Help/contact/support submission
- `Profile.php`
  - Customer account, orders, inbox
- `Admin.php`
  - Admin dashboard/orders/customers/inbox
- `SuperAdmin.php`
  - Inventory/homepage/customer management

### `app/models/`

- `User.php`
  - Auth-specific queries
- `UserModel.php`
  - Profile/admin user operations
- `ProductModel.php`
  - Product and merch queries
- `OrderModel.php`
  - Order persistence
- `FavoriteModel.php`
  - Favorites persistence
- `MessageModel.php`
  - Support ticket system
- `SettingsModel.php`
  - Fallback campaigns and announcement settings

### `app/views/templates/`

- `header.php`
  - Public shell/header/nav/search/profile dropdown
- `footer.php`
  - Public footer and shared quick-add modal
- `product_card.php`
  - Reusable product grid tile
- `admin_header.php`
  - Admin sidebar/topbar shell
- `admin_footer.php`
  - Admin shell close

### `app/views/home/`

- `index.php`
  - Animated hero and product sections

### `app/views/catalog/`

- `index.php`
  - Filter sidebar + grid + client filtering

### `app/views/product/`

- `view.php`
  - Product detail and AJAX add to cart

### `app/views/cart/`

- `index.php`
  - Cart list and summary
- `checkout.php`
  - 3-step checkout wizard
- `success.php`
  - Order confirmation page

### `app/views/favorites/`

- `index.php`
  - Favorite products list

### `app/views/help/`

- `index.php`
  - FAQ/help center + contact modal
- `contact.php`
  - Contact/support page
- `shipping_returns.php`
  - Policy page + support form

### `app/views/profile/`

- `account.php`
  - Account settings and addresses
- `orders.php`
  - Orders grouped by status
- `order_detail.php`
  - Single order view
- `inbox.php`
  - Customer support messenger

### `app/views/admin/`

- `dashboard.php`
  - Admin summary dashboard
- `customers.php`
  - Customer management
- `orders.php`
  - Order management
- `order_detail.php`
  - Order detail/status
- `inbox.php`
  - Staff support messenger
- `products.php`
  - Inventory listing
- `product_add.php`
  - Add product form
- `product_edit.php`
  - Edit product form
- `hero_settings.php`
  - Hero/fallback/announcement management
- `fallback_add.php`
  - Add fallback campaign
- `fallback_edit.php`
  - Edit fallback campaign
- `reset_link.php`
  - Displays generated password reset link
- `featured.php`
  - Legacy/unused featured page

### `public/css/`

- `style.css`
  - Main public visual system and many shared/storefront components
- `admin.css`
  - Admin dashboard theme/layout/components
- `auth.css`
  - Login/register/reset styling
- `cart.css`
  - Cart/checkout shared styles
- `catalog.css`
  - Catalog page styles
- `contact.css`
  - Contact page styles
- `filter.css`
  - Small helper stylesheet for catalog filtering UI
- `help.css`
  - Help center page styles
- `product.css`
  - Product detail page styles
- `profile.css`
  - Profile/account/orders/inbox styles
- `shipping_returns.css`
  - Shipping/returns page styles
- `store.css`
  - Store page styles

### `public/js/`

- `main.js`
  - Shared storefront interactions

### `public/uploads/`

- Contains uploaded product images
- `profiles/` contains uploaded user avatars
- Uploads are currently committed into the repository

## -----------------------------------

## 13. Current Architecture Notes and Gaps

These are important if the goal is to understand the system as it exists today, not as originally intended.

### 13.1 Hardcoded Base Path

The codebase is heavily coupled to `/php/Webdev/public`.

Impact:

- difficult to deploy under a different host/path
- fallback CTA links are stored with local path assumptions
- JS fetch URLs are not environment-agnostic

### 13.2 Schema Drift

There is a mismatch between:

- `app/core/Database.php`
- the runtime schema the app actually expects
- the live models and views

This affects:

- support message statuses
- unread flags
- addresses
- announcement settings
- profile fields

### 13.3 Missing Model Method

`SuperAdmin::hero_settings()` calls:

- `ProductModel->updateFeaturedGradient(...)`

That method does not exist in `ProductModel.php`.

Impact:

- saving hero gradient from the admin UI should currently fail

### 13.4 View Layer Talks to the Database

`header.php` and `admin_header.php` instantiate models directly to fetch:

- active announcements
- site settings
- current user info
- unread ticket counts

Impact:

- database logic is split across controllers and views
- more than one DB connection/model instance may be created per request
- view rendering is no longer passive/pure

### 13.5 Session Cart Only

- Session cart is the live implementation
- there is no active database cart layer in the current code

Impact:

- carts are not persistent across devices/sessions
- cart behavior is tied directly to session lifetime

### 13.6 Product Data Modeling Is Denormalized

- `products.category` mixes:
  - product type
  - marketing status
- `products.sizes` stores stock JSON in one field

Impact:

- filtering relies on string matching
- inventory is harder to query/report on
- statuses are not first-class relational data

### 13.7 Order Write Path Has No Transaction

Checkout currently:

- creates order
- inserts order items
- subtracts stock
- clears session cart

This is done without a database transaction.

Impact:

- partial writes are possible if one step fails

### 13.8 Route / File Leftovers

Observed leftovers:

- `app/views/admin/featured.php` appears unused
- it references `/superadmin/featured`, but no such controller method exists
- profile dropdown links to `/admin/dashboard`, while the actual admin dashboard route is `/admin`

### 13.9 Header Loads Broad CSS Bundle

The public header loads many page styles globally, even when irrelevant to the current page.

Impact:

- larger CSS payload
- more cross-page style coupling

### 13.10 No Full Migration Layer

The app has a larger runtime schema than the codebase provisions automatically.

Impact:

- environment setup depends on pre-existing tables/columns
- schema drift is easier to introduce
- onboarding and deployment are less predictable

## 14. Current Runtime Bring-Up

The live app currently assumes:

1. Apache and MySQL are running in XAMPP
2. The app is served from `/php/Webdev/public`
3. Database `bliss_ecommerce` is reachable with the credentials in `app/core/Database.php`
4. All operational tables and columns listed in Section 9 already exist
5. Browse the site at `/php/Webdev/public/`

Important note:

- the repo does not currently contain a trustworthy full migration/bootstrap path for that schema

## 15. Architectural Bottom Line

BLISS is a custom monolithic PHP MVC web app with server-rendered pages, a MySQL backend, and a small amount of shared JavaScript for interactivity. It is organized clearly enough to navigate by feature, but it is not yet strongly standardized:

- routing is simple and file-based
- controllers are the main business layer
- models are thin SQL wrappers
- views contain a meaningful amount of presentation logic and client behavior
- sessions hold authentication state and the live cart
- MySQL stores products, orders, favorites, tickets, and homepage configuration

The strongest structural qualities are:

- easy-to-follow folder organization
- clear feature boundaries
- functional admin and superadmin separation
- reusable product-card and shell templates

The main architectural weaknesses are:

- schema drift
- hardcoded paths
- mixed concerns in views
- denormalized product modeling
- missing migrations/transactions/config abstraction

If you want, the next useful step would be turning this into a second document, for example:

- `Bliss-Audit.md` for bugs and risks
- `Bliss-Routes.md` for a pure route inventory
- `Bliss-DB.md` for a corrected database schema and migration plan

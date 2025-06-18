# 🛍️ Laravel Products & Categories CRUD Application

This is a full-featured Laravel CRUD application that manages **Products** and **Categories** with a **many-to-many relationship**, styled using **Tailwind CSS**. It provides a clean and responsive UI for product management in modern web applications.

---

## 🚀 Features

- 🧾 **Product Management** (Create, Read, Update, Delete)
- 📂 **Category Management**
- 🔗 **Many-to-Many Relationship** between Products and Categories
- 💡 Tailwind CSS integration for modern UI design
- 📊 Dynamic Category assignment via multiselect dropdown
- 🧼 Clean, modular, and scalable code structure

---

## 🧰 Tech Stack

- **Framework**: Laravel 12
- **Frontend**: Blade Templates, Tailwind CSS
- **Database**: MySQL
---
## ⚙️ Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/mdomor527461/product-category-crud.git
cd product-category-crud
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install NPM Dependencies & Compile Assets

```bash
npm install
npm run dev
```

### 4. Configure `.env` File

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

Update the following values in `.env`:

```env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Generate App Key & Migrate

```bash
php artisan key:generate
php artisan migrate
```

### 6. Run the Development Server

```bash
php artisan serve
```





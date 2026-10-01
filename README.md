# Rumah Jahit Lina Sales Information System

Web-based sales and inventory information system developed by **Muliadi** as a Bachelor's thesis project in the Information Systems Program, Faculty of Computer Science, Universitas Katolik Santo Thomas.

> Academic portfolio repository. Sensitive credentials, private customer data, runtime uploads, and production database records are intentionally excluded from this public repository.

## Project Overview

The system was built to digitize sales, inventory, customer, and reporting workflows for Rumah Jahit Lina. It supports multiple user roles and centralizes product, stock, transaction, invoice, and reporting data in a web application.

## Main Features

- Authentication and password reset
- Admin, employee, and customer workflows
- Product and category management
- Product variants and image management
- Inventory and stock history
- Shopping cart and checkout
- Shipping cost integration
- Midtrans payment integration
- Transaction and invoice management
- Customer and employee data management
- Sales reporting
- Ratings and reviews

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL
- Blade
- HTML / CSS / JavaScript
- Bootstrap / Tailwind CSS
- Vite
- Midtrans PHP SDK
- RajaOngkir / Komerce API

## Development Method

The project was developed using the **Waterfall** method as part of the thesis titled:

**Sistem Informasi Penjualan pada Rumah Jahit Lina Berbasis Web Menggunakan Metode Waterfall**

The development process covered requirements analysis, system design, implementation, testing, deployment, and maintenance planning.

## Testing

The thesis documented **22 Black Box test scenarios**, with all 22 scenarios recorded as successful.

## Local Setup

```bash
git clone https://github.com/Muly-Adi/rumah-jahit-lina-sales-system.git
cd rumah-jahit-lina-sales-system
composer install
cp .env.example .env
php artisan key:generate
npm install
php artisan migrate --seed
npm run dev
php artisan serve
```

Then configure your local database and optional third-party Sandbox credentials in `.env`.

## Environment Variables

The application uses environment variables for external services. Do **not** commit real credentials.

```env
MIDTRANS_SERVERKEY=
MIDTRANS_CLIENTKEY=
RAJAONGKIR_API_KEY=
RAJAONGKIR_ORIGIN_DISTRICT=
```

## Privacy and Data

The original academic database included development and transaction records. Those records are not included in this public portfolio version. Use migrations, seeders, and dummy data for local demonstrations.

## Academic Context

- **Developer:** Muliadi
- **Program:** Bachelor of Information Systems
- **Faculty:** Faculty of Computer Science
- **University:** Universitas Katolik Santo Thomas
- **Project Type:** Bachelor's Thesis / Academic Project

## Third-Party Assets

This project uses open-source libraries and may include interface assets obtained during development. Their original licenses and attribution requirements must be respected. Assets without clear redistribution permission should not be republished in the public repository.

## License

No open-source license is granted for the project-specific source code in this repository. Third-party packages remain subject to their respective licenses.

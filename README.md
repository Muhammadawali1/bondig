<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
  <h1 align="center">Sistem Manajemen Bon Barang</h1>
  <p align="center">Aplikasi Web-Based untuk Manajemen Permintaan dan Persetujuan Barang</p>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Version-1.0.0-blue.svg" alt="Version">
  <img src="https://img.shields.io/badge/Laravel-12.0-red.svg" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## Tentang Project

Sistem Manajemen Bon Barang adalah aplikasi web berbasis Laravel yang dirancang untuk mengotomatiskan dan menyederhanakan proses permintaan barang dalam organisasi. Sistem ini memfasilitasi alur kerja dari pengajuan permintaan oleh pegawai hingga persetujuan multi-level dan pemenuhan oleh gudang.

## Fitur Utama

### **Multi-Level Approval Workflow**
- Pegawai mengajukan permintaan barang
- Atasan melakukan persetujuan awal
- Gudang melakukan verifikasi stok dan pemenuhan
- Administrator monitoring dan oversight

### **Role-Based Access Control**
- **Administrator**: Manajemen sistem, monitoring, laporan
- **Atasan**: Persetujuan permintaan, monitoring tim
- **Gudang**: Manajemen stok, pemenuhan permintaan
- **Pegawai**: Pengajuan permintaan, tracking status

### **Manajemen Inventaris**
- Real-time stock tracking
- Automatic stock deduction
- Barang management dengan kategori
- History dan audit trail lengkap

### **Notifikasi Real-Time**
- Email notifications untuk setiap status update
- Dashboard notifications
- Status tracking untuk semua stakeholder

### **Reporting & Analytics**
- Laporan permintaan per periode
- Statistik per departemen
- Export data ke Excel
- Visual dashboard

## Teknologi

### **Backend**
- **Framework**: Laravel 12.0
- **Language**: PHP 8.2+
- **Database**: MySQL/MariaDB
- **Authentication**: Laravel Sanctum

### **Frontend**
- **Template Engine**: Blade
- **CSS Framework**: Bootstrap 5
- **JavaScript**: Vanilla JS + jQuery
- **Build Tool**: Vite

### **Additional Packages**
- `maatwebsite/excel` - Export/Import Excel
- `mews/captcha` - Security verification
- `ext-gd` - Image processing

## Instalasi

### **Requirements**
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Node.js & NPM

### **Setup Instructions**
```bash
# Clone repository
git clone <repository-url>
cd bonn-dig-final-bos

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start development server
php artisan serve
```

## Struktur Database

### **Core Tables**
- `users` - Manajemen pengguna
- `divisis` - Data departemen
- `barangs` - Master data barang
- `bon_barangs` - Header permintaan
- `bon_barang_details` - Detail permintaan
- `notifikasis` - System notifications

## Screenshots

*(Add screenshots of the application here)*

## Deployment

### **Production Deployment**
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set environment variables
APP_ENV=production
APP_DEBUG=false
```

### **Docker Support**
- Dockerfile included for containerization
- Ready for cloud deployment (Railway, Heroku, etc.)

## Kontribusi

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## License

Project ini dilisensikan under MIT License - lihat file [LICENSE](LICENSE) untuk detail.

## Contact

- **Developer**: [Your Name]
- **Email**: [your.email@example.com]
- **LinkedIn**: [Your LinkedIn Profile]

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

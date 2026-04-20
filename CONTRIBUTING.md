# Contributing to Sistem Manajemen Bon Barang

Terima kasih atas ketertarikan Anda untuk berkontribusi pada project ini! Berikut adalah panduan untuk berkontribusi:

## Cara Berkontribusi

### 1. Fork Repository
- Fork repository ini ke akun GitHub Anda
- Clone fork Anda ke local machine

### 2. Setup Development Environment
```bash
# Clone fork Anda
git clone https://github.com/USERNAME/bonn-dig-final-bos.git
cd bonn-dig-final-bos

# Setup remote untuk tracking upstream
git remote add upstream https://github.com/ORIGINAL_OWNER/bonn-dig-final-bos.git

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### 3. Buat Branch Baru
```bash
# Buat branch untuk fitur baru
git checkout -b feature/nama-fitur

# Atau untuk bug fix
git checkout -b fix/deskripsi-bug
```

### 4. Lakukan Perubahan
- Ikuti coding standards yang ada
- Test perubahan Anda
- Pastikan semua tests pass

### 5. Commit Changes
```bash
# Add changes
git add .

# Commit dengan pesan yang jelas
git commit -m "feat: tambahkan fitur export ke PDF"
```

### 6. Push dan Pull Request
```bash
# Push ke fork Anda
git push origin feature/nama-fitur

# Buat Pull Request ke repository utama
```

## Coding Standards

### PHP/Laravel
- Ikuti PSR-12 coding standards
- Gunakan meaningful variable names
- Add comments untuk complex logic
- Follow Laravel best practices

### JavaScript
- Gunakan modern ES6+ syntax
- Add JSDoc comments untuk functions
- Minimize global variables

### Blade Templates
- Gunakan component-based approach
- Keep logic minimal di templates
- Use proper HTML structure

## Testing
- Pastikan semua existing tests masih pass
- Add tests untuk fitur baru
- Test di multiple browsers jika relevant

## Pull Request Guidelines

### PR Title Format
- `feat:` untuk fitur baru
- `fix:` untuk bug fixes
- `docs:` untuk dokumentasi
- `style:` untuk formatting
- `refactor:` untuk refactoring
- `test:` untuk tests

### PR Description
- Deskripsi singkat perubahan
- Screenshots jika UI changes
- Testing instructions
- Related issues

## Code Review Process
1. Automated checks harus pass
2. Manual review oleh maintainer
3. Feedback akan diberikan
4. Update berdasarkan feedback
5. Approval dan merge

## Questions?
Jika ada pertanyaan, silakan:
- Buat issue di repository
- Contact maintainer
- Join discussion channels

## License
Dengan berkontribusi, Anda setuju bahwa kontribusi Anda akan dilisensikan under MIT License.

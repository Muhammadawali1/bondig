# Kontribusi ke Sistem Manajemen Bon Barang

Makasih ya mau kontribusi ke project ini! Ini panduan singkat buat yang mau bantu develop:

## Cara Kontribusi

### 1. Fork Repository Dulu
- Fork repo ini ke GitHub kamu
- Clone fork ke laptop/komputer kamu

### 2. Setup Environment
```bash
# Clone repo kamu
git clone https://github.com/USERNAME/bonn-dig-final-bos.git
cd bonn-dig-final-bos

# Setup remote buat tracking upstream
git remote add upstream https://github.com/Muhammadawali1/bondig.git

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
# Buat branch buat fitur baru
git checkout -b feature/nama-fitur

# Atau buat bug fix
git checkout -b fix/bug-yang-diperbaiki
```

### 4. Lakukan Perubahan
- Ikutin coding style yang udah ada
- Test fitur yang kamu buat
- Pastikan ga ada yang error

### 5. Commit Changes
```bash
# Add semua perubahan
git add .

# Commit dengan pesan yang jelas
git commit -m "feat: tambah export PDF"
```

### 6. Push & Pull Request
```bash
# Push ke repo kamu
git push origin feature/nama-fitur

# Buat Pull Request ke repo utama
```

## Coding Style

### PHP/Laravel
- Ikutin PSR-12 standards (biar rapih)
- Pake nama variable yang jelas
- Add comment buat logic yang rumit
- Ikutin best practices Laravel

### JavaScript
- Pake ES6+ syntax (modern)
- Add comment buat function penting
- Jangan banyak pake global variable

### Blade Templates
- Pake component-based approach
- Logic di template jangan terlalu banyak
- HTML structure yang bener

## Testing
- Pastikan semua tests masih jalan
- Add tests buat fitur baru
- Test di beberapa browser kalo perlu

## Pull Request Guidelines

### Format Title PR
- `feat:` buat fitur baru
- `fix:` buat bug fixes
- `docs:` buat dokumentasi
- `style:` buat formatting
- `refactor:` buat refactoring
- `test:` buat tests

### Description PR
- Jelaskan singkat perubahan kamu
- Screenshot kalo ada perubahan UI
- Cara testing fiturnya
- Issue yang berhubungan (kalo ada)

## Review Process
1. Automated checks harus lolos
2. Manual review sama maintainer
3. Feedback bakal dikasih
4. Update berdasarkan feedback
5. Approve & merge

## Ada Pertanyaan?
Kalo ada yang mau ditanya:
- Buat issue di repo
- Contact saya langsung
- Join discussion channels

## License
Dengan kontribusi, kamu setuju bahwa kontribusinya bakal dilisensikan under MIT License.

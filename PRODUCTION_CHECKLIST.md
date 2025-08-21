# 🚀 Laravel Production Deployment Checklist

## ⚠️ ZORUNLU GÜVENLIK KONTROLLERI

### 1. Environment Ayarları
- [ ] `APP_ENV=production` olarak ayarlandı
- [ ] `APP_DEBUG=false` olarak ayarlandı
- [ ] `APP_KEY` güçlü ve benzersiz bir key ile ayarlandı
- [ ] `APP_URL` production domain ile ayarlandı

### 2. Database Güvenliği
- [ ] Production database bağlantı bilgileri doğru
- [ ] Database user'ı minimum gerekli yetkiler ile oluşturuldu
- [ ] Database şifresi güçlü ve güvenli
- [ ] Database backup stratejisi kuruldu

### 3. Session & Security
- [ ] `SESSION_SECURE_COOKIE=true` (HTTPS için)
- [ ] `SESSION_HTTP_ONLY=true` 
- [ ] `SESSION_ENCRYPT=true`
- [ ] `SESSION_DOMAIN` doğru domain ile ayarlandı

### 4. Cache & Performance
- [ ] `CACHE_STORE=redis` (production için önerilir)
- [ ] Redis bağlantısı kuruldu ve test edildi
- [ ] `QUEUE_CONNECTION=redis` (yoğun trafik için)

### 5. Mail Ayarları
- [ ] SMTP ayarları production için yapılandırıldı
- [ ] Mail gönderimi test edildi
- [ ] `MAIL_FROM_ADDRESS` doğru email adresi

### 6. File Storage
- [ ] `FILESYSTEM_DISK=public` veya S3 ayarlandı
- [ ] AWS S3 credentials güvenli şekilde ayarlandı
- [ ] Storage symlink oluşturuldu (`php artisan storage:link`)

### 7. SSL/TLS & HTTPS
- [ ] SSL sertifikası kuruldu ve aktif
- [ ] HTTPS yönlendirmesi aktif
- [ ] `SECURE_SSL_REDIRECT=true`
- [ ] `SECURE_HSTS_SECONDS=31536000`

### 8. Log & Monitoring
- [ ] `LOG_LEVEL=error` (production için)
- [ ] `LOG_STACK=daily` (log rotation için)
- [ ] Log dosyaları izlenebilir
- [ ] Error monitoring kuruldu (Sentry, Bugsnag vb.)

### 9. Performance Optimizations
- [ ] `composer install --no-dev --optimize-autoloader` çalıştırıldı
- [ ] `php artisan optimize` çalıştırıldı
- [ ] `php artisan config:cache` çalıştırıldı
- [ ] `php artisan route:cache` çalıştırıldı
- [ ] `php artisan view:cache` çalıştırıldı

### 10. Server Konfigürasyonu
- [ ] PHP production ayarları yapıldı (display_errors=Off)
- [ ] Web server (Nginx/Apache) güvenlik ayarları
- [ ] Firewall kuralları uygulandı
- [ ] Rate limiting aktif
- [ ] Fail2ban kuruldu (brute force koruması)

## 🔍 PRODUCTION ÖNCESI TEST LİSTESİ

### Fonksiyonel Testler
- [ ] Admin panel login/logout çalışıyor
- [ ] CRUD işlemleri tüm modüllerde çalışıyor
- [ ] API endpoint'leri doğru response veriyor
- [ ] File upload/download çalışıyor
- [ ] Mail gönderimi çalışıyor

### Performance Testler
- [ ] Sayfa yükleme süreleri < 2 saniye
- [ ] API response süreleri < 500ms
- [ ] Database query'leri optimize edildi
- [ ] N+1 query problemi kontrol edildi

### Güvenlik Testler
- [ ] XSS koruması test edildi
- [ ] CSRF koruması aktif
- [ ] SQL Injection koruması test edildi
- [ ] Authentication bypass denemeleri test edildi
- [ ] File upload güvenliği test edildi

## 📝 DEPLOYMENT ADIMLARİ

### Önce Test Et:
```bash
# Test environment'da çalıştır
php artisan test
php artisan pint --test
```

### Production Deploy:
```bash
# 1. .env dosyasını production ayarları ile güncelle
cp .env.production .env

# 2. Deploy script'ini çalıştır
./deploy.sh  # Linux/Mac
# veya
deploy.bat   # Windows

# 3. Manuel kontroller yap
php artisan route:list
php artisan config:show app
php artisan queue:work --daemon  # Queue worker başlat
```

### Deploy Sonrası Kontroller:
- [ ] Ana sayfa açılıyor
- [ ] Admin panel erişilebilir
- [ ] API endpoint'leri çalışıyor
- [ ] File upload çalışıyor
- [ ] Mail gönderimi çalışıyor
- [ ] Log dosyaları yazılıyor
- [ ] Performance monitöring aktif

## ⚡ PERFORMANS MONİTÖRİNG

### Monitoring Araçları
- [ ] Laravel Telescope (development için)
- [ ] New Relic / DataDog (production monitoring)
- [ ] Laravel Horizon (queue monitoring)
- [ ] Uptime monitoring (Pingdom, UptimeRobot)

### Backup Stratejisi
- [ ] Database backup otomasyonu kuruldu
- [ ] File backup otomasyonu kuruldu
- [ ] Backup restore testi yapıldı
- [ ] Backup retention policy belirlendi

## 🚨 EMERGENCY PROCEDURES

### Rollback Planı
- [ ] Önceki versiyon yedeklendi
- [ ] Rollback script'i hazır
- [ ] Database migration rollback planı
- [ ] DNS failover planı (gerekirse)

### İletişim Planı
- [ ] Incident response team belirlendi
- [ ] Escalation matrix oluşturuldu
- [ ] Status page kuruldu
- [ ] User communication planı

---

**Not:** Bu checklist'teki tüm maddelerin tamamlanmasından sonra production deploy yapılmalıdır. Hiçbir güvenlik maddesi atlanmamalıdır.

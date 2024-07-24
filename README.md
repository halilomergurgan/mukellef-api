# Proje Adı

Bu proje, Laravel 11 ve PHP 8.3 kullanılarak geliştirilmiş bir API projesidir. Projenin Docker ile nasıl kurulacağını ve çalıştırılacağını açıklayan talimatlar aşağıda verilmiştir.

## Kullanılan Teknolojiler

- Laravel 11
- PHP 8.3
- Docker
- MySQL 8.0
- Laravel Sanctum

## Kullanılan Koşullar

- Dependency Injection
- Service Pattern
- Request Validation Classes
- Implicit binding
- PSR-12 Uyumluluğu

## Kurulum

### Gereksinimler

- Docker ve Docker Compose yüklü olmalıdır.

### Adımlar

1. Projeyi klonlayın:
    ```bash
    git clone https://github.com/halilomergurgan/mukellef-api.git
    cd proje
    ```

2. Docker konteynerlerini başlatın ve yapılandırın:
    ```bash
    ./setup.sh
    ```

### Setup Dosyasının Açıklaması

`setup.sh` dosyası, Docker konteynerlerini başlatmak, veritabanı migrasyonlarını çalıştırmak ve gerekli önbellekleri temizlemek için kullanılan bir script dosyasıdır. Bu dosya, aşağıdaki adımları gerçekleştirir:

1. `.env` dosyasını `.env.example` dosyasından kopyalar.
2. Docker konteynerlerini aşağı indirir ve yeniden başlatır.
3. MySQL konteynerinin hazır olmasını bekler.
4. Laravel önbelleklerini temizler.
5. Laravel migrasyonlarını çalıştırır.
6. Veritabanını seed'ler (örnek verileri yükler).

### Mail Gönderimi

Mail gönderimi için mailtrap.io kullandım. Dilerseniz sizde kullanabilirsiniz. Ekran görüntüsünü mail ile ilettim. Bunun haricinde log içine mailleri atacaktır. storage/laravel.log

### Docker Komutları

Docker konteynerlerini başlatmak için aşağıdaki komutları kullanabilirsiniz:

- Docker konteynerlerini başlatmak:
    ```bash
    docker-compose up -d
    ```

- Docker konteynerlerini durdurmak:
    ```bash
    docker-compose down
    ```
docker-compose.yml dosyalasının içinde DB bilgileri bulabiliriniz. .env.example içine doğru bilgiler eklenmiştir. setup.sh otomatik olarak kopyalacaktır.

### Projeyi Çalıştırma

Host dosyanıza 127.0.0.1 mukellef-api.test ekleyiniz. Eğer eklemek istemiyorsanız nginx->default.conf dosyası içindeki server_name alanını localhost olarak değiştiriniz.

Docker konteynerlerini başlattıktan sonra, projeyi tarayıcınızda şu adresi kullanarak görüntüleyebilirsiniz:

- [http://mukellef-api.test](http://mukellef-api.test)

### API Endpoints

Projedeki bazı temel API endpoint'leri aşağıda listelenmiştir:

- `POST /register` - Kullanıcı kaydı
- `POST /login` - Kullanıcı girişi
- `POST /logout` - Kullanıcı çıkışı
- `GET /me` - Kullanıcı bilgileri
- `POST /user/{user}/subscription` - Abonelik ekleme
- `PUT /user/{user}/subscription/{subscription}` - Abonelik güncelleme
- `DELETE /user/{user}/subscription/{subscription}` - Abonelik silme
- `POST /user/{user}/transaction` - Ödeme işlemi

### Command/Job

- Ödemeleri saatlik olarak kontrol eden bir job eklenmiştir:
    ```bash
    php artisan subscriptions:renew
    ```
  
### Testler

Projede yer alan testleri çalıştırmak için aşağıdaki komutu kullanabilirsiniz:

```bash
php artisan test

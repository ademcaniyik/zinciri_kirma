# Zinciri Kırma - Alışkanlık Takip Uygulaması

Bu uygulama, günlük alışkanlıklarınızı takip etmenize ve "zinciri kırma" prensibiyle alışkanlıklarınızı sürdürmenize yardımcı olan bir web uygulamasıdır.

## Özellikler

- Yeni alışkanlık ekleme
- Her alışkanlık için hedef gün sayısı belirleme
- Görsel zincir takibi
- Günlük ilerleme işaretleme
- Alışkanlık silme
- Verilerin MySQL'de kalıcı olarak saklanması

## Teknolojiler

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- Bootstrap 5.3.0

## Kurulum

1. XAMPP veya benzeri bir local server kurulumu yapın
2. MySQL veritabanını oluşturun:
   - phpMyAdmin'e gidin
   - `database.sql` dosyasının içeriğini çalıştırın

3. Proje dosyalarını `htdocs` klasörüne kopyalayın
4. Veritabanı bağlantı ayarlarını `config.php` dosyasında yapılandırın:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "zinciri_kirma";
   ```

5. Tarayıcıdan `http://localhost/zinciri_kirma` adresine giderek uygulamayı kullanmaya başlayın

## Kullanım

1. "Yeni Alışkanlık Ekle" formunu kullanarak bir alışkanlık adı ve hedef gün sayısı girin
2. Eklenen alışkanlık için günlük takibi daireler üzerine tıklayarak yapın
3. Yeşil daireler tamamlanan günleri gösterir
4. İstenmeyen alışkanlıkları çöp kutusu ikonuna tıklayarak silebilirsiniz

## Katkıda Bulunma

1. Bu depoyu fork edin
2. Yeni bir branch oluşturun (`git checkout -b yeni-ozellik`)
3. Değişikliklerinizi commit edin (`git commit -am 'Yeni özellik eklendi'`)
4. Branch'inizi push edin (`git push origin yeni-ozellik`)
5. Pull Request oluşturun

## Lisans

Bu proje MIT lisansı altında lisanslanmıştır. Daha fazla bilgi için `LICENSE` dosyasına bakın.

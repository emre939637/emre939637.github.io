<?php
function girisKontrol()
{
    // 1. KONTROL: Session başlatılmamışsa başlat (genelde en üstte olur)
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // 2. KONTROL: Giriş yapılmış mı ve grup numarası var mı?
    if (!isset($_SESSION["kisiAdi"]) || !isset($_SESSION["grupNo"])) {
        echo "Yetkisiz erişim! Giriş yapmak için: <a href='index.php'>Anasayfaya dön...</a>";
        exit(); 
    }
    
    // Giriş başarılıysa selamla
    echo "<h1>Hoşgeldiniz " . htmlspecialchars($_SESSION["kisiAdi"]) . "</h1>";
}
// BU YENİ FONKSİYON İŞİNİ ÇOK KOLAYLAŞTIRACAK
function grupSorgusu() {
   
    if (isset($_SESSION['grupNo']) && !empty($_SESSION['grupNo'])) {
        $grupID = intval($_SESSION['grupNo']);
        return " WHERE grupNo = $grupID"; // Sadece bu kadar!
    } else {
        return " WHERE grupNo = -1"; 
    }
}
?>
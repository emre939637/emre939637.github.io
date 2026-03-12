<?php
session_start();
require '../baglanti.php';

if ($_POST) {
    $kad = mysqli_real_escape_string($baglanti, $_POST["kad"]);
    $sifre = mysqli_real_escape_string($baglanti, $_POST["sifre"]);

    // 1. ADIM: Sadece kullanıcı adını kontrol et
    $sql_kad = "SELECT * FROM uyeler WHERE kullaniciAdi='$kad' LIMIT 1";
    $sonuc_kad = mysqli_query($baglanti, $sql_kad);

    if (mysqli_num_rows($sonuc_kad) == 1) {
        $alinacakVeri = mysqli_fetch_assoc($sonuc_kad);
        
        // 2. ADIM: Kullanıcı bulundu, şimdi şifreyi kontrol et
        if ($alinacakVeri['sifre'] === $sifre) {
            // GİRİŞ BAŞARILI
            $_SESSION["uyeID"]      = $alinacakVeri["uyeID"];
            $_SESSION["kisiAdi"]    = $alinacakVeri["kisiAdi"];
            $_SESSION["kisiSoyadi"] = $alinacakVeri["kisiSoyadi"];
            $_SESSION["grupNo"]     = $alinacakVeri['grupNo'];
            $_SESSION["yetkiDurum"] = $alinacakVeri["yetkiDurum"];
            $_SESSION["oturum"]     = true;

            echo "<link rel='stylesheet' href='yonetim.css'>";
            echo "<div class='loader-kutu'>
                    <div class='loader'></div>
                    <p>Giriş Başarılı! Hoş geldiniz, " . $_SESSION["kisiAdi"] ." ". $_SESSION["kisiSoyadi"]."...</p>
                  </div>";
            
            header("refresh:2;url=index.php");
            exit();
        } else {
            // ŞİFRE HATALI - Kullanıcı adını geri gönderiyoruz ki tekrar yazmasın
            header("Location:index.php?hata=sifre&kad=" . urlencode($kad));
            exit();
        }
    } else {
        // KULLANICI ADI HATALI
        header("Location:index.php?hata=kad");
        exit();
    }
    mysqli_close($baglanti);
}
?>
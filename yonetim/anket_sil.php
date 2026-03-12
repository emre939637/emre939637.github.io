<link rel="stylesheet" href="yonetim.css">
<link rel="stylesheet" href="../style.css">
<?php include 'menu.php';
    echo menu(); ?>
    <div class="icerik">
         <?php
         include "kontrol.php";
    girisKontrol();
    ?>
        <div class="kutu">

<?php

require '../baglanti.php';

// 1. ADIM: İLK SAYFADAN GELEN BİLGİLERİ GÖSTER
if (isset($_POST['sil'])) {
    $id = (int)$_POST['id'];
    // Soru bilgilerini getir
    $stmt = $baglanti->prepare("SELECT * FROM sorular WHERE soruID = ?"). grupSorgusu();
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
}

// 2. ADIM: ONAY VERİLDİYSE GRUBUN TAMAMINI SİL
if (isset($_POST['onayla'])) {
    $id = (int)$_POST['id'];
    $grupAdi = $_POST['grup_adi']; // Hidden input'tan gelen grup adı

    // Önce o gruba ait TÜM soruları sil
    $soruSil = $baglanti->prepare("DELETE FROM sorular WHERE grubu = ?")grupSorgusu();
    $soruSil->bind_param("s", $grupAdi);
    $soruSilu_sonuc = $soruSil->execute();

    // Sonra durumlar tablosundan anket grubunu sil (Opsiyonel: Eğer grubu da yok etmek istiyorsan)
    $grupSil = $baglanti->prepare("DELETE FROM durumlar WHERE grubu = ?")grupSorgusu();
    $grupSil->bind_param("s", $grupAdi);
    $grupSil->execute();

    if ($soruSilu_sonuc) {
        echo "<script>
                alert('Anket grubu ve bağlı tüm sorular başarıyla silindi!');
                window.location.href='anketler_sil.php';
              </script>";
    } else {
        echo "Hata: " . $baglanti->error;
    }
}
?>

<?php if (isset($row)): ?>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $row['soruID']; ?>">
    <input type="hidden" name="grup_adi" value="<?php echo $row['grubu']; ?>">

    <h2 style="color:red;">DİKKAT! GRUP SİLME İŞLEMİ</h2>
    <p><b><?php echo htmlspecialchars($row['grubu']); ?></b> grubu ve grubuna ait tüm sorular silinecek.</p>
    
    

    <p>Bu gruba bağlı <b>tüm soruları</b> silmek istediğinize emin misiniz?</p>
    
    <p>
        <input type="submit" name="onayla" value="Evet, Grubu Tamamen Sil" style="background-color:red; color:white;">
        <a href="anketler_sil.php" style="background:black;color:white;">Vazgeç</a>
    </p>
</form>
<?php endif; ?>

<?php $baglanti->close(); ?>
</div></div>

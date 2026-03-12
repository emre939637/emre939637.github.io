<link rel="stylesheet" href="style.css">
<?php
session_start();
if(!isset($_SESSION["son_ad"])){
    header("Location: anket.php"); // Eğer verisiz gelinirse geri at
    exit;
}
?>
<div class="kutu">
<h1>Anket Tamamlandı!</h1>
<p>Teşekkürler Sayın <?php echo $_SESSION["son_ad"] . " " . $_SESSION["son_soyad"]; ?>,</p>
<p>Cevaplarınız başarıyla kaydedildi.</p>
</div>
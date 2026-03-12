
<link rel="stylesheet" href="yonetim.css">
<link rel="stylesheet" href="../style.css">
<?php  include "menu.php";
    echo menu(); ?>
<div class="icerik">
 <?php
         include "kontrol.php";
    girisKontrol();
    ?>
<?php
require '../baglanti.php';

if (isset($_POST['sil'])) {
    $id = (int)$_POST['id'];
    $sorgu = $baglanti->query("SELECT * FROM sorular WHERE soruID=$id");
    $row = $sorgu->fetch_assoc();
}
?>
<form method="post">
    <input type="hidden" name="id" value="<?php echo $row['soruID']; ?>">

    <b><?php echo $row['grubu']?></b> grubundan,<br>
    <b><?php echo $row['soruBaslik']?></b> başlıklı,<br>
    <b><?php echo $row['soru1']?></b> 1.sorusu,<br>
    <b><?php echo $row['soru2']?></b> 2.sorusu,<br>
    <b><?php echo $row['soru3']?></b> 3.sorusu,<br>
    <b><?php echo $row['soru4']?></b> 4.sorusu<br>

    <p>olan anket sorusunu silmek istiyor musunuz?</p>
    <p>
        <input type="submit" name="onayla" value="Sil" style="background-color:red;color:white;">
        <a href="soru_sil.php">Vazgeç</a>

    </p>
</form>
<?php
if (isset($_POST['onayla'])) {
    $id = (int)$_POST['id'];

    $sil = $baglanti->query("DELETE FROM sorular WHERE soruID=$id");

    if ($sil) {
        echo "<script>
                alert('Soru başarıyla silindi');
                window.location.href='soru_sil.php';
              </script>";
    } else {
        echo "Hata: " . $baglanti->error;
    }
}

$baglanti->close();
?>
</div>

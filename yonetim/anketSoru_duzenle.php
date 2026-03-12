<html lang="tr">
<link rel="stylesheet" href="yonetim.css">
<?php
 include "menu.php";
    echo menu();

?>
<div class="icerik">
     <?php
         include "kontrol.php";
    girisKontrol();
    ?>
<?php
require '../baglanti.php';

// Düzenle butonuna basıldıysa
if(isset($_POST['duzenle'])){
    $id = $_POST['id'];

    $sorgu = $baglanti->query("SELECT * FROM sorular WHERE soruID=$id");
    $durumlar = $baglanti->query("SELECT * FROM durumlar".grupSorgusu());
    $row = $sorgu->fetch_assoc();
?>
    <form method="post" action="anket_duzenle.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

      <select name="grubu" required>
    <option value="">Grup Seçiniz</option>
    <?php while ($d = $durumlar->fetch_assoc()) { ?>
        <option 
            value="<?= $d['grubu'] ?>"
            <?= ($d['durumID'] == $row['grubu']) ? 'selected' : '' ?>
        >
            <?= $d['grubu'] ?>
        </option>
    <?php } ?>
</select><br>
        Soru Başlığı: <input type="text" name="soruBaslik" value="<?php echo $row['soruBaslik']; ?>"><br>
        Cevap 1: <input type="text" name="soru1" value="<?php echo $row['soru1']; ?>"><br>
        Cevap 2: <input type="text" name="soru2" value="<?php echo $row['soru2']; ?>"><br>
        Cevap 3: <input type="text" name="soru3" value="<?php echo $row['soru3']; ?>"><br>
        Cevap 4: <input type="text" name="soru4" value="<?php echo $row['soru4']; ?>"><br>
         <p>
            <input type="submit" name="guncelle" value="Güncelle">
       
            <a href="soru_duzenle.php"style="background:black;color:white;">Vazgeç</a>
        </p>
    </form>
    </div>
<?php
}

// Güncelleme işlemi
if (isset($_POST['guncelle'])) {

    $id    = $_POST['id'];
    $grubu = $_POST['grubu'];

    $soruBaslik = $_POST['soruBaslik'];
    $soru1 = $_POST['soru1'];
    $soru2 = $_POST['soru2'];
    $soru3 = $_POST['soru3'];
    $soru4 = $_POST['soru4'];

    $baglanti->query("
        UPDATE sorular SET 
            grubu='$grubu',
            soruBaslik='$soruBaslik',
            soru1='$soru1',
            soru2='$soru2',
            soru3='$soru3',
            soru4='$soru4'
        WHERE soruID=$id
    ");

    echo "Soru güncellendi! 5 saniye sonra listeye dönüyorsunuz...";
    echo '<meta http-equiv="refresh" content="5;url=soru_duzenle.php">';
}
?>
</html>
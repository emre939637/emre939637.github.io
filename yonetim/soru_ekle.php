<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anket Ekleme</title>
    <link rel="stylesheet" href="yonetim.css">
</head>
<body>
 
        <?php  include "menu.php";
    echo menu();
    require '../baglanti.php';
        ?>
  
    <div class="icerik">
         <?php
         include "kontrol.php";
            girisKontrol();
            ?>
            <form action="soru_ekle.php" method="post">
                <p>
                <?php
        require '../baglanti.php';
        $durumlar = $baglanti->query("SELECT * FROM durumlar". grupSorgusu());
        ?>

        <h3>*Grubu</h3>
        <select name="grup" id="grup" required>
            <option value="">Grup Seçiniz</option>
            <?php while ($d = $durumlar->fetch_assoc()) { ?>
                <option value="<?= $d['grubu'] ?>">
                    <?= $d['grubu'] ?>
                </option>
            <?php } ?>
        </select>
                </p>
                <p>
                    <h3>*Başlık Ekle</h3>
                    <input type="text" name="baslik" id="baslik">
                </p>
                <p>
                    <h3>*Cevap 1</h3>
                    <input type="text" name="cevap1" id="cevap1">
                </p>
                <p>
                    <h3>*Cevap 2</h3>
                    <input type="text" name="cevap2" id="cevap2">
                </p>
                <p>
                    <h3>*Cevap 3</h3>
                    <input type="text" name="cevap3" id="cevap3">
                </p>
                <p>
                    <h3>*Cevap 4</h3>
                    <input type="text" name="cevap4" id="cevap4">
                </p>
                <input type="submit" value="Kaydet">
            </form>
            </div>
            <?php
            require '../baglanti.php';
                if($_POST){
                    $grup= $_POST["grup"]; 
                    $baslik=$_POST["baslik"];
                    $cvp1=$_POST["cevap1"];
                    $cvp2=$_POST["cevap2"];
                    $cvp3=$_POST["cevap3"];
                    $cvp4=$_POST["cevap4"];
                     $oturumGrupNo = $_SESSION['grupNo'];
                     
                    $veriEkle="INSERT INTO sorular (grubu,soruBaslik,soru1,soru2,soru3,soru4,grupNo) VALUES ('$grup','$baslik','$cvp1','$cvp2','$cvp3','$cvp4','$oturumGrupNo')";
        if($baglanti->query($veriEkle)===TRUE){
            echo "<script>
                alert('Soru Başarıyla Eklendi');</script>";
        } else{
            echo "hata: ".$veriEkle."<br>".$baglanti->error;
        } $baglanti->close();
        
                }
            ?>
</div>
</body>
</html>
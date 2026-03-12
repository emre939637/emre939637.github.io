<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="yonetim.css">
    <title>Anket Ekle</title>
</head>
<body>
    <?php  include "menu.php";
    echo menu(); ?>
    <div class="icerik">
         <?php
         include "kontrol.php";
    girisKontrol();
    ?>
    <form method="post" style="margin-top:50px;">
        <label>Anket Eklemek İçin Grup Katagorisi Ekleyin:</label>
        <input type="text" name="grp" id="grp" required>
        <input type="submit" value="Ekle">
    </form>

    <?php
    require '../baglanti.php';

    // POST işlemi
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['grp'])) {
        $grp = $baglanti->real_escape_string($_POST['grp']); // SQL Injection koruması
        $drm='Pasif';
       $oturumGrupNo = $_SESSION['grupNo'];
       $uyeID = $_SESSION['uyeID']; // Kontrol.php sayesinde burada mevcut

    // INSERT sorgusunda grupSorgusu() KULLANILMAZ! 
    // Doğrudan VALUES içine yazılır:
    $veriEkle = "INSERT INTO durumlar (grubu, durumu, grupNo,uyeID) VALUES ('$grp', '$drm', '$oturumGrupNo','$uyeID')";
    
        if ($baglanti->query($veriEkle) === TRUE) {
            // POST verisi tekrar eklenmesin diye yönlendirme
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "<p style='color:red;'>Hata: " . $baglanti->error . "</p>";
        }
    }

    // Tabloyu her zaman göster
    $listele = "SELECT * FROM durumlar". grupSorgusu();
    $result = $baglanti->query($listele);

    if ($result->num_rows > 0) {
        echo "<table style='max-width: 200px; margin:20px;'>\n";
        echo "<tr><th>Soru Grubu</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . htmlspecialchars($row["grubu"]) . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Henüz bir grup eklenmemiş.</p>";
    }

    $baglanti->close();
    ?>
    </div>
</body>
</html>
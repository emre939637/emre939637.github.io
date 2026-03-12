<!DOCTYPE html>
<html lang="tr-tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktif Anketler</title>
    <link rel="stylesheet" href="aktif.css">
    <style>
        button {
            outline: none; border:none; margin: 5px; padding: 10px 20px;
            font-size: 1em; background-color: rgb(13, 66, 241);
            border-radius: 25px; color: white; cursor: pointer;
        }
        /* MODAL TASARIMI (Eksik olan kısım buydu) */
        .modal {
            display: none; position: fixed; z-index: 1000;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white; margin: 10% auto; padding: 20px;
            border-radius: 10px; width: 400px; text-align: center;
            position: relative;
        }
        .close {
            position: absolute; right: 15px; top: 10px;
            font-size: 28px; font-weight: bold; cursor: pointer;
        }
        iframe { border: none; width: 100%; height: 350px; }
    </style>
</head>
<body>

<?php
include "menu.php";
echo menu();
?>

<div class="icerik">
    <?php
    include "kontrol.php";
    girisKontrol();
    require '../baglanti.php';

    // DURUM GÜNCELLEME
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["durumID"])) {
        $durumID = $_POST["durumID"];
        $mevcut_durum = $_POST["mevcut_durum"];
        $yeni_durum = ($mevcut_durum == 1) ? 0 : 1;
        $guncelle = $baglanti->prepare("UPDATE durumlar SET durumu = ? WHERE durumID = ?");
        $guncelle->bind_param("ii", $yeni_durum, $durumID);
        $guncelle->execute();
    }

    // LİSTELEME
    $listele = "SELECT durumlar.* FROM durumlar" . grupSorgusu();
    $result = $baglanti->query($listele);
    // --- LİSTELEME KISMI ---
$listele = "SELECT durumlar.* FROM durumlar" . grupSorgusu();
$result = $baglanti->query($listele);

// 1. Kapsayıcıyı döngünün DIŞINDA başlatıyoruz
echo '<div class="kart-kapsayici">';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        $durumID = $row["durumID"];
        $durum = $row["durumu"]; 
        $tam_link = "http://localhost/AnketUni/anket.php?id=" . $durumID;
        
        if ($durum == 1) {
    $durum_yazisi = "<b style='color:green;'>Aktif</b>";
    $buton_metin = "Pasif Et";
    $buton_renk = "#ea0e24ff"; // Aktifken basılacak buton "Pasif Et" olacağı için Kırmızı
} else {
    $durum_yazisi = "<b style='color:red;'>Pasif</b>";
    $buton_metin = "Aktif Et";
    $buton_renk = "#28a745"; // Pasifken basılacak buton "Aktif Et" olacağı için Yeşil
}

        // Kart yapısı (Döngü içinde)
      echo '
<div class="card">
    <div class="card-resim">
        <div class="card-resim1">
            <div class="card-baslik">'.$row["grubu"].'</div>
            <div class="card-baslik">'.$durum_yazisi.'</div>
        </div>
    </div>
    
    <div class="card-kod">
        <input type="text" value="'.$tam_link.'" id="link_'.$durumID.'" readonly style="width:100%; font-size: 14px; margin: 5px 0;">
        <button type="button" onclick="kopyala(\'link_'.$durumID.'\')" style="width:100%;">Kopyala</button>
    </div>

    <div class="card-islem-btnler">
        <form method="post" style="display:inline; width:48%;">
            <input type="hidden" name="durumID" value="'.$durumID.'">
            <input type="hidden" name="mevcut_durum" value="'.$durum.'">
            <button type="submit" style="width:100%; background-color:'.$buton_renk.'; color:white;">'.$buton_metin.'</button>
        </form>
        <button onclick="pencereyiAc(\''.$tam_link.'\')" style="width:100%;">Linke QR Oluştur</button>
    </div>
</div>';
    }
}

// 2. Kapsayıcıyı döngünün DIŞINDA kapatıyoruz
echo '</div>';
    ?>
</div> 

<div id="benimModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="pencereyiKapat()">&times;</span>
        <iframe src="" id="qrFrame"></iframe>
    </div>
</div>

<script>
function pencereyiAc(link) {
    var modal = document.getElementById("benimModal");
    var iframe = document.getElementById("qrFrame");
    
    // iframe src'sini ayarlıyoruz
    iframe.src = "qr_sayfa.php?link=" + encodeURIComponent(link);
    modal.style.display = "block";
}

function pencereyiKapat() {
    document.getElementById("benimModal").style.display = "none";
    document.getElementById("qrFrame").src = ""; // Kapatınca içeriği temizle
}

window.onclick = function(event) {
    var modal = document.getElementById("benimModal");
    if (event.target == modal) {
        pencereyiKapat();
    }
}

function kopyala(input_id) {
    var copyText = document.getElementById(input_id);
    copyText.select();
    navigator.clipboard.writeText(copyText.value).then(function() {
        alert("Link kopyalandı!");
    });
}
</script>

</body>
</html>
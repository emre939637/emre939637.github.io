<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="yonetim.css">
    <link rel="stylesheet" href="../style.css">
    
    <style>
    /* Arka plan (karartma) */
.modal {
  display: none; 
  position: fixed; 
  z-index: 1000; 
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0,0,0,0.5); /* Siyah şeffaf arka plan */
}

/* Beyaz kutu (içerik) */
.modal-icerik {
  background-color: #fff;
  margin: 10% auto; 
  padding: 20px;
  width: 60%; 
  height:auto;
  border-radius: 8px;
  position: relative;
}

/* Kapatma butonu (X) */
.kapat {
  position: absolute;
  right: 15px; top: 10px;
  font-size: 28px;
  cursor: pointer;
}
           button{
    outline: none;
    border:none;
    margin: 15px 4px;
    padding: 10px 20px;
    font-size: 1em;
    background-color: rgb(13, 66, 241);
    border-radius: 25px;
    width: auto;
    color: white;
    cursor: pointer;
    text-decoration: none;
    }
    .diskutu{
         background-color: rgb(13, 66, 241);
         color:white;
         max-width: 120px;
         padding: 10px;
         border-radius:30px;
         cursor:pointer;
         margin:25px 5px;
    }
    .arti{
        width: 30px;
        height: 30px;
        border:3px solid white;
        border-radius:50%;
        display: inline-flex;
        align-items:center;
        justify-content:center;
        font-size:22px;
        font-weight:bold;
        background:none;
        color:white;
    }
    </style>
    <title>Üyeler</title>
</head>
<body>
    <?php
    require 'menu.php';
    echo menu();
    ?>
    <div class="icerik">
        
        
        <?php
        require "../baglanti.php";
         if (isset($_SESSION["kisiAdi"]) && isset($_SESSION["uyeID"])) {
            echo "<h1>Hoşgeldiniz " . $_SESSION["kisiAdi"] ." ".$_SESSION["kisiSoyadi"]. "</h1>"; 
        
             ?>
            <div class="diskutu" onclick="modalAc('uyeOl.php')">
            <input class="arti" type="button" value="+"> Üye Ekle
        </div>
             <?php
            require 'kontrol.php';
               $listele = "SELECT * FROM uyeler".grupSorgusu();
              
        $result = $baglanti->query($listele);
                  
        echo "<table>
                <tr>
                    <th>Üye Adı Soyadı</th>
                    <th>Kullanıcı Adı</th>
                    <th>Şifre</th>
                     <th>Yetki</th>
                    <th>Bölümü</th>
                    <th>Grup Numarası</th>
                    <th>Düzenle</th>
                    <th>Sil</th>
                    
                </tr>";

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                 $uyeID = $row["uyeID"];
                $silinecekID=$uyeID;
                echo "<tr>";
                echo "<td>".htmlspecialchars($row["kisiAdi"])." ".htmlspecialchars($row["kisiSoyadi"])."</td>";
                echo "<td>".htmlspecialchars($row["kullaniciAdi"])."</td>";
                echo "<td>".htmlspecialchars($row["sifre"])."</td>";
                echo "<td>".htmlspecialchars($row["yetkiDurum"])."</td>";
                echo "<td>".htmlspecialchars($row["bolum"])."</td>";
                echo "<td>".htmlspecialchars($row["grupNo"])."</td>";
               // Döngü içinde butonları şu şekilde değiştirin:
                echo "<td><button onclick=\"modalAc('uyeDuzenle.php?id=$uyeID')\">Düzenle</button></td>";
                echo "<td><button onclick=\"modalAc('uyeSil.php?id=$uyeID')\" style='background:red;'>Sil</button></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Kayıt bulunamadı.</td></tr>";
        }
        echo "</table>";
    }
?>
    </div>
   
</div>
<div id="genelModal" class="modal">
    <div class="modal-icerik">
        <span class="kapat" onclick="modalKapat()">&times;</span>
        <iframe id="modalFrame" src="" width="100%" height="400px" frameborder="0"></iframe>
    </div>
</div>
<script>
   function modalAc(sayfaUrl) {
    // Iframe'in src adresini tıklanan ID'ye göre günceller
    document.getElementById("modalFrame").src = sayfaUrl;
    document.getElementById("genelModal").style.display = "block";
}

function modalKapat() {
    document.getElementById("genelModal").style.display = "none";
    document.getElementById("modalFrame").src = ""; // İçeriği temizle
}

// Dışarı tıklandığında kapanma
window.onclick = function(event) {
    var modal = document.getElementById("genelModal");
    if (event.target == modal) {
        modalKapat();
    }
}
</script>
</body>
</html>
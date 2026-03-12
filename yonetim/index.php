<link rel="stylesheet" href="yonetim.css">
<?php
require "../baglanti.php";
?>
<div>
 <div>
        <?php 
        require 'menu.php';
        echo menu();
        ?>  
    </div>
    <div class="icerik">
         <?php
       
    ?>
        <?php
             
        if (isset($_SESSION["kisiAdi"]) && isset($_SESSION["uyeID"])) {
            echo "<h1>Hoşgeldiniz " . $_SESSION["kisiAdi"] ." ".$_SESSION["kisiSoyadi"]. "</h1>"; 
        
            $uyeID = $_SESSION["uyeID"];
            require 'kontrol.php';
               $listele = "SELECT * FROM uyeler" .grupSorgusu();
        $result = $baglanti->query($listele);

        echo "<table>
                <tr>
                    <th>Grup No</th>
                    <th>Bölümünüz</th>
                    <th>Grup Üyeleri</th>
                    
                </tr>";

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($row["grupNo"])."</td>";
                echo "<td>".htmlspecialchars($row["bolum"])."</td>";
                echo "<td>".htmlspecialchars($row["kisiAdi"])."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Kayıt bulunamadı.</td></tr>";
        }
        echo "</table>";

          ?>
            
          <?php
        } 
            else {
             require 'formKontrol.php';
            }
        ?>
    </div>
</div>  
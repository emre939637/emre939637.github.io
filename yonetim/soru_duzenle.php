<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="yonetim.css">
    <title>Anket Düzenle</title>
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

        // URL'den seçilen bir grup var mı kontrol ediyoruz (Filtreleme için)
        $secilenGrup = isset($_GET['grup_filtre']) ? $_GET['grup_filtre'] : '';
        ?>

        <form method="GET" action="" id="filtreForm">
            <select name="grup_filtre" id="grup" style="margin:15px;" onchange="this.form.submit()">
                <option value="">Tüm Soruları Göster</option>
                <?php 
                $sql_durumlar = "SELECT * FROM durumlar" . grupSorgusu();
                $durumlar = $baglanti->query($sql_durumlar);

                if ($durumlar && $durumlar->num_rows > 0) {
                    while ($d = $durumlar->fetch_assoc()) { 
                        $selected = ($secilenGrup == $d['grubu']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($d['grubu']) . '" '.$selected.'>' . htmlspecialchars($d['grubu']) . '</option>';
                    }
                }
                ?>
            </select>
        </form>

        <?php
        // TEMEL SORGU: Giriş yapanın kendi grupNo'sunu baz alıyoruz
        $sorguEki = grupSorgusu();

        // EK FİLTRE: Eğer select'ten bir grup seçildiyse sorguya AND ile ekliyoruz
        if (!empty($secilenGrup)) {
            $güvenliGrup = $baglanti->real_escape_string($secilenGrup);
            $sorguEki .= " AND grubu = '$güvenliGrup'";
        }

        $listele = "SELECT * FROM sorular" . $sorguEki;
        $result = $baglanti->query($listele);

        echo "<table>
                <tr>
                    <th>Soru Grubu</th>
                    <th>Soru Başlığı</th>
                    <th>Cevap 1</th>
                    <th>Cevap 2</th>
                    <th>Cevap 3</th>
                    <th>Cevap 4</th>
                    <th>Düzenle</th>
                </tr>";

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($row["grubu"])."</td>";
                echo "<td>".htmlspecialchars($row["soruBaslik"])."</td>";
                echo "<td>".htmlspecialchars($row["soru1"])."</td>";
                echo "<td>".htmlspecialchars($row["soru2"])."</td>";
                echo "<td>".htmlspecialchars($row["soru3"])."</td>";
                echo "<td>".htmlspecialchars($row["soru4"])."</td>";
                echo "<td>
                        <form method='post' action='anketSoru_duzenle.php'>
                            <input type='hidden' name='id' value='".$row["soruID"]."'>
                            <input type='submit' name='duzenle' value='Düzenle'>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Seçili gruba ait soru bulunamadı.</td></tr>";
        }
        echo "</table>";
        ?>
    </div>
</body>
</html>
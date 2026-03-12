<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="yonetim.css">
    <title>Anket Sil</title>
</head>
<body>


<?php
 include "menu.php";
    echo menu();
     include "kontrol.php";
    ?>
                    <?php
                require '../baglanti.php';
                

                /* ==== SİLME İŞLEMİ ==== */
                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["sil"])) {
                    // Silinecek anketin grup adını ve durumID'sini alıyoruz
                    $silinecekID = (int)$_POST['id'];
                    $silinecekGrup = $_POST['grup_adi'];

                    // 1. Önce sorular tablosundan o gruba ait TÜM soruları sil
                    $soruSil = $baglanti->prepare("DELETE FROM sorular WHERE grubu = ?");
                    $soruSil->bind_param("s", $silinecekGrup);
                    $soruSil->execute();

                    // 2. Sonra durumlar tablosundan anketin kendisini sil
                    $anketSil = $baglanti->prepare("DELETE FROM durumlar WHERE durumID = ?");
                    $anketSil->bind_param("i", $silinecekID);
                    $anketSil->execute();

                    echo "<script>alert('Anket grubu ve bağlı tüm sorular başarıyla silindi!'); window.location.href='anketler_sil.php';</script>";
                }

                /* ==== LİSTELEME KISMI ==== */
                // Burada soruları değil, durumlar (gruplar) tablosunu listelemek daha mantıklı 
                // çünkü "grubu silmek" istiyoruz.
                $listele = "SELECT * FROM durumlar". grupSorgusu();
                $result = $baglanti->query($listele);
                ?>
                <div class="icerik">
         <?php
        
                    girisKontrol();
                    ?>

                
                    <table>
                        <tr>
                            <th>Anket Grubu</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>

                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["grubu"]) ?></td>
                                    <td><?= $row["durumu"] == 1 ? 'Aktif' : 'Pasif' ?></td>
                                    <td>
                                        <form method="post" action="anketler_sil.php">
                                            <input type="hidden" name="id" value="<?= $row["durumID"] ?>">
                                            <input type="hidden" name="grup_adi" value="<?= $row["grubu"] ?>">
                                            <input type="submit" name="sil" value="Grubu ve Soruları Sil" style="background-color:red; color:white; border:none; padding:5px 10px; cursor:pointer; border-radius:5px;">
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3">Silinecek anket bulunamadı.</td></tr>
                        <?php endif; ?>
                    </table>
</div>
</body>
</html>
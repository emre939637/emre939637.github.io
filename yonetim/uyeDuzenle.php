<link rel="stylesheet" href="yonetim.css">
<link rel="stylesheet" href="../style.css">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
    body { padding: 15px; background-color: #f4f4f4; }
    
    .duzenle-kutusu { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h3 { color: #333; margin-bottom: 15px; text-align: center; }
    
    .form-grup { margin-bottom: 12px; }
    label { display: block; font-size: 13px; color: #666; margin-bottom: 5px; }
    input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
    
    .buton-grubu { margin-top: 20px; display: flex; gap: 10px; justify-content: center; }
    
    button {
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        color: white;
        flex: 1;
    }
    .btn-kaydet { background-color: #28a745; }
    .btn-vazgec { background-color: #333; }
    button:hover { opacity: 0.9; }
</style>

<?php
require '../baglanti.php';

// 1. ADIM: GÜNCELLEME İŞLEMİ (POST GELDİĞİNDE)
if (isset($_POST['guncelle'])) {
    $id = intval($_POST['uyeID']);
    $ad = $_POST['kisiAdi'];
    $soyad = $_POST['kisiSoyadi'];
    $bolum = $_POST['bolum'];
    $grupNo = $_POST['grupNo'];
    $yetki = $_POST['yetki'];

$guncelle = $baglanti->prepare("UPDATE uyeler SET kisiAdi=?, kisiSoyadi=?, bolum=?, grupNo=?, yetkiDurum=? WHERE uyeID=?");
$guncelle->bind_param("sssssi", $ad, $soyad, $bolum, $grupNo, $yetki, $id);
    if ($guncelle->execute()) {
        echo "<div style='text-align:center; color:green;'>Başarıyla Güncellendi!</div>";
        echo "<script>setTimeout(function(){ window.parent.location.reload(); }, 1000);</script>";
        exit;
    }
}

// 2. ADIM: MEVCUT BİLGİLERİ FORMA GETİR (GET İLE ID GELDİĞİNDE)
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sorgu = $baglanti->query("SELECT * FROM uyeler WHERE uyeID=$id");
    
    if ($row = $sorgu->fetch_assoc()) {
        ?>
        <div class="duzenle-kutusu">
            <h3>Üye Bilgilerini Güncelle</h3>
            <form method="POST" action="">
                <input type="hidden" name="uyeID" value="<?php echo $id; ?>">
                
                <div class="form-grup">
                    <label>Adı</label>
                    <input type="text" name="kisiAdi" value="<?php echo htmlspecialchars($row['kisiAdi']); ?>" required>
                </div>
                
                <div class="form-grup">
                    <label>Soyadı</label>
                    <input type="text" name="kisiSoyadi" value="<?php echo htmlspecialchars($row['kisiSoyadi']); ?>" required>
                </div>
                
                <div class="form-grup">
                    <label>Bölümü</label>
                    <input type="text" name="bolum" value="<?php echo htmlspecialchars($row['bolum']); ?>">
                </div>

                <div class="form-grup">
                    <label>Grup No</label>
                    <input type="text" name="grupNo" value="<?php echo htmlspecialchars($row['grupNo']); ?>">
                </div>
                <div class="form-grup">
                    <label>Yetkisi</label>
                    <select name="yetki" id="yetki" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="admin" <?php echo ($row['yetkiDurum'] == 'admin') ? 'selected' : ''; ?>>admin</option>
                        <option value="user" <?php echo ($row['yetkiDurum'] == 'user') ? 'selected' : ''; ?>>user</option>
                    </select>
                </div>

                <div class="buton-grubu">
                    <button type="submit" name="guncelle" class="btn-kaydet">Değişiklikleri Kaydet</button>
                    <button type="button" onclick="window.parent.modalKapat()" class="btn-vazgec">İptal</button>
                </div>
            </form>
        </div>
        <?php
    }
}
?>
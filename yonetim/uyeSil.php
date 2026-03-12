<link rel="stylesheet" href="yonetim.css">
<link rel="stylesheet" href="../style.css">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
    body { 
        padding: 20px; 
        display: flex; 
        flex-direction: column; 
        align-items: center; 
        justify-content: center; 
        background-color: #f4f4f4; 
    }
    .bilgi-kutusu { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 100%; }
    h3 { color: #d9534f; margin-bottom: 15px; }
    p { margin-bottom: 8px; font-size: 14px; color: #555; }
    .buton-grubu { margin-top: 20px; display: flex; gap: 10px; justify-content: center; }
    
    button {
        padding: 12px 25px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        color: white;
    }
    .btn-sil { background-color: #ff4d4d; width: 45%; }
    .btn-vazgec { background-color: #333; width: 45%; }
    button:hover { opacity: 0.8; transform: translateY(-2px); }
</style>

<?php
require '../baglanti.php';

// 1. ADIM: SİLME İŞLEMİ TETİKLENDİ Mİ?
if (isset($_POST['gercektenSil'])) {
    $id = intval($_POST['uyeID']);
    
    $sil = $baglanti->prepare("DELETE FROM uyeler WHERE uyeID = ?");
    $sil->bind_param("i", $id);

    if ($sil->execute()) {
        echo "<div style='text-align:center;'>
                <h2 style='color:green;'>Başarıyla Silindi!</h2>
                <p>Liste güncelleniyor...</p>
              </div>";
        // Ana sayfayı (parent) yenile ve modalı kapat
        echo "<script>
                setTimeout(function(){ 
                    window.parent.location.reload(); 
                }, 1000);
              </script>";
        exit; // İşlem bittiği için alttaki formu gösterme
    }
}

// 2. ADIM: ÜYE BİLGİLERİNİ GETİR
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sorgu = $baglanti->query("SELECT * FROM uyeler WHERE uyeID=$id");
    
    if ($row = $sorgu->fetch_assoc()) {
        ?>
        <div class="bilgi-kutusu">
            <h3>Üyeyi Silmek İstediğinize Emin misiniz?</h3>
            <p>Üye: <b><?php echo htmlspecialchars($row['kisiAdi'] . " " . $row['kisiSoyadi']); ?></b></p>
            <p>Bölüm: <b><?php echo htmlspecialchars($row['bolum']); ?></b></p>
            <p>Grup No: <b><?php echo htmlspecialchars($row['grupNo']); ?></b></p>

            <form method="POST" action="">
                <input type="hidden" name="uyeID" value="<?php echo $id; ?>">
                <div class="buton-grubu">
                    <button type="submit" name="gercektenSil" class="btn-sil">Evet, Sil</button>
                    <button type="button" onclick="window.parent.modalKapat()" class="btn-vazgec">Vazgeç</button>
                </div>
            </form>
        </div>
        <?php
    } else {
        echo "Kullanıcı bulunamadı.";
    }
} else {
    echo "Geçersiz İstek.";
}
?>
<?php
include "baglanti.php";
// Oturumu başlatıyoruz ki son.php'de ismi görebilelim
if (session_status() === PHP_SESSION_NONE) { session_start(); }

/* ==== 1. HANGİ ANKETİN AÇILACAĞINI BELİRLE ==== */
$anket_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($anket_id === 0) {
    echo "<h2 style='color:orange; text-align:center;'>Lütfen katılmak istediğiniz anketi seçin.</h2>";
    exit;
}

/* ==== 2. AKTİF ANKET BİLGİSİNİ ÇEK ==== */
$stmtAnket = $baglanti->prepare("SELECT * FROM durumlar WHERE durumID = ? AND durumu = 1 LIMIT 1");
$stmtAnket->bind_param("i", $anket_id);
$stmtAnket->execute();
$anketSonuc = $stmtAnket->get_result();

if ($anketSonuc->num_rows == 0) {
    echo "<h2 style='color:red; text-align:center;'>Geçersiz veya pasif anket!</h2>";
    exit;
}
$anket = $anketSonuc->fetch_assoc();

/* ==== 3. FORM GÖNDERİLDİYSE KAYIT YAP ==== */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ad"])) {
    function maskle($veri) {
        $ilk = mb_substr($veri, 0, 1, "UTF-8");
        return $ilk . str_repeat("*", mb_strlen($veri, "UTF-8") - 1);
    }

    $adMask    = maskle(trim($_POST["ad"]));
    $soyadMask = maskle(trim($_POST["soyad"]));
    $bolum     = trim($_POST["bolum"]);
    $yas       = (int)$_POST["yas"];
    $cins      = $_POST["cins"];

    // --- EKLEME: son.php için isimleri session'a kaydediyoruz ---
    $_SESSION["son_ad"] = $adMask;
    $_SESSION["son_soyad"] = $soyadMask;
    // -----------------------------------------------------------

    $stmtUser = $baglanti->prepare("INSERT INTO kullanicilar (ad, soyad, bolum, yas, cins) VALUES (?, ?, ?, ?, ?)");
    $stmtUser->bind_param("sssis", $adMask, $soyadMask, $bolum, $yas, $cins);
    $stmtUser->execute();
    $kullaniciId = $baglanti->insert_id;

    if (isset($_POST["cevap"]) && is_array($_POST["cevap"])) {
        $stmtCevap = $baglanti->prepare("INSERT INTO cevaplar (kullaniciID, soruID, cevap) VALUES (?, ?, ?)");
        foreach ($_POST["cevap"] as $soruId_db => $cevap) {
            $stmtCevap->bind_param("iis", $kullaniciId, $soruId_db, $cevap);
            $stmtCevap->execute();
        }
    }
    // Yönlendirme
    echo "<script>window.location.href='son.php';</script>";
    exit;
}

/* ==== 4. SORULARI GETİR (EN ÖNEMLİ KISIM) ==== */
$grupAdi = $anket['grubu']; 
$stmtSorular = $baglanti->prepare("SELECT * FROM sorular WHERE grubu = ?");
$stmtSorular->bind_param("s", $grupAdi);
$stmtSorular->execute();
$sorularSonuc = $stmtSorular->get_result();

$sorular = [];
while ($row = $sorularSonuc->fetch_assoc()) {
    $sorular[] = $row;
}

if (empty($sorular)) {
    echo "<h2 style='text-align:center;'>HATA: '{$grupAdi}' grubuna ait soru bulunamadı!</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Anket: <?= htmlspecialchars($anket['grubu']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<form method="post" id="anketForm">
    <div class="kutu" id="step0">
        <h3><?= htmlspecialchars($anket['grubu']) ?> - Kişisel Bilgiler</h3>
        <input type="text" name="ad" id="ad" placeholder="Ad">
        <input type="text" name="soyad" id="soyad" placeholder="Soyad">
        <input type="text" name="bolum" id="bolum" placeholder="Bölüm">
        <input type="text" name="yas" id="yas" placeholder="Yaş">
        <div class="gorunen-radio">Cinsiyet:
        <input type="radio" name="cins" value="Erkek" class="gorunen-radio"> Erkek
        <input type="radio" name="cins" value="Kadın" class="gorunen-radio"> Kadın
        <div class="hata" id="hataStep0">*Tüm Alanları Doldurmak Zorunludur.*</div>
      </div>
    </div>

    <div class="kutu" id="anketAlan" style="display:none">
        <?php foreach ($sorular as $i => $soru) { ?>
            <div class="radio-grup-kutu" id="soru<?= $i ?>" style="<?= $i==0 ? '' : 'display:none' ?>">
                <h3><?= htmlspecialchars($soru["soruBaslik"]) ?></h3>
                
                <?php for ($j=1; $j<=4; $j++) {
                    if (!empty($soru["soru$j"])) { 
                        $radio_id = "s{$i}_{$j}";
                        ?>
                        <input type="radio" id="<?= $radio_id ?>" name="cevap[<?= $soru['soruID'] ?>]" value="<?= $j ?>">
                        <label for="<?= $radio_id ?>"><?= htmlspecialchars($soru["soru$j"]) ?></label><br>
                    <?php }
                } ?>
                <div class="hata" id="hata<?= $i ?>" style="display:none; color:red;">*Bu soru boş bırakılamaz*</div>
            </div>
        <?php } ?>
    </div>

    <input type="submit" value="Devam Et" style="margin-top:20px;">
</form>

<script>
let adim = 0;
let aktifSoru = 0;
let toplamSoru = <?= count($sorular) ?>;
document.getElementById("anketForm").addEventListener("submit", function(e){
    e.preventDefault();

    if(adim === 0){
        const kutu0 = document.getElementById("step0");
        const ad = document.getElementById("ad").value.trim();
        const soyad = document.getElementById("soyad").value.trim();
        const bolum = document.getElementById("bolum").value.trim();
        const yas = document.getElementById("yas").value.trim();
        const cins = document.querySelector('input[name="cins"]:checked');

        if(!ad || !soyad || !bolum || !yas || !cins){
            kutu0.classList.add("sallan");
            document.getElementById("hataStep0").style.display = "block";
            setTimeout(() => { kutu0.classList.remove("sallan"); }, 400);
            return;
        }

        kutu0.style.display = "none";
        document.getElementById("anketAlan").style.display = "block";
        adim = 1;
        return;
    }

    const aktifSoruKutusu = document.getElementById("soru" + aktifSoru);
    const secili = aktifSoruKutusu.querySelector('input[type="radio"]:checked');

    if(!secili){
        aktifSoruKutusu.classList.add("sallan");
        document.getElementById("hata" + aktifSoru).style.display = "block";
        setTimeout(() => { aktifSoruKutusu.classList.remove("sallan"); }, 400);
        return;
    }

    document.getElementById("soru" + aktifSoru).style.display = "none";
    aktifSoru++;

    if(aktifSoru < toplamSoru){
        document.getElementById("soru" + aktifSoru).style.display = "block";
    } else {
        this.submit(); 
    }
});
</script>
</body>
</html>
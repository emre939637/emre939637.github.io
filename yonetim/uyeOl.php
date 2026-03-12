<!DOCTYPE html>
<html lang="tr-tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .kutu {
            position: relative; 
            width: 400px; 
            min-height: 650px;
             border-radius: 30px;
            background: #f0f8ff; 
            text-align: center;
             padding: 30px 10px; 
             margin: 20px auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            font-family: sans-serif;
        }
    
        .input-group { 
            position: relative; 
            margin-bottom: 30px;
         }
        label {
            position: absolute; 
            left: 90px; 
            top: 10px; 
            padding: 0 6px;
            transition: all 0.3s ease; 
            color: gray; 
            pointer-events: none;
        }
             @media (max-width: 500px) {
                 .kutu {
            position: relative; 
            width: 100%; 
            min-height: 100%;
             border-radius: 30px;
            background: #f0f8ff; 
            text-align: center;
             padding: 20px 10px; 
             margin: 10px auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            font-family: sans-serif;
        }
         label {
            position: absolute; 
            left: 30px; 
            top: 10px; 
            padding: 0 6px;
            transition: all 0.3s ease; 
            color: gray; 
            pointer-events: none;
        }
    }
        input[type="text"], input[type="password"] {
            width: 200px; 
            height: 40px; 
            border: none; 
            border-bottom: 0.8px solid black;
            outline: none; 
            padding: 0 10px; 
            background: none;
        }
        input:focus + label, input:not(:placeholder-shown) + label {
            transform: translateY(-20px) translateX(1px); 
            color: black;
            background: #f0f8ff;
             font-size: 14px; 
             font-weight: bold;
        }
        input:focus {
             border: 2px solid rgb(13, 66, 241); 
             border-radius:5px;
            }
        input[type="submit"] {
            background-color: rgb(13, 66, 241); 
            color: white; 
            border: none;
            padding: 10px 30px;
             border-radius: 25px;
              cursor: pointer; 
              font-size: 16px;
        }
        /* Uyarı Mesajları */
        .hata-mesaji { 
            color: red; 
            font-size: 11px; 
            display: block; 
            margin-top: 5px;
            min-height: 15px;
            font-weight: bold; 
            }
        .onay-mesaji { 
            color: green; 
            font-size: 11px;
             display: block;
              margin-top: 5px; 
              font-weight: bold;
             }
    </style>
    <title>Üye Ol</title>
</head>
<body>
    <div class="kutu">
        <p style="color:red;"><span><b>*Grup Numaranızı <i>"bölüm_kodu"</i>ve <i>"grup_no"</i><br>Şeklinde Yazınız</b> (Örn:1135)</span></p>
        <p style="color:red;"><span><b>*Grup Numaranızı kendiniz belirleyebilirsiniz</b></span></p>
        
         <form method="POST" action="" onsubmit="return formKontrol();">
            <div class="input-group">
                <input type="text" name="kisiAd" id="kisiAd" placeholder=" " required oninput="this.value = this.value.replace(/[0-9]/g, '')">
                <label>Adınız:</label>
            </div>
            <div class="input-group">
                <input type="text" name="kisiSoyad" id="kisiSoyad" placeholder=" " required oninput="this.value = this.value.replace(/[0-9]/g, '')">
                <label>Soyadınız:</label>
            </div>
            <div class="input-group">
                <input type="text" name="bolum" id="bolum" placeholder=" " required>
                <label>Bölümünüz:</label>
            </div>
            <div class="input-group">
                <input type="text" name="grupNo" id="grupNo" placeholder=" " required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <label>Grup Numaranız:</label>
                <span id="grupHata" class="hata-mesaji"></span> 
            </div>
            <div class="input-group">
                <input type="text" name="kad" id="kad" placeholder=" " required>
                <label>Kullanıcı Adı:</label>
                 <span id="kadHata" class="hata-mesaji"></span> 
            </div>
            <div class="input-group">
                <input type="password" name="sifre" id="psd" placeholder=" " required>
                <label>Şifreniz:</label>
            </div>
            <p><input type="checkbox" onclick="document.getElementById('psd').type = this.checked ? 'text' : 'password'"> Şifreyi Göster</p>
            <p><input type="submit" value="Kaydol"></p>
        </form>
    </div>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../baglanti.php';
    
    $kad = $_POST['kad'];
    $grupNo = $_POST['grupNo'];
    $kisiAd = $_POST['kisiAd'];
    $kisiSoyad = $_POST['kisiSoyad'];
    $bolum = $_POST['bolum'];
    $sifre = $_POST['sifre'];

    // Aynı kullanıcı adı VE aynı grup no kombinasyonunu kontrol et
    $kontrol = $baglanti->prepare("SELECT uyeID FROM uyeler WHERE kullaniciAdi = ? AND grupNo = ?");
    $kontrol->bind_param("ss", $kad, $grupNo);
    $kontrol->execute();
    $sonuc = $kontrol->get_result();

    if ($sonuc->num_rows > 0) {
        // Aynı grupNo + aynı kullanıcı adı zaten var → engelle
        echo "<script>alert('Bu grup numarasında aynı kullanıcı adı zaten kayıtlı! Lütfen farklı bir kullanıcı adı seçin.'); window.history.back();</script>";
    } else {
        // Kombinasyon yoksa kaydet (grupNo aynı olsa bile farklı kullanıcı adıyla olur)
        $ekle = $baglanti->prepare("INSERT INTO uyeler (kisiAdi, kisiSoyadi, kullaniciAdi, sifre, yetkiDurum, bolum, grupNo) 
                                    VALUES (?, ?, ?, ?, 'user', ?, ?)");
        $ekle->bind_param("ssssss", $kisiAd, $kisiSoyad, $kad, $sifre, $bolum, $grupNo);
        
        if ($ekle->execute()) {
            echo "<script>alert('Üye Oldunuz!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Kayıt sırasında bir hata oluştu!'); window.history.back();</script>";
        }
    }
    $baglanti->close();
}
?>

    <script>
document.addEventListener('DOMContentLoaded', function() {

    const kadInput = document.getElementById('kad');
    const grupInput = document.getElementById('grupNo');
    const kadHata = document.getElementById('kadHata');

    function kontrol() {
        let kad = kadInput.value.trim();
        let grup = grupInput.value.trim();

        if (kad.length < 2 || grup.length < 1) {
            kadHata.innerText = "";
            return;
        }

        let veri = new FormData();
        veri.append('kad', kad);
        veri.append('grupNo', grup);

        fetch('kntrl.php', { method: 'POST', body: veri })
        .then(res => res.text())
        .then(sonuc => {
            if (sonuc.trim() === "mevcut") {
                kadHata.innerText = "❌ Bu grupta bu kullanıcı adı zaten var!";
                kadHata.className = "hata-mesaji";
            } else {
                kadHata.innerText = "✅ Bu grup için kullanılabilir";
                kadHata.className = "onay-mesaji";
            }
        })
        .catch(err => {
            kadHata.innerText = "⚠️ Sunucuya ulaşılamadı!";
            kadHata.className = "hata-mesaji";
        });
    }

    kadInput.addEventListener('input', kontrol);
    grupInput.addEventListener('input', kontrol);

});

function formKontrol() {
    const kadHata = document.getElementById('kadHata');
    
    if (kadHata.innerText.includes("❌")) {
        return false; // Sadece engeller, alert yok
    }
    if (!kadHata.innerText.includes("✅")) {
        return false; // Sadece engeller, alert yok
    }
    return true;
}
</script>
</body>
</html>
<link rel="stylesheet" href="menu.css">
<?php

function menu() {
    // Session kontrolü (Hata almamak için)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $ad = isset($_SESSION["kisiAdi"]) ? $_SESSION["kisiAdi"] : "Giriş Yap";
    $soyad = isset($_SESSION["kisiSoyadi"]) ? $_SESSION["kisiSoyadi"] : "";
    $yd = isset($_SESSION["yetkiDurum"]) ? $_SESSION["yetkiDurum"] : "misafir";
    $adSoyad = $ad . " " . $soyad;

    // Menü başlangıcı
    $menu_html = '
    <div>
        <button class="menu-toggle" id="menuToggle">☰</button>
        <ul id="nav">
            <li class="dropdown">
                <a href="#">Anketler</a>
                <ul class="submenu">
                    <li><a href="yeni_anket.php">Yeni Anket Ekle</a></li>
                    <li><a href="aktif_anket.php">Aktif Anketler</a></li>
                    <li><a href="anketler_sil.php">Anket Sil</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a href="#">Anket Soruları</a>
                <ul class="submenu">
                    <li><a href="soru_ekle.php">Yeni Anket Soruları Ekle</a></li>
                    <li><a href="soru_duzenle.php">Anket Sorularını Düzenle</a></li>
                    <li><a href="soru_sil.php">Anket Sorularını Sil</a></li>
                </ul>
            </li>
            <li><a href="anket_sonuc.php">Anket Sonuç</a></li>';

    // ŞART: Admin ise "Üyeler" linkini ekle
   if (strtolower($yd) == 'admin') {
        $menu_html .= '<li><a href="uyeler.php">Üyeler</a></li>';
    }

    // Menü sonu ve JavaScript
    $menu_html .= '
            <li><a href="index.php">' . htmlspecialchars($adSoyad . " (" . $yd . ")") . '</a></li>
            <li><a href="logout.php">Çıkış Yap</a></li>
        </ul> 
    </div>
    <script>
        const menuToggle = document.getElementById("menuToggle");
        const nav = document.getElementById("nav");
        if(menuToggle) {
            menuToggle.addEventListener("click", () => {
                nav.classList.toggle("active");
            });
        }
    </script>';

    return $menu_html;
}

?>
<?php

require '../baglanti.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kad = isset($_POST['kad']) ? $_POST['kad'] : '';
    $grupNo = isset($_POST['grupNo']) ? $_POST['grupNo'] : '';

    if (empty($kad) || empty($grupNo)) {
        echo "bos";
        exit;
    }

    $stmt = $baglanti->prepare("SELECT COUNT(*) as sayi FROM uyeler WHERE kullaniciAdi = ? AND grupNo = ?");
    $stmt->bind_param("ss", $kad, $grupNo);
    $stmt->execute();
    $sonuc = $stmt->get_result();
    $satir = $sonuc->fetch_assoc();

    echo ($satir['sayi'] > 0) ? "mevcut" : "musait";
    $baglanti->close();
}

?>
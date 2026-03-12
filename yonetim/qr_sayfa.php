<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; text-align: center; padding: 20px; }
        .image img { width: 200px; height: 200px; border: 1px solid #ddd; padding: 10px; border-radius: 8px; }
        .btn-container { margin-top: 20px; }
        .download-btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <?php
    // Gelen linki alıyoruz
    $gelen_link = isset($_GET['link']) ? $_GET['link'] : 'https://google.com';
    $qr_api_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($gelen_link);
    ?>

    <div class="image">
        <img id="qrimg" src="<?php echo $qr_api_url; ?>" alt="QR Code">
    </div>

    <div class="btn-container">
        <button class="download-btn" onclick="indirQR()">QR Kodu İndir (.jpg)</button>
    </div>

    <script>
        async function indirQR() {
            const img = document.getElementById('qrimg');
            const link = "<?php echo $gelen_link; ?>"; // Dosya adı için
            
            try {
                // Resmi blob olarak çekiyoruz (CORS sorununu aşmak için önemli)
                const response = await fetch(img.src);
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                
                // Geçici bir indirme linki oluştur
                const a = document.createElement('a');
                a.href = url;
                // Dosya ismini temizle ve .jpg yap
                a.download = "anket-qr.jpg";
                
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            } catch (err) {
                alert("İndirme sırasında bir hata oluştu.");
                console.error(err);
            }
        }
    </script>
</body>
</html>
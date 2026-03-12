<link rel="stylesheet" href="yonetim.css">
<?php

        
        session_start();
        session_unset();
        session_destroy();
       echo "<div class='loader-kutu'><div class='loader'></div><p>Çıkış Yapılıyor Lütfen Bekleyiniz...</p></div>";
       header("refresh:2;url=index.php");
?>
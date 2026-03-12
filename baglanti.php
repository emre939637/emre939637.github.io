<?php
$sunucuAdi="localhost";
$kullaniciAdi="root";
$rootSifre="";
$veritabaniAdi="uni_anket";

$baglanti=mysqli_connect($sunucuAdi,$kullaniciAdi,$rootSifre,$veritabaniAdi);
if(!$baglanti){
    die("Bağlantı Kurulamadı...".mysqli_connect_error());
}$baglanti->set_charset("utf8mb4"); 

$sorular = [];
$result = $baglanti->query("SELECT * FROM sorular ORDER BY soruID");

while ($row = $result->fetch_assoc()) {
  $sorular[] = $row;
}
?>    

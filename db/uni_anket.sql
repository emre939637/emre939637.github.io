-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 12 Mar 2026, 08:36:23
-- Sunucu sürümü: 9.1.0
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `uni_anket`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cevaplar`
--

DROP TABLE IF EXISTS `cevaplar`;
CREATE TABLE IF NOT EXISTS `cevaplar` (
  `cevapID` int NOT NULL AUTO_INCREMENT,
  `soruID` int NOT NULL,
  `kullaniciID` int NOT NULL,
  `cevap` text CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`cevapID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `durumlar`
--

DROP TABLE IF EXISTS `durumlar`;
CREATE TABLE IF NOT EXISTS `durumlar` (
  `durumID` int NOT NULL AUTO_INCREMENT,
  `grubu` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `durumu` tinyint(1) NOT NULL,
  `uyeID` int NOT NULL,
  `grupNo` int NOT NULL,
  PRIMARY KEY (`durumID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

DROP TABLE IF EXISTS `kullanicilar`;
CREATE TABLE IF NOT EXISTS `kullanicilar` (
  `kullaniciID` int NOT NULL AUTO_INCREMENT,
  `ad` varchar(25) COLLATE utf8mb4_turkish_ci NOT NULL,
  `soyad` varchar(25) COLLATE utf8mb4_turkish_ci NOT NULL,
  `bolum` varchar(20) COLLATE utf8mb4_turkish_ci NOT NULL,
  `yas` varchar(2) COLLATE utf8mb4_turkish_ci NOT NULL,
  `cins` varchar(5) COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`kullaniciID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sorular`
--

DROP TABLE IF EXISTS `sorular`;
CREATE TABLE IF NOT EXISTS `sorular` (
  `soruID` int NOT NULL AUTO_INCREMENT,
  `grubu` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `soruBaslik` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `soru1` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `soru2` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `soru3` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `soru4` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `grupNo` int NOT NULL,
  PRIMARY KEY (`soruID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `uyeler`
--

DROP TABLE IF EXISTS `uyeler`;
CREATE TABLE IF NOT EXISTS `uyeler` (
  `uyeID` int NOT NULL AUTO_INCREMENT,
  `kisiAdi` varchar(10) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kisiSoyadi` varchar(10) COLLATE utf8mb4_turkish_ci NOT NULL,
  `kullaniciAdi` varchar(20) COLLATE utf8mb4_turkish_ci NOT NULL,
  `sifre` char(6) COLLATE utf8mb4_turkish_ci NOT NULL,
  `yetkiDurum` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `bolum` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_turkish_ci NOT NULL,
  `grupNo` varchar(20) COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`uyeID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Tablo döküm verisi `uyeler`
--

INSERT INTO `uyeler` (`uyeID`, `kisiAdi`, `kisiSoyadi`, `kullaniciAdi`, `sifre`, `yetkiDurum`, `bolum`, `grupNo`) VALUES
(1, 'Metehan', 'Güler', 'Metehan', 'mete06', 'admin', 'Bilgisayar Progamcılığı', '1135'),
(2, 'Emre', 'Demirel', 'Emre', '939637', 'admin', 'Bilgisayar Programcı', '1135');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

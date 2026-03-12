<link rel="stylesheet" href="yonetim.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<?php 
include "menu.php";
echo menu();
?>

<div class="icerik" style="background-color: #f8f9fc; padding: 20px;">
    <?php
    include "kontrol.php";
    girisKontrol(); 
    require '../baglanti.php';

    $secilenGrup = isset($_GET['grup']) ? $_GET['grup'] : '';
    $gruplarSorgu = $baglanti->query("SELECT DISTINCT grubu FROM durumlar " . grupSorgusu());
    ?>

    <div class="kutu" style="max-width: 1200px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 0 15px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2>📊 Detaylı Soru Analiz Paneli</h2>
            <?php if ($secilenGrup): ?>
                <button onclick="exportExcel()" style="background: #1d6f42; color: white; padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; font-weight: bold;">
                    Excel İndir
                </button>
            <?php endif; ?>
        </div>

        <form method="GET">
            <select name="grup" onchange="this.form.submit()" style="padding: 10px; width: 350px; border-radius: 5px; border: 1px solid #ddd;">
                <option value="">--- Bir Grup Seçiniz ---</option>
                <?php while($g = $gruplarSorgu->fetch_assoc()): ?>
                    <option value="<?= $g['grubu'] ?>" <?= $secilenGrup == $g['grubu'] ? 'selected' : '' ?>>
                        <?= $g['grubu'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <?php if ($secilenGrup): ?>
            <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

            <?php
            $sorularSorgu = $baglanti->prepare("SELECT * FROM sorular WHERE grubu = ?");
            $sorularSorgu->bind_param("s", $secilenGrup);
            $sorularSorgu->execute();
            $sorular = $sorularSorgu->get_result();

            while ($soru = $sorular->fetch_assoc()):
                $soruID = $soru['soruID'];

                // ANALİZ SORGUSU
                $analizSorgu = $baglanti->prepare("
                    SELECT k.cins, k.bolum, k.yas, c.cevap 
                    FROM cevaplar c 
                    JOIN kullanicilar k ON c.kullaniciID = k.kullaniciID 
                    WHERE c.soruID = ?
                ");
                $analizSorgu->bind_param("i", $soruID);
                $analizSorgu->execute();
                $sonuclar = $analizSorgu->get_result();

                $data = [
                    'cins' => ['Erkek' => 0, 'Kadın' => 0],
                    'bolum' => [],
                    'yas' => ['18-25' => 0, '26-35' => 0, '36-45' => 0, '46+' => 0],
                    'cevaplar' => [1 => 0, 2 => 0, 3 => 0, 4 => 0] // Soru şıkları için
                ];

                while($row = $sonuclar->fetch_assoc()) {
                    if(isset($data['cins'][$row['cins']])) $data['cins'][$row['cins']]++;
                    $b = $row['bolum'];
                    $data['bolum'][$b] = ($data['bolum'][$b] ?? 0) + 1;
                    $y = (int)$row['yas'];
                    if($y <= 25) $data['yas']['18-25']++;
                    elseif($y <= 35) $data['yas']['26-35']++;
                    elseif($y <= 45) $data['yas']['36-45']++;
                    else $data['yas']['46+']++;
                    
                    // Cevap sayılarını topla
                    if(isset($data['cevaplar'][$row['cevap']])) $data['cevaplar'][$row['cevap']]++;
                }

                $cevapEtiketleri = [
                    $soru['soru1'] ?: 'Seçenek 1',
                    $soru['soru2'] ?: 'Seçenek 2',
                    $soru['soru3'] ?: 'Seçenek 3',
                    $soru['soru4'] ?: 'Seçenek 4'
                ];
            ?>
                <div class="soru-analiz-blogu" style="margin-bottom: 60px; border: 2px solid #eaecf4; padding: 25px; border-radius: 15px; background: #fff;">
                    <h3 style="color: #4e73df; margin-bottom: 10px;"><?= htmlspecialchars($soru['soruBaslik']) ?></h3>
                    <p style="color: #858796; margin-bottom: 25px;">Bu soruya toplam <b><?= array_sum($data['cevaplar']) ?></b> kişi cevap verdi.</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
                        
                        <div style="background:#f8f9fc; padding:15px; border-radius:10px;">
                            <canvas id="ans_<?= $soruID ?>"></canvas>
                        </div>

                        <div style="background:#f8f9fc; padding:15px; border-radius:10px;">
                            <canvas id="cins_<?= $soruID ?>"></canvas>
                        </div>

                        <div style="background:#f8f9fc; padding:15px; border-radius:10px;">
                            <canvas id="bolum_<?= $soruID ?>"></canvas>
                        </div>

                        <div style="background:#f8f9fc; padding:15px; border-radius:10px;">
                            <canvas id="yas_<?= $soruID ?>"></canvas>
                        </div>
                    </div>

                    <table class="analiz-tablo" style="width:100%; margin-top:20px; border-collapse: collapse; font-size:13px;">
                        <tr style="background:#4e73df; color:white;">
                            <th style="padding:8px; border:1px solid #ddd;">Seçenek</th>
                            <th style="padding:8px; border:1px solid #ddd;">Verilen Cevap Sayısı</th>
                            <th style="padding:8px; border:1px solid #ddd;">Yüzde</th>
                        </tr>
                        <?php 
                        $toplam = array_sum($data['cevaplar']);
                        foreach($data['cevaplar'] as $key => $val): 
                            $yuzde = $toplam > 0 ? round(($val / $toplam) * 100, 1) : 0;
                        ?>
                        <tr>
                            <td style="padding:8px; border:1px solid #ddd;"><?= $cevapEtiketleri[$key-1] ?></td>
                            <td style="padding:8px; border:1px solid #ddd; text-align:center;"><?= $val ?></td>
                            <td style="padding:8px; border:1px solid #ddd; text-align:center;">%<?= $yuzde ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <script>
                // CEVAP DAĞILIMI (Yeni Grafik)
                new Chart(document.getElementById('ans_<?= $soruID ?>'), {
                    type: 'bar', // Cevaplar için sütun grafiği daha iyi okunur
                    data: { 
                        labels: <?= json_encode($cevapEtiketleri) ?>, 
                        datasets: [{ 
                            label: 'Cevap Sayısı',
                            data: <?= json_encode(array_values($data['cevaplar'])) ?>, 
                            backgroundColor: '#4e73df' 
                        }] 
                    },
                    options: { maintainAspectRatio: false, plugins: { title: { display: true, text: 'Cevap Dağılımı' } } }
                });

                // DİĞER GRAFİKLER (Öncekiyle aynı mantık)
                new Chart(document.getElementById('cins_<?= $soruID ?>'), {
                    type: 'pie',
                    data: { labels: Object.keys(<?= json_encode($data['cins']) ?>), datasets: [{ data: Object.values(<?= json_encode($data['cins']) ?>), backgroundColor: ['#3498db', '#e74c3c'] }] },
                    options: { maintainAspectRatio: false, plugins: { title: { display: true, text: 'Cinsiyet Dağılımı' } } }
                });

                new Chart(document.getElementById('bolum_<?= $soruID ?>'), {
                    type: 'doughnut',
                    data: { labels: Object.keys(<?= json_encode($data['bolum']) ?>), datasets: [{ data: Object.values(<?= json_encode($data['bolum']) ?>), backgroundColor: ['#1cc88a', '#f6c23e', '#36b9cc', '#e74a3b'] }] },
                    options: { maintainAspectRatio: false, plugins: { title: { display: true, text: 'Bölüm Dağılımı' } } }
                });

                new Chart(document.getElementById('yas_<?= $soruID ?>'), {
                    type: 'pie',
                    data: { labels: Object.keys(<?= json_encode($data['yas']) ?>), datasets: [{ data: Object.values(<?= json_encode($data['yas']) ?>), backgroundColor: ['#2ecc71', '#f39c12', '#d35400', '#95a5a6'] }] },
                    options: { maintainAspectRatio: false, plugins: { title: { display: true, text: 'Yaş Dağılımı' } } }
                });
                </script>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function exportExcel() {
    let wb = XLSX.utils.book_new();
    let ws_data = [
        ["ANKET DETAYLI ANALİZ RAPORU"],
        ["Grup:", "<?= $secilenGrup ?>"],
        ["Tarih:", new Date().toLocaleDateString()],
        [],
        ["SORU / KATEGORİ", "DETAY", "ADET", "YÜZDE"]
    ];

    const soruBloklari = document.querySelectorAll('.soru-analiz-blogu');
    
    soruBloklari.forEach((blok) => {
        const soruBasligi = blok.querySelector('h3').innerText;
        ws_data.push([soruBasligi.toUpperCase(), "", "", ""]); 

        // 1. SORU CEVAPLARINI EKLE
        const cevapSatirlari = blok.querySelectorAll('.analiz-tablo tr');
        cevapSatirlari.forEach((satir, index) => {
            if (index === 0) return; 
            const sutunlar = satir.querySelectorAll('td');
            ws_data.push(["Cevap Seçeneği:", sutunlar[0].innerText, sutunlar[1].innerText, sutunlar[2].innerText]);
        });

        // 2. CİNSİYET, BÖLÜM VE YAŞ VERİLERİNİ EKLE
        // Bu verileri grafik nesnelerinden (Chart.js) çekiyoruz
        const charts = blok.querySelectorAll('canvas');
        charts.forEach((canvas) => {
            const chartInstance = Chart.getChart(canvas.id);
            if (chartInstance) {
                const label = chartInstance.options.plugins.title.text; // "Cinsiyet Dağılımı" vb.
                ws_data.push([label, "", "", ""]); // Alt başlık
                
                chartInstance.data.labels.forEach((lab, i) => {
                    const val = chartInstance.data.datasets[0].data[i];
                    ws_data.push(["Dağılım:", lab, val, ""]);
                });
            }
        });
        
        ws_data.push([], ["--------------------------------", "", "", ""]); // Sorular arası ayraç
    });

    let ws = XLSX.utils.aoa_to_sheet(ws_data);
    ws['!cols'] = [{ wch: 45 }, { wch: 30 }, { wch: 15 }, { wch: 15 }];

    XLSX.utils.book_append_sheet(wb, ws, "Anket Detayli Rapor");
    XLSX.writeFile(wb, "Anket_Analiz_Raporu.xlsx");
}
</script>
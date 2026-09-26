<?php
// biodata.php

// Modifikasi 1: Penambahan validasi batas IPK dan kategori baru
function statusKelulusan(float $ipk): string
{
    if ($ipk < 0.00 || $ipk > 4.00) return 'Data IPK Tidak Valid';
    if ($ipk >= 3.50) return 'Sangat Memuaskan';
    if ($ipk >= 3.00) return 'Memuaskan';
    if ($ipk >= 2.00) return 'Cukup';
    return 'Perlu Peningkatan';
}

// Modifikasi 2: Penambahan field baru (universitas, status_aktif, serta mengganti data di dalam menjadi nama sendiri)
$mahasiswa = [
    'nim' => '4521210088',
    'nama' => 'Muhammad Yusuf',
    'universitas' => 'Universitas Pancasila',
    'prodi' => 'Teknik Informatika',
    'semester' => 8,
    'ipk' => 3.60,
    'status_aktif' => true
];
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Biodata Mahasiswa</title>
     <!-- Modifikasi 3: Penambahan CSS untuk antarmuka bergaya "Card" -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
        }
        h1 {
            color: #333;
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-top: 0;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
        }
        li:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            width: 140px;
            text-transform: capitalize;
            color: #555;
        }
        .predikat {
            text-align: center;
            font-weight: bold;
            color: #fff;
            background-color: #28a745;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .error {
            background-color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="card">
        <h1>Biodata Mahasiswa</h1>
        <ul>
            <?php foreach ($mahasiswa as $kunci => $nilai): ?>
                <li>
                    <!-- Mengganti underscore dengan spasi untuk label -->
                    <span class="label"><?= str_replace('_', ' ', $kunci) ?></span>
                    <span>: 
                        <?php 
                        // Modifikasi 4: Penanganan khusus untuk tipe boolean
                        if (is_bool($nilai)) {
                            echo $nilai ? 'Aktif' : 'Cuti/Non-aktif';
                        } else {
                            echo htmlspecialchars((string)$nilai);
                        }
                        ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <?php 
        $status = statusKelulusan($mahasiswa['ipk']); 
        $statusClass = ($status === 'Data IPK Tidak Valid') ? 'predikat error' : 'predikat';
        ?>
        <div class="<?= $statusClass ?>">
            Predikat: <?= $status ?>
        </div>
    </div>
</body>

</html>
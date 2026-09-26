<?php
// kalkulator.php
$hasil = null;
$pesan = '';

// Modifikasi 1: Variabel untuk menyimpan nilai input (State Retention)
$val_a = '';
$val_b = '';
$val_operator = '+';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $a = (float) ($_POST['a'] ?? 0);
    $b = (float) ($_POST['b'] ?? 0);
    $operator = $_POST['operator'] ?? '+';

    // Menyimpan nilai yang diinput user untuk ditampilkan kembali
    $val_a = $_POST['a'];
    $val_b = $_POST['b'];
    $val_operator = $operator;

    switch ($operator) {
        case '+':
            $hasil = $a + $b;
            break;
        case '-':
            $hasil = $a - $b;
            break;
        case '*':
            $hasil = $a * $b;
            break;
        case '/':
            if ($b == 0) {
                $pesan = 'Pembagian dengan nol tidak diperbolehkan.';
            } else {
                $hasil = $a / $b;
            }
            break;
        // Modifikasi 2: Penambahan operasi Modulo (Sisa Bagi) dan Pangkat
        case '%':
            if ($b == 0) {
                $pesan = 'Modulo dengan nol tidak diperbolehkan.';
            } else {
                $hasil = fmod($a, $b);
            }
            break;
        case '^':
            $hasil = pow($a, $b);
            break;
        default: // Perbaikan sintaks: menggunakan titik dua (:)
            $pesan = 'Operator tidak valid.';
    }
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Kalkulator Sederhana</title>
    <!-- Modifikasi 3: Penambahan CSS agar tampilan lebih modern -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e9ecef;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .calculator-card {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        h1 {
            color: #333;
            margin-top: 0;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
            align-items: center;
        }
        input[type="number"] {
            width: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            font-size: 18px;
            font-weight: bold;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="calculator-card">
        <h1>Kalkulator Sederhana</h1>
        <form method="post">
            <div class="input-group">
                <!-- Modifikasi 4: Value dipertahankan setelah disubmit -->
                <input type="number" step="any" name="a" value="<?= htmlspecialchars((string)$val_a) ?>" placeholder="Angka 1" required>
                
                <select name="operator">
                    <option value="+" <?= $val_operator === '+' ? 'selected' : '' ?>>+</option>
                    <option value="-" <?= $val_operator === '-' ? 'selected' : '' ?>>-</option>
                    <option value="*" <?= $val_operator === '*' ? 'selected' : '' ?>>*</option>
                    <option value="/" <?= $val_operator === '/' ? 'selected' : '' ?>>/</option>
                    <option value="%" <?= $val_operator === '%' ? 'selected' : '' ?>>% (Mod)</option>
                    <option value="^" <?= $val_operator === '^' ? 'selected' : '' ?>>^ (Pangkat)</option>
                </select>
                
                <input type="number" step="any" name="b" value="<?= htmlspecialchars((string)$val_b) ?>" placeholder="Angka 2" required>
            </div>
            <button type="submit">Hitung</button> 
        </form>
        
        <?php if ($pesan): ?>
            <div class="result error"><?= htmlspecialchars($pesan) ?></div>
        <?php elseif ($hasil !== null): ?>
            <div class="result success">Hasil: <?= htmlspecialchars((string)$hasil) ?></div>
        <?php endif; ?>
    </div>
</body>

</html>
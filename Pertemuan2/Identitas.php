<?php
// Interface
interface Identitas
{
    public function ringkasan(): string;
}

// Class Parent
class Mahasiswa implements Identitas
{
    protected string $nim;
    protected string $nama;
    protected float $ipk;

    public function __construct(string $nim, string $nama, float $ipk)
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->setIpk($ipk);
    }

    public function setIpk(float $ipk): void
    {
        if ($ipk < 0 || $ipk > 4) {
            throw new InvalidArgumentException('IPK tidak valid: harus antara 0.00 dan 4.00.');
        }
        $this->ipk = $ipk;
    }

    public function getIpk(): float
    {
        return $this->ipk;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getNim(): string
    {
        return $this->nim;
    }
    
    public function getPredikat(): string
    {
        if ($this->ipk >= 3.50) return 'CumLaude';
        if ($this->ipk >= 3.00) return 'Sangat Memuaskan';
        if ($this->ipk >= 2.75) return 'Memuaskan';
        return 'Cukup';
    }

    public function ringkasan(): string
    {
        return $this->nim . ' - ' . $this->nama . ' - IPK: ' . $this->ipk . ' (' . $this->getPredikat() . ')';
    }
}

// Class Child
class MahasiswaUniversitas extends Mahasiswa
{
    private string $universitas;

    public function __construct(string $nim, string $nama, float $ipk, string $universitas)
    {
        parent::__construct($nim, $nama, $ipk);
        $this->universitas = $universitas;
    }

    public function getUniversitas(): string
    {
        return $this->universitas;
    }

    public function ringkasan(): string
    {
        return parent::ringkasan() . ' di ' . $this->universitas;
    }
}

// Simulasi Data Mahasiswa dari Database/Form
$dataMahasiswa = [
    ['nim' => '4521210088', 'nama' => 'Muhammad Yusuf', 'ipk' => 3.65, 'univ' => 'Universitas Pancasila'],
    ['nim' => '4521210102', 'nama' => 'Budi Santoso', 'ipk' => 4.05, 'univ' => 'Universitas Pancasila'], // Data error IPK
    ['nim' => '4521210045', 'nama' => 'Siti Aminah', 'ipk' => 3.25, 'univ' => 'Universitas Pancasila']
];

// Proses Instansiasi Data
$hasilProses = [];
foreach ($dataMahasiswa as $data) {
    try {
        $mhs = new MahasiswaUniversitas($data['nim'], $data['nama'], $data['ipk'], $data['univ']);
        $hasilProses[] = ['status' => 'sukses', 'data' => $mhs];
    } catch (InvalidArgumentException $e) {
        $hasilProses[] = ['status' => 'gagal', 'nama' => $data['nama'], 'pesan' => $e->getMessage()];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Data Mahasiswa</title>
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --success: #10b981;
            --danger: #ef4444;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-header h1 {
            color: var(--primary);
            font-size: 2.5rem;
            margin: 0 0 10px 0;
        }

        .page-header p {
            color: var(--text-muted);
            margin: 0;
            font-size: 1.1rem;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Styling Kartu Sukses */
        .card-sukses {
            border-top: 4px solid var(--primary);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge {
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge.cumlaude { background-color: #d1fae5; color: #065f46; }
        .badge.memuaskan { background-color: #e0e7ff; color: #3730a3; }
        .badge.cukup { background-color: #fef3c7; color: #92400e; }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-label {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .info-value {
            font-weight: 600;
            color: var(--text-main);
            text-align: right;
        }

        /* Styling Kartu Error */
        .card-error {
            border-top: 4px solid var(--danger);
            background-color: #fef2f2;
        }

        .card-error .card-title {
            color: var(--danger);
        }

        .error-message {
            color: #b91c1c;
            font-size: 0.95rem;
            background: #fee2e2;
            padding: 10px;
            border-radius: 6px;
            margin-top: 10px;
        }

        .icon {
            font-size: 1.2rem;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Sistem Data Mahasiswa</h1>
            <p>UNIVERSITAS PANCASILA</p>
        </div>

        <div class="grid">
            <?php foreach ($hasilProses as $item): ?>
                
                <?php if ($item['status'] === 'sukses'): ?>
                    <?php 
                        $mhs = $item['data']; 
                        $predikat = $mhs->getPredikat();
                        $badgeClass = ($predikat === 'CumLaude') ? 'cumlaude' : (($predikat === 'Sangat Memuaskan' || $predikat === 'Memuaskan') ? 'memuaskan' : 'cukup');
                    ?>
                    <div class="card card-sukses">
                        <h2 class="card-title">
                            <?= htmlspecialchars($mhs->getNama()) ?>
                            <span class="badge <?= $badgeClass ?>"><?= $predikat ?></span>
                        </h2>
                        
                        <div class="info-row">
                            <span class="info-label">NIM</span>
                            <span class="info-value"><?= htmlspecialchars($mhs->getNim()) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Universitas</span>
                            <span class="info-value"><?= htmlspecialchars($mhs->getUniversitas()) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nilai IPK</span>
                            <span class="info-value" style="color: var(--primary); font-size: 1.2rem;">
                                <?= number_format($mhs->getIpk(), 2) ?>
                            </span>
                        </div>
                    </div>
                
                <?php else: ?>
                    <div class="card card-error">
                        <h2 class="card-title">
                            <span><span class="icon">⚠️</span> Validasi Gagal</span>
                        </h2>
                        <div class="info-row">
                            <span class="info-label">Calon Mahasiswa</span>
                            <span class="info-value"><?= htmlspecialchars($item['nama']) ?></span>
                        </div>
                        <div class="error-message">
                            <strong>Pesan Sistem:</strong><br>
                            <?= htmlspecialchars($item['pesan']) ?>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
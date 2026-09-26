<?php
interface BisaDihitung
{
    public function hargaAkhir(): float;
}

class Produk implements BisaDihitung
{
    public function __construct(
        protected string $nama,
        protected float $harga,
        protected int $stok // Modifikasi 1: Penambahan field baru untuk Stok
    ) {
        // Modifikasi 2: Validasi agar harga dan stok tidak bernilai negatif
        if ($harga < 0) throw new InvalidArgumentException('Harga tidak boleh kurang dari 0.');
        if ($stok < 0) throw new InvalidArgumentException('Stok tidak boleh kurang dari 0.');
    }

    public function hargaAkhir(): float
    {
        return $this->harga;
    }

    public function getHargaAwal(): float
    {
        return $this->harga;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getStok(): int
    {
        return $this->stok;
    }

    // Modifikasi 3: Method baru untuk mengecek ketersediaan produk
    public function isTersedia(): bool
    {
        return $this->stok > 0;
    }
}

class ProdukDiskon extends Produk
{
    // Perbaikan: Menambahkan type hint 'float' pada parameter $harga
    public function __construct(string $nama, float $harga, int $stok, private float $diskon)
    {
        parent::__construct($nama, $harga, $stok);
        
        // Validasi agar diskon hanya berada di rentang 0 - 100 persen
        if ($diskon < 0 || $diskon > 100) {
            throw new InvalidArgumentException('Diskon harus berada di antara 0 hingga 100.');
        }
    }

    public function hargaAkhir(): float
    {
        return $this->harga * (1 - $this->diskon / 100);
    }

    public function getDiskon(): float
    {
        return $this->diskon;
    }
}

// Inisialisasi Data Produk
$daftarProduk = [
    new Produk('Logitech G316 X 75 Keyboard Gaming', 750000, 15),
    new ProdukDiskon('PRO X SUPERLIGHT 2 DEX SE LIGHTSPEED Wireless Gaming Mouse', 250000, 5, 20), // Harga 250rb, Stok 5, Diskon 20%
    new Produk('MSI G2422C Curved Gaming Monitor - 24 Inch', 1500000, 0),         // Stok habis (0)
    new ProdukDiskon('PRO X3 LIGHTSPEED Wireless 7.1 Gaming Headset', 450000, 12, 50) // Harga 450rb, Stok 12, Diskon 50%
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk</title>
    <!-- Modifikasi 4: Styling antarmuka bergaya Katalog E-Commerce -->
    <style>
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding: 30px;
            margin: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            position: relative;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }
        .badge-diskon {
            position: absolute;
            top: -10px;
            right: -10px;
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.85rem;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.4);
        }
        .nama-produk {
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0 0 10px 0;
        }
        .harga-coret {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        .harga-akhir {
            font-size: 1.4rem;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 15px;
        }
        .stok-status {
            margin-top: auto;
            padding: 8px;
            text-align: center;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .stok-ada {
            background-color: #d4edda;
            color: #155724;
        }
        .stok-habis {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Katalog Produk PiksSHOP</h1>
    </div>

    <div class="container">
        <?php foreach ($daftarProduk as $produk): ?>
            <div class="card">
                <!-- Cek apakah produk ini adalah instance dari ProdukDiskon -->
                <?php if ($produk instanceof ProdukDiskon): ?>
                    <div class="badge-diskon">Disk <?= $produk->getDiskon() ?>%</div>
                <?php endif; ?>

                <h2 class="nama-produk"><?= htmlspecialchars($produk->getNama()) ?></h2>
                
                <!-- Tampilkan harga asli yang dicoret jika ada diskon -->
                <?php if ($produk instanceof ProdukDiskon): ?>
                    <div class="harga-coret">Rp <?= number_format($produk->getHargaAwal(), 0, ',', '.') ?></div>
                <?php else: ?>
                    <!-- Spacer agar layout sejajar meskipun tidak ada diskon -->
                    <div class="harga-coret" style="visibility: hidden;">Rp 0</div> 
                <?php endif; ?>

                <div class="harga-akhir">
                    Rp <?= number_format($produk->hargaAkhir(), 0, ',', '.') ?>
                </div>

                <!-- Modifikasi Logika Penampilan Stok -->
                <?php if ($produk->isTersedia()): ?>
                    <div class="stok-status stok-ada">
                        Tersedia (Sisa: <?= $produk->getStok() ?>)
                    </div>
                <?php else: ?>
                    <div class="stok-status stok-habis">
                        Stok Habis
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
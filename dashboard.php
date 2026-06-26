<?php
// =========================================================================
// 1. KONEKSI DATABASE (OOP MURNI)
// =========================================================================
class Database {
    private string $host = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $database = "db_uas_pbo_trpl1a_annisanursafitri"; // Sesuai database wajib Anda
    protected ?mysqli $koneksi = null;

    public function __construct() {
        mysqli_report(MYSQLI_REPORT_OFF);
        $this->koneksi = new mysqli($this->host, $this->username, $this->password, $this->database);
        
        if ($this->koneksi->connect_error) {
            die("<div style='color:red; padding:20px;'><h3>Koneksi Gagal!</h3>" . $this->koneksi->connect_error . "</div>");
        }
    }

    public function getKoneksi(): mysqli {
        return $this->koneksi;
    }
}


// =========================================================================
// 2. ABSTRACT CLASS INDUK (ABSTRAKSI & ENKAPSULASI)
// =========================================================================
abstract class Mahasiswa {
    protected int $id_mahasiswa;
    protected string $nama_mahasiswa;
    protected string $nim;
    protected int $semester;
    protected float $tarif_ukt_nominal;

    public function __construct(int $id_mahasiswa, string $nama_mahasiswa, string $nim, int $semester, float $tarif_ukt_nominal) {
        $this->id_mahasiswa = $id_mahasiswa;
        $this->nama_mahasiswa = $nama_mahasiswa;
        $this->nim = $nim;
        $this->semester = $semester;
        $this->tarif_ukt_nominal = $tarif_ukt_nominal;
    }

    // Metode Abstrak Polimorfisme
    abstract public function hitungTagihanSemester(): float;
    abstract public function tampilkanSpesifikasiAkademik(): void;

    // Getter Enkapsulasi untuk akses data di komponen View
    public function getIdMahasiswa(): int { return $this->id_mahasiswa; }
    public function getNamaMahasiswa(): string { return $this->nama_mahasiswa; }
    public function getNim(): string { return $this->nim; }
    public function getSemester(): int { return $this->semester; }
    public function getTarifUktNominal(): float { return $this->tarif_ukt_nominal; }
}


// =========================================================================
// 3. SUBCLASSES (PEWARISAN, METHOD SPESIFIK & OVERRIDING)
// =========================================================================

// --- SUBCLASS MAHASISWA MANDIRI ---
class MahasiswaMandiri extends Mahasiswa {
    private ?string $golonganUKT;
    private ?string $namaWali;

    public function __construct(int $id_mahasiswa=0, string $nama_mahasiswa="", string $nim="", int $semester=0, float $tarif_ukt_nominal=0.0, ?string $golonganUKT=null, ?string $namaWali=null) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->golonganUKT = $golonganUKT;
        $this->namaWali = $namaWali;
    }

    // Overriding: Mandiri dikenakan tambahan biaya praktikum flat Rp 100.000
    public function hitungTagihanSemester(): float {
        return $this->tarif_ukt_nominal + 100000.00;
    }

    public function tampilkanSpesifikasiAkademik(): void {}
    
    // Getter khusus data spesifik anak
    public function getGolonganUKT(): ?string { return $this->golonganUKT; }
    public function getNamaWali(): ?string { return $this->namaWali; }

    // Method Spesifik Database SELECT-WHERE
    public function ambilDataDariDatabase(string $nim, mysqli $koneksi): bool {
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'mandiri' AND nim = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id_mahasiswa = (int)$row['id_mahasiswa'];
            $this->nama_mahasiswa = $row['nama_mahasiswa'];
            $this->nim = $row['nim'];
            $this->semester = (int)$row['semester'];
            $this->tarif_ukt_nominal = (float)$row['tarif_ukt_nominal'];
            $this->golonganUKT = $row['golongan_ukt'];
            $this->namaWali = $row['nama_wali'];
            return true;
        }
        return false;
    }
}

// --- SUBCLASS MAHASISWA BIDIKMISI ---
class MahasiswaBidikmisi extends Mahasiswa {
    private ?string $nomorKipKuliah;
    private ?float $danaSakuSubsidi;

    public function __construct(int $id_mahasiswa=0, string $nama_mahasiswa="", string $nim="", int $semester=0, float $tarif_ukt_nominal=0.0, ?string $nomorKipKuliah=null, ?float $danaSakuSubsidi=null) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->nomorKipKuliah = $nomorKipKuliah;
        $this->danaSakuSubsidi = $danaSakuSubsidi;
    }

    // Overriding: Bidikmisi dibebaskan biaya semesteran penuh (Rp 0)
    public function hitungTagihanSemester(): float { return 0.00; }
    public function tampilkanSpesifikasiAkademik(): void {}
    
    public function getNomorKipKuliah(): ?string { return $this->nomorKipKuliah; }
    public function getDanaSakuSubsidi(): ?float { return $this->danaSakuSubsidi; }

    public function ambilDataDariDatabase(string $nim, mysqli $koneksi): bool {
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'bidikmisi' AND nim = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id_mahasiswa = (int)$row['id_mahasiswa'];
            $this->nama_mahasiswa = $row['nama_mahasiswa'];
            $this->nim = $row['nim'];
            $this->semester = (int)$row['semester'];
            $this->tarif_ukt_nominal = (float)$row['tarif_ukt_nominal'];
            $this->nomorKipKuliah = $row['nomor_kip_kuliah'];
            $this->danaSakuSubsidi = (float)$row['dana_saku_subsidi'];
            return true;
        }
        return false;
    }
}

// --- SUBCLASS MAHASISWA PRESTASI ---
class MahasiswaPrestasi extends Mahasiswa {
    private ?string $namaInstansiBeasiswa;
    private ?float $minimalIpkSyarat;

    public function __construct(int $id_mahasiswa=0, string $nama_mahasiswa="", string $nim="", int $semester=0, float $tarif_ukt_nominal=0.0, ?string $namaInstansiBeasiswa=null, ?float $minimalIpkSyarat=null) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarif_ukt_nominal);
        $this->namaInstansiBeasiswa = $namaInstansiBeasiswa;
        $this->minimalIpkSyarat = $minimalIpkSyarat;
    }

    // Overriding: Prestasi mendapat potongan beasiswa 75% (Cukup bayar 25% dari UKT asli)
    public function hitungTagihanSemester(): float { return $this->tarif_ukt_nominal * 0.25; }
    public function tampilkanSpesifikasiAkademik(): void {}
    
    public function getNamaInstansiBeasiswa(): ?string { return $this->namaInstansiBeasiswa; }
    public function getMinimalIpkSyarat(): ?float { return $this->minimalIpkSyarat; }

    public function ambilDataDariDatabase(string $nim, mysqli $koneksi): bool {
        $query = "SELECT * FROM tabel_mahasiswa WHERE jenis_pembayaran = 'prestasi' AND nim = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id_mahasiswa = (int)$row['id_mahasiswa'];
            $this->nama_mahasiswa = $row['nama_mahasiswa'];
            $this->nim = $row['nim'];
            $this->semester = (int)$row['semester'];
            $this->tarif_ukt_nominal = (float)$row['tarif_ukt_nominal'];
            $this->namaInstansiBeasiswa = $row['nama_instansi_beasiswa'];
            $this->minimalIpkSyarat = (float)$row['minimal_ipk_syarat'];
            return true;
        }
        return false;
    }
}


// =========================================================================
// 4. DATA MAPPING CONTROLLER
// =========================================================================
$db = new Database();
$koneksi = $db->getKoneksi();

$daftarMandiri = [];
$daftarBidikmisi = [];
$daftarPrestasi = [];

$query = "SELECT * FROM tabel_mahasiswa";
$result = $koneksi->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        switch (strtolower($row['jenis_pembayaran'])) {
            case 'mandiri':
                $daftarMandiri[] = new MahasiswaMandiri((int)$row['id_mahasiswa'], $row['nama_mahasiswa'], $row['nim'], (int)$row['semester'], (float)$row['tarif_ukt_nominal'], $row['golongan_ukt'], $row['nama_wali']);
                break;
            case 'bidikmisi':
                $daftarBidikmisi[] = new MahasiswaBidikmisi((int)$row['id_mahasiswa'], $row['nama_mahasiswa'], $row['nim'], (int)$row['semester'], (float)$row['tarif_ukt_nominal'], $row['nomor_kip_kuliah'], (float)$row['dana_saku_subsidi']);
                break;
            case 'prestasi':
                $daftarPrestasi[] = new MahasiswaPrestasi((int)$row['id_mahasiswa'], $row['nama_mahasiswa'], $row['nim'], (int)$row['semester'], (float)$row['tarif_ukt_nominal'], $row['nama_instansi_beasiswa'], (float)$row['minimal_ipk_syarat']);
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS PBO - Sistem Registrasi Pembayaran Kuliah</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; margin: 40px; background-color: #f4f7f6; color: #333; }
        .wrapper { max-width: 1200px; margin: 0 auto; background: #fff; padding: 35px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h1 { text-align: center; color: #2c3e50; margin-bottom: 5px; }
        .author-tag { text-align: center; color: #7f8c8d; font-size: 14px; margin-bottom: 40px; border-bottom: 1px dashed #ccc; padding-bottom: 15px; }
        h2 { color: #2c3e50; border-left: 5px solid #2980b9; padding-left: 12px; margin-top: 35px; margin-bottom: 15px; font-size: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e0e0e0; font-size: 14px; }
        th { background-color: #2980b9; color: white; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        tr:hover { background-color: #fcfcfc; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; display: inline-block; }
        .badge-info { background-color: #e1f5fe; color: #0288d1; }
        .badge-success { background-color: #e8f5e9; color: #2e7d32; }
        .badge-warning { background-color: #fff3e0; color: #f57c00; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold-total { font-weight: bold; color: #111; }
    </style>
</head>
<body>

<div class="wrapper">
    <h1>Daftar Registrasi Pembayaran Kuliah Mahasiswa</h1>
    <p class="author-tag">Sistem Informasi Akademik Terintegrasi | Pembuat: <strong>Annisa Nur Safitri (TRPL 1A)</strong></p>

    <h2><span class="badge badge-info">MND</span> Kategori: Mahasiswa Mandiri</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="12%">NIM</th>
                <th>Nama Mahasiswa</th>
                <th width="8%" class="text-center">Semester</th>
                <th width="15%">Golongan UKT</th>
                <th>Nama Wali</th>
                <th class="text-right">Tarif Pokok</th>
                <th class="text-right">Total Tagihan (+100k)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarMandiri)): ?>
                <tr><td colspan="8" class="text-center">Tidak ada data mahasiswa kategori Mandiri.</td></tr>
            <?php else: ?>
                <?php foreach ($daftarMandiri as $mhs): ?>
                    <tr>
                        <td><?= $mhs->getIdMahasiswa(); ?></td>
                        <td><strong><?= $mhs->getNim(); ?></strong></td>
                        <td><?= htmlspecialchars($mhs->getNamaMahasiswa()); ?></td>
                        <td class="text-center"><?= $mhs->getSemester(); ?></td>
                        <td><span class="badge badge-info"><?= htmlspecialchars($mhs->getGolonganUKT()); ?></span></td>
                        <td><?= htmlspecialchars($mhs->getNamaWali() ?? '-'); ?></td>
                        <td class="text-right">Rp <?= number_format($mhs->getTarifUktNominal(), 0, ',', '.'); ?></td>
                        <td class="text-right bold-total">Rp <?= number_format($mhs->hitungTagihanSemester(), 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h2><span class="badge badge-success">BDK</span> Kategori: Mahasiswa Bidikmisi (KIP-Kuliah)</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="12%">NIM</th>
                <th>Nama Mahasiswa</th>
                <th width="8%" class="text-center">Semester</th>
                <th width="20%">No. KIP Kuliah</th>
                <th>Dana Saku Subs. / Bulan</th>
                <th class="text-right">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarBidikmisi)): ?>
                <tr><td colspan="7" class="text-center">Tidak ada data mahasiswa kategori Bidikmisi.</td></tr>
            <?php else: ?>
                <?php foreach ($daftarBidikmisi as $mhs): ?>
                    <tr>
                        <td><?= $mhs->getIdMahasiswa(); ?></td>
                        <td><strong><?= $mhs->getNim(); ?></strong></td>
                        <td><?= htmlspecialchars($mhs->getNamaMahasiswa()); ?></td>
                        <td class="text-center"><?= $mhs->getSemester(); ?></td>
                        <td><code><?= htmlspecialchars($mhs->getNomorKipKuliah() ?? '-'); ?></code></td>
                        <td>Rp <?= number_format($mhs->getDanaSakuSubsidi() ?? 0, 0, ',', '.'); ?></td>
                        <td class="text-right bold-total" style="color: #2e7d32;">Rp <?= number_format($mhs->hitungTagihanSemester(), 0, ',', '.'); ?> <small>(GRATIS)</small></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h2><span class="badge badge-warning">PRST</span> Kategori: Mahasiswa Prestasi</h2>
    <table>
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="12%">NIM</th>
                <th>Nama Mahasiswa</th>
                <th width="8%" class="text-center">Semester</th>
                <th>Instansi Beasiswa</th>
                <th width="12%" class="text-center">Syarat Min IPK</th>
                <th class="text-right">Tarif Pokok</th>
                <th class="text-right">Total Tagihan (Disc 75%)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($daftarPrestasi)): ?>
                <tr><td colspan="8" class="text-center">Tidak ada data mahasiswa kategori Prestasi.</td></tr>
            <?php else: ?>
                <?php foreach ($daftarPrestasi as $mhs): ?>
                    <tr>
                        <td><?= $mhs->getIdMahasiswa(); ?></td>
                        <td><strong><?= $mhs->getNim(); ?></strong></td>
                        <td><?= htmlspecialchars($mhs->getNamaMahasiswa()); ?></td>
                        <td class="text-center"><?= $mhs->getSemester(); ?></td>
                        <td><?= htmlspecialchars($mhs->getNamaInstansiBeasiswa() ?? '-'); ?></td>
                        <td class="text-center"><span class="badge badge-warning"><?= number_format($mhs->getMinimalIpkSyarat() ?? 0.0, 2); ?></span></td>
                        <td class="text-right">Rp <?= number_format($mhs->getTarifUktNominal(), 0, ',', '.'); ?></td>
                        <td class="text-right bold-total" style="color: #e67e22;">Rp <?= number_format($mhs->hitungTagihanSemester(), 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
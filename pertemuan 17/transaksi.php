<?php 
include 'koneksi.php';

// PROSES 1: JIKA TOMBOL SEWA DIKLIK
if (isset($_POST['sewa_mobil'])) {
    $id_mobil    = $_POST['id_mobil'];
    $id_customer = $_POST['id_customer'];
    $tgl_sewa    = $_POST['tgl_sewa'];
    $tgl_kembali = $_POST['tgl_kembali_rencana'];

    // Ambil harga per hari mobil
    $m_res = mysqli_query($koneksi, "SELECT harga_per_hari FROM mobil WHERE id_mobil='$id_mobil'");
    $m_data = mysqli_fetch_array($m_res);
    $harga_hari = $m_data['harga_per_hari'];

    // Hitung durasi hari (menggunakan objek tanggal PHP)
    $tgl1 = new DateTime($tgl_sewa);
    $tgl2 = new DateTime($tgl_kembali);
    $durasi = $tgl2->diff($tgl1)->days;
    if($durasi == 0) $durasi = 1; // Minimal hitung 1 hari

    $total_bayar = $durasi * $harga_hari;

    // Insert ke tabel transaksi
    $q_transaksi = "INSERT INTO transaksi (id_mobil, id_customer, tgl_sewa, tgl_kembali_rencana, total_bayar, status_transaksi) 
                    VALUES ('$id_mobil', '$id_customer', '$tgl_sewa', '$tgl_kembali', '$total_bayar', 'Sewa')";
    mysqli_query($koneksi, $q_transaksi);

    // Update status mobil menjadi 'Disewa'
    mysqli_query($koneksi, "UPDATE mobil SET status='Disewa' WHERE id_mobil='$id_mobil'");
    header("Location: transaksi.php");
}

// PROSES 2: JIKA TOMBOL PENGEMBALIAN DIKLIK
if (isset($_GET['kembali'])) {
    $id_trx = $_GET['kembali'];
    $tgl_sekarang = date('Y-m-d');

    // Ambil data detail transaksi & harga mobil
    $q_detail = mysqli_query($koneksi, "SELECT t.*, m.harga_per_hari, t.id_mobil FROM transaksi t JOIN mobil m ON t.id_mobil=m.id_mobil WHERE t.id_transaksi='$id_trx'");
    $d_trx = mysqli_fetch_array($q_detail);

    $rencana_kembali = new DateTime($d_trx['tgl_kembali_rencana']);
    $realisasi_kembali = new DateTime($tgl_sekarang);
    
    $denda = 0;
    // Cek jika terlambat mengembalikan
    if ($realisasi_kembali > $rencana_kembali) {
        $terlambat = $realisasi_kembali->diff($rencana_kembali)->days;
        $denda = $terlambat * 100000; // Denda Rp 100.000 per hari terlambat
    }

    $total_akhir = $d_trx['total_bayar'] + $denda;
    $id_mob = $d_trx['id_mobil'];

    // Update tabel transaksi menjadi Selesai beserta denda jika ada
    mysqli_query($koneksi, "UPDATE transaksi SET tgl_kembali_realisasi='$tgl_sekarang', denda='$denda', total_bayar='$total_akhir', status_transaksi='Selesai' WHERE id_transaksi='$id_trx'");
    
    // Kembalikan status mobil ke 'Tersedia'
    mysqli_query($koneksi, "UPDATE mobil SET status='Tersedia' WHERE id_mobil='$id_mob'");
    header("Location: transaksi.php");
}

include 'header.php';
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Form Penyewaan Baru</div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label>Pilih Mobil (Hanya yang Tersedia)</label>
                        <select name="id_mobil" class="form-select" required>
                            <?php 
                            $m_list = mysqli_query($koneksi, "SELECT * FROM mobil WHERE status='Tersedia'");
                            while($m = mysqli_fetch_array($m_list)){
                                echo "<option value='{$m['id_mobil']}'>{$m['merk']} {$m['model']} ({$m['plat_nomor']})</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Pilih Customer</label>
                        <select name="id_customer" class="form-select" required>
                            <?php 
                            $c_list = mysqli_query($koneksi, "SELECT * FROM customer");
                            while($c = mysqli_fetch_array($c_list)){
                                echo "<option value='{$c['id_customer']}'>{$c['nama']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Sewa</label>
                        <input type="date" name="tgl_sewa" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Rencana Kembali</label>
                        <input type="date" name="tgl_kembali_rencana" class="form-control" required>
                    </div>
                    <button type="submit" name="sewa_mobil" class="btn btn-dark w-100">Buka Transaksi Sewa</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Transaksi Berjalan / Belum Kembali</div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Nota</th>
                            <th>Pelanggan</th>
                            <th>Mobil</th>
                            <th>Tgl Sewa</th>
                            <th>Estimasi Bayar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $q_list = "SELECT t.*, c.nama, m.merk, m.model FROM transaksi t 
                                   JOIN customer c ON t.id_customer=c.id_customer 
                                   JOIN mobil m ON t.id_mobil=m.id_mobil 
                                   WHERE t.status_transaksi='Sewa'";
                        $trx_data = mysqli_query($koneksi, $q_list);
                        if(mysqli_num_rows($trx_data) == 0){
                            echo "<tr><td colspan='6' class='text-center text-muted'>Tidak ada transaksi penyewaan aktif.</td></tr>";
                        }
                        while($row = mysqli_fetch_array($trx_data)){
                            echo "<tr>
                                    <td>TRX-00{$row['id_transaksi']}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['merk']} {$row['model']}</td>
                                    <td>{$row['tgl_sewa']}</td>
                                    <td>Rp ".number_format($row['total_bayar'])."</td>
                                    <td>
                                        <a href='transaksi.php?kembali={$row['id_transaksi']}' class='btn btn-sm btn-success' onclick='return confirm(\"Proses pengembalian mobil ini?\")'>Kembalikan</a>
                                    </td>
                                  </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<?php 
include 'koneksi.php';

// Proses simpan data ke database jika tombol Tambah diklik
if (isset($_POST['simpan'])) {
    $plat  = $_POST['plat_nomor'];
    $merk  = $_POST['merk'];
    $model = $_POST['model'];
    $harga = $_POST['harga_per_hari'];

    $query = "INSERT INTO mobil (plat_nomor, merk, model, harga_per_hari) VALUES ('$plat', '$merk', '$model', '$harga')";
    mysqli_query($koneksi, $query);
    header("Location: mobil.php");
}

include 'header.php';
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Tambah Mobil Baru</div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label>Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control" placeholder="Contoh: B 1234 ABC" required>
                    </div>
                    <div class="mb-3">
                        <label>Merk</label>
                        <input type="text" name="merk" class="form-control" placeholder="Contoh: Toyota" required>
                    </div>
                    <div class="mb-3">
                        <label>Model/Tipe</label>
                        <input type="text" name="model" class="form-control" placeholder="Contoh: Avanza" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga Sewa / Hari (Rp)</label>
                        <input type="number" name="harga_per_hari" class="form-control" required>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-dark w-100">Simpan Data Mobil</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Daftar Armada Mobil</div>
            <div class="card-body">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Plat Nomor</th>
                            <th>Kendaraan</th>
                            <th>Harga/Hari</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $data = mysqli_query($koneksi, "SELECT * FROM mobil");
                        while($row = mysqli_fetch_array($data)){
                            $badge = ($row['status'] == 'Tersedia') ? 'bg-success' : 'bg-danger';
                            echo "<tr>
                                    <td>$no</td>
                                    <td>{$row['plat_nomor']}</td>
                                    <td>{$row['merk']} {$row['model']}</td>
                                    <td>Rp ".number_format($row['harga_per_hari'])."</td>
                                    <td><span class='badge $badge'>{$row['status']}</span></td>
                                  </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
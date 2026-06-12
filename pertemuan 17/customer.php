<?php 
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nik    = $_POST['nik'];
    $nama   = $_POST['nama'];
    $telp   = $_POST['no_telp'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO customer (nik, nama, no_telp, alamat) VALUES ('$nik', '$nama', '$telp', '$alamat')";
    mysqli_query($koneksi, $query);
    header("Location: customer.php");
}

include 'header.php';
?>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Tambah Customer</div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label>NIK (No KTP)</label>
                        <input type="text" name="nik" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>No. Telp</label>
                        <input type="text" name="no_telp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="simpan" class="btn btn-dark w-100">Simpan Customer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Daftar Customer</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>No. Telp</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $data = mysqli_query($koneksi, "SELECT * FROM customer");
                        while($row = mysqli_fetch_array($data)){
                            echo "<tr>
                                    <td>$no</td>
                                    <td>{$row['nik']}</td>
                                    <td>{$row['nama']}</td>
                                    <td>{$row['no_telp']}</td>
                                    <td>{$row['alamat']}</td>
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
<?php 
include 'koneksi.php';
include 'header.php';

$t_mobil = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM mobil"));
$t_ready = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM mobil WHERE status='Tersedia'"));
$t_sewa  = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM mobil WHERE status='Disewa'"));
?>

<div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
    <div class="container-fluid py-2">
        <h1 class="display-5 fw-bold">Selamat Datang di Sistem Rental Mobil</h1>
        <p class="col-md-8 fs-4 text-muted">Aplikasi manajemen penyewaan mobil, data pelanggan, transaksi, hingga pelaporan otomatis.</p>
    </div>
</div>

<div class="row text-center">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-3 shadow-sm">
            <div class="card-body">
                <h3>Total Mobil</h3>
                <h2 class="display-4 fw-bold"><?= $t_mobil; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white mb-3 shadow-sm">
            <div class="card-body">
                <h3>Mobil Tersedia</h3>
                <h2 class="display-4 fw-bold"><?= $t_ready; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white mb-3 shadow-sm">
            <div class="card-body">
                <h3>Mobil Sedang Disewa</h3>
                <h2 class="display-4 fw-bold"><?= $t_sewa; ?></h2>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
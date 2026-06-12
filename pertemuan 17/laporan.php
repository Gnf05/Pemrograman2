<?php 
include 'koneksi.php';
include 'header.php';
?>

<div class="card shadow-sm mb-4 no-print">
    <div class="card-body bg-white">
        <h5 class="card-title mb-3">Filter & Cetak</h5>
        <button type="button" class="btn btn-primary" onclick="window.print()">🖨️ Cetak Laporan Keuangan</button>
    </div>
</div>

<div class="p-4 bg-white rounded shadow-sm">
    <div class="text-center mb-4">
        <h2 class="fw-bold uppercase">LAPORAN REALISASI RENTAL MOBIL</h2>
        <p class="text-muted mb-0">Sistem Informasi Manajemen Pemrograman 2 - RentCar</p>
        <hr class="border border-dark border-2 opacity-75">
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-secondary">
            <tr>
                <th>No</th>
                <th>Nota ID</th>
                <th>Customer</th>
                <th>Mobil</th>
                <th>Tgl Sewa</th>
                <th>Real-Kembali</th>
                <th>Denda</th>
                <th>Total Penerimaan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $grand_total = 0;
            $grand_denda = 0;
            
            $query_laporan = "SELECT t.*, c.nama, m.merk, m.model, m.plat_nomor FROM transaksi t 
                              JOIN customer c ON t.id_customer=c.id_customer 
                              JOIN mobil m ON t.id_mobil=m.id_mobil";
            $res = mysqli_query($koneksi, $query_laporan);
            
            while($row = mysqli_fetch_array($res)){
                $grand_total += $row['total_bayar'];
                $grand_denda += $row['denda'];
                $tgl_kembali = ($row['tgl_kembali_realisasi'] != NULL) ? $row['tgl_kembali_realisasi'] : '-';
                $status_b = ($row['status_transaksi'] == 'Selesai') ? 'success' : 'warning';
                
                echo "<tr>
                        <td>$no</td>
                        <td>TRX-00{$row['id_transaksi']}</td>
                        <td>{$row['nama']}</td>
                        <td>{$row['merk']} ({$row['plat_nomor']})</td>
                        <td>{$row['tgl_sewa']}</td>
                        <td>$tgl_kembali</td>
                        <td>Rp ".number_format($row['denda'])."</td>
                        <td>Rp ".number_format($row['total_bayar'])."</td>
                        <td><span class='badge bg-$status_b'>{$row['status_transaksi']}</span></td>
                      </tr>";
                $no++;
            }
            ?>
            <tr class="table-light fw-bold">
                <td colspan="6" class="text-end">TOTAL KESELURUHAN REKAPITULASI :</td>
                <td>Rp <?= number_format($grand_denda); ?></td>
                <td>Rp <?= number_format($grand_total); ?></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
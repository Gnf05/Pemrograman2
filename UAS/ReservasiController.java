package com.example.demo;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import java.time.LocalDate;
import java.time.temporal.ChronoUnit;
import java.util.List;

@Controller
public class ReservasiController {

    @Autowired
    private ReservasiRepository repository;

    @GetMapping("/")
    public String dashboard(Model model) {
        model.addAttribute("reservasi", new Reservasi());
        model.addAttribute("daftarReservasi", repository.findAll());
        model.addAttribute("totalKamar", repository.count());
        model.addAttribute("activePage", "dashboard");
        return "index";
    }

    @GetMapping("/status-log")
    public String statusLog(Model model) {
        model.addAttribute("daftarReservasi", repository.findAll());
        model.addAttribute("totalKamar", repository.count());
        model.addAttribute("activePage", "logKamar");
        return "index";
    }

    @GetMapping("/sql-management")
    public String sqlManagement(Model model) {
        model.addAttribute("totalKamar", repository.count());
        model.addAttribute("activePage", "sqlManage");
        return "index";
    }

    @PostMapping("/pesan")
    public String simpanReservasi(@ModelAttribute Reservasi reservasi, 
                                  @RequestParam(value = "chkBreakfast", required = false) String chkBreakfast,
                                  @RequestParam(value = "chkExtraBed", required = false) String chkExtraBed,
                                  Model model) {
        try {
            if (reservasi.getNamaTamu() == null || reservasi.getNamaTamu().trim().isEmpty()) {
                throw new Exception("Validation Failure: Identitas nama tamu wajib diisi!");
            }

            // 1. Parsing Tanggal & Validasi Ekstrem Exception Handling
            LocalDate hariIni = LocalDate.now();
            LocalDate checkIn = LocalDate.parse(reservasi.getTanggalCheckIn());
            LocalDate checkOut = LocalDate.parse(reservasi.getTanggalCheckOut());

            if (checkIn.isBefore(hariIni)) {
                throw new Exception("Sistem Menolak: Tanggal Check-In tidak boleh hari yang sudah terlewat!");
            }
            if (checkOut.isBefore(checkIn)) {
                throw new Exception("Sistem Menolak: Urutan waktu terbalik! Tanggal Check-Out tidak boleh mendahului Check-In.");
            }
            if (checkIn.isEqual(checkOut)) {
                throw new Exception("Aturan Transaksi: Durasi tinggal minimal di GNF Hotel adalah 1 malam!");
            }

            // 2. Hitung Durasi Malam Murni menggunakan ChronoUnit Java 8 API
            long selisihMalam = ChronoUnit.DAYS.between(checkIn, checkOut);
            reservasi.setTotalMalam((int) selisihMalam);

            // 3. Hitung Biaya Dasar Berdasarkan Atribut Tipe Kamar
            long hargaPerMalam = 350000;
            if (reservasi.getTipeKamar().equalsIgnoreCase("Deluxe")) hargaPerMalam = 650000;
            if (reservasi.getTipeKamar().equalsIgnoreCase("Suite")) hargaPerMalam = 1250000;

            // 4. Hitung Add-On Fasilitas Pilihan dari Checkbox Frontend
            long biayaTambahanPerMalam = 0;
            StringBuilder logFasilitas = new StringBuilder("Regular");
            
            if (chkBreakfast != null) {
                biayaTambahanPerMalam += 75000;
                logFasilitas.setLength(0);
                logFasilitas.append("Breakfast");
            }
            if (chkExtraBed != null) {
                biayaTambahanPerMalam += 150000;
                if (logFasilitas.toString().equals("Breakfast")) {
                    logFasilitas.append(" + Extra Bed");
                } else {
                    logFasilitas.setLength(0);
                    logFasilitas.append("Extra Bed");
                }
            }
            reservasi.setFasilitasTambahan(logFasilitas.toString());

            // 5. Kalkulasi Harga Sub-Total Komparatif
            long subTotalBiaya = (hargaPerMalam + biayaTambahanPerMalam) * selisihMalam;

            // 6. Logika Aturan Diskon Korporat (> 5 Malam Diskon 10%)
            if (selisihMalam > 5) {
                long nilaiDiskon = (long) (subTotalBiaya * 0.10);
                subTotalBiaya = subTotalBiaya - nilaiDiskon;
            }
            reservasi.setTotalBiaya(subTotalBiaya);

            // 7. Limitasi Kuota Kamar Suite (Sesuai Soal)
            long totalSuite = repository.findAll().stream()
                .filter(r -> r.getTipeKamar() != null && r.getTipeKamar().equalsIgnoreCase("Suite"))
                .count();
            if (reservasi.getTipeKamar().equalsIgnoreCase("Suite") && totalSuite >= 5) {
                throw new Exception("Resource Out: Kuota kamar Suite sudah penuh (Maksimal 5 kamar)!");
            }

            repository.save(reservasi);
            return "redirect:/";

        } catch (Exception e) {
            model.addAttribute("errorMessage", e.getMessage());
            model.addAttribute("reservasi", reservasi);
            model.addAttribute("daftarReservasi", repository.findAll());
            model.addAttribute("totalKamar", repository.count());
            model.addAttribute("activePage", "dashboard");
            return "index";
        }
    }

    @GetMapping("/hapus/{id}")
    public String hapusReservasi(@PathVariable("id") Long id) {
        repository.deleteById(id);
        return "redirect:/";
    }

    @GetMapping("/reset-database")
    public String resetDatabase() {
        repository.deleteAll();
        return "redirect:/";
    }
}
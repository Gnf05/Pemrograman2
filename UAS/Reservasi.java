package com.example.demo;

import jakarta.persistence.*;

@Entity
@Table(name = "tabel_reservasi")
public class Reservasi {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(name = "nama_tamu", nullable = false)
    private String namaTamu;

    @Column(name = "tipe_kamar", nullable = false)
    private String tipeKamar;

    @Column(name = "tanggal_checkin", nullable = false)
    private String tanggalCheckIn; 

    @Column(name = "tanggal_checkout", nullable = false)
    private String tanggalCheckOut;

    // --- ATRIBUT BARU LOGIKA BISNIS INDUSTRIAL ---
    @Column(name = "total_malam")
    private int totalMalam;

    @Column(name = "total_biaya")
    private long totalBiaya;

    @Column(name = "fasilitas_tambahan")
    private String fasilitasTambahan;

    public Reservasi() {}

    public Reservasi(String namaTamu, String tipeKamar, String tanggalCheckIn, String tanggalCheckOut, int totalMalam, long totalBiaya, String fasilitasTambahan) {
        this.namaTamu = namaTamu;
        this.tipeKamar = tipeKamar;
        this.tanggalCheckIn = tanggalCheckIn;
        this.tanggalCheckOut = tanggalCheckOut;
        this.totalMalam = totalMalam;
        this.totalBiaya = totalBiaya;
        this.fasilitasTambahan = fasilitasTambahan;
    }

    // --- GETTER & SETTER MANUAL (ANTI CRASH HIBERNATE) ---
    public Long getId() { return id; }
    public void setId(Long id) { this.id = id; }

    public String getNamaTamu() { return namaTamu; }
    public void setNamaTamu(String namaTamu) { this.namaTamu = namaTamu; }

    public String getTipeKamar() { return tipeKamar; }
    public void setTipeKamar(String tipeKamar) { this.tipeKamar = tipeKamar; }

    public String getTanggalCheckIn() { return tanggalCheckIn; }
    public void setTanggalCheckIn(String tanggalCheckIn) { this.tanggalCheckIn = tanggalCheckIn; }

    public String getTanggalCheckOut() { return tanggalCheckOut; }
    public void setTanggalCheckOut(String tanggalCheckOut) { this.tanggalCheckOut = tanggalCheckOut; }

    public int getTotalMalam() { return totalMalam; }
    public void setTotalMalam(int totalMalam) { this.totalMalam = totalMalam; }

    public long getTotalBiaya() { return totalBiaya; }
    public void setTotalBiaya(long totalBiaya) { this.totalBiaya = totalBiaya; }

    public String getFasilitasTambahan() { return fasilitasTambahan; }
    public void setFasilitasTambahan(String fasilitasTambahan) { this.fasilitasTambahan = fasilitasTambahan; }
}
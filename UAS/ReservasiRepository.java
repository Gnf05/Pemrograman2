package com.example.demo;

import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface ReservasiRepository extends JpaRepository<Reservasi, Long> {
    // Digunakan untuk koneksi database MySQL secara otomatis [cite: 15]
}
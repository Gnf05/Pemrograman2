import javax.swing.*;

public class biodatagui {

    public static void main(String[] args) {

        // Membuat window
        JFrame frame = new JFrame("Biodata Mahasiswa");

        // Membuat label nama
        JLabel namaLabel = new JLabel("Nama : Galih Naufal Faturroham");

        // Membuat label NIM
        JLabel nimLabel = new JLabel("NIM : 231011402731");

        // Mengatur posisi teks
        namaLabel.setBounds(120,50,200,30);
        nimLabel.setBounds(120,90,200,30);

        // Menambahkan komponen ke frame
        frame.add(namaLabel);
        frame.add(nimLabel);

        // Pengaturan window
        frame.setSize(400,200);
        frame.setLayout(null);
        frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        frame.setVisible(true);
    }
}

-- =========================
-- DATABASE
-- =========================

DROP DATABASE IF EXISTS pbd_prak_2025;
CREATE DATABASE pbd_prak_2025;
USE pbd_prak_2025;

-- =========================
-- MASTER TABLE
-- =========================

CREATE TABLE role (
    idrole INT AUTO_INCREMENT PRIMARY KEY,
    nama_role VARCHAR(100)
);

CREATE TABLE satuan (
    idsatuan INT AUTO_INCREMENT PRIMARY KEY,
    nama_satuan VARCHAR(45),
    status TINYINT
);

CREATE TABLE vendor (
    idvendor INT AUTO_INCREMENT PRIMARY KEY,
    nama_vendor VARCHAR(100),
    badan_hukum CHAR(1),
    status CHAR(1)
);

CREATE TABLE user (
    iduser INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(45),
    password VARCHAR(100),
    idrole INT NOT NULL,
    FOREIGN KEY (idrole) REFERENCES role(idrole)
);

CREATE TABLE barang (
    idbarang INT AUTO_INCREMENT PRIMARY KEY,
    jenis CHAR(1),
    nama VARCHAR(45),
    idsatuan INT NOT NULL,
    status TINYINT,
    harga INT,
    FOREIGN KEY (idsatuan) REFERENCES satuan(idsatuan)
);

-- =========================
-- TRANSACTION TABLE
-- =========================

CREATE TABLE margin_penjualan (
    idmargin_penjualan INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    persen DOUBLE,
    status TINYINT,
    iduser INT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (iduser) REFERENCES user(iduser)
);

CREATE TABLE pengadaan (
    idpengadaan INT AUTO_INCREMENT PRIMARY KEY,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_iduser INT NOT NULL,
    status CHAR(1),
    vendor_idvendor INT NOT NULL,
    subtotal_nilai INT,
    ppn INT,
    total_nilai INT,
    FOREIGN KEY (user_iduser) REFERENCES user(iduser),
    FOREIGN KEY (vendor_idvendor) REFERENCES vendor(idvendor)
);

CREATE TABLE penerimaan (
    idpenerimaan INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status CHAR(1),
    idpengadaan INT NOT NULL,
    iduser INT NOT NULL,
    FOREIGN KEY (idpengadaan) REFERENCES pengadaan(idpengadaan),
    FOREIGN KEY (iduser) REFERENCES user(iduser)
);

CREATE TABLE penjualan (
    idpenjualan INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    subtotal_nilai INT,
    ppn INT,
    total_nilai INT,
    iduser INT NOT NULL,
    idmargin_penjualan INT NOT NULL,
    FOREIGN KEY (iduser) REFERENCES user(iduser),
    FOREIGN KEY (idmargin_penjualan)
        REFERENCES margin_penjualan(idmargin_penjualan)
);

CREATE TABLE `retur` (
    idretur INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idpenerimaan INT NOT NULL,
    iduser INT NOT NULL,
    FOREIGN KEY (idpenerimaan) REFERENCES penerimaan(idpenerimaan),
    FOREIGN KEY (iduser) REFERENCES user(iduser)
);

-- =========================
-- DETAIL TABLE
-- =========================

CREATE TABLE detail_pengadaan (
    iddetail_pengadaan INT AUTO_INCREMENT PRIMARY KEY,
    harga_satuan INT,
    jumlah INT,
    sub_total INT,
    idbarang INT NOT NULL,
    idpengadaan INT NOT NULL,
    FOREIGN KEY (idbarang) REFERENCES barang(idbarang),
    FOREIGN KEY (idpengadaan) REFERENCES pengadaan(idpengadaan)
);

CREATE TABLE detail_penerimaan (
    iddetail_penerimaan INT AUTO_INCREMENT PRIMARY KEY,
    idpenerimaan INT NOT NULL,
    barang_idbarang INT NOT NULL,
    jumlah_terima INT,
    harga_satuan_terima INT,
    sub_total_terima INT,
    FOREIGN KEY (idpenerimaan) REFERENCES penerimaan(idpenerimaan),
    FOREIGN KEY (barang_idbarang) REFERENCES barang(idbarang)
);

CREATE TABLE detail_penjualan (
    iddetail_penjualan INT AUTO_INCREMENT PRIMARY KEY,
    harga_satuan INT,
    jumlah INT,
    sub_total INT,
    penjualan_idpenjualan INT NOT NULL,
    idbarang INT NOT NULL,
    FOREIGN KEY (penjualan_idpenjualan)
        REFERENCES penjualan(idpenjualan),
    FOREIGN KEY (idbarang) REFERENCES barang(idbarang)
);

CREATE TABLE detail_retur (
    iddetail_retur INT AUTO_INCREMENT PRIMARY KEY,
    jumlah INT,
    alasan VARCHAR(200),
    idretur INT NOT NULL,
    iddetail_penerimaan INT NOT NULL,
    FOREIGN KEY (idretur) REFERENCES `retur`(idretur),
    FOREIGN KEY (iddetail_penerimaan)
        REFERENCES detail_penerimaan(iddetail_penerimaan)
);

CREATE TABLE kartu_stok (
    idkartu_stok INT AUTO_INCREMENT PRIMARY KEY,
    jenis_transaksi CHAR(1),
    masuk INT,
    keluar INT,
    stock INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idtransaksi INT,
    idbarang INT NOT NULL,
    FOREIGN KEY (idbarang) REFERENCES barang(idbarang)
);

-- =========================
-- DATA TABLE
-- =========================

INSERT INTO role (nama_role) VALUES
('Super Admin'),
('Admin');

INSERT INTO user (username, password, idrole) VALUES
('superadmin', '123456', 1),
('admin', '123456', 2);

INSERT INTO vendor (nama_vendor, badan_hukum, status) VALUES
('PT. Jaringan Nusantara', 'P', 'A'),
('CV. Jaya Abadi', 'C', 'A'),
('UD. Media Komputer', 'U', 'A'),
('PT. Sumber Rejeki', 'P', 'T'),
('CV. Sentosa', 'C', 'T');

INSERT INTO satuan (nama_satuan, status) VALUES
('Unit', 1),
('Box', 1),
('Lisensi', 1),
('Roll', 1),
('Pcs', 0),
('Lembar', 0);

INSERT INTO barang (jenis, nama, idsatuan, status, harga) VALUES
('C', 'SSD 512GB', 1, 1, 750000),
('C', 'Hardisk 1TB', 1, 0, 500000),
('L', 'Microsoft Office 2021', 3, 1, 1500000),
('D', 'Printer Canon G3010', 1, 1, 2500000),
('D', 'PC AIO Lenovo ThinkCentre', 1, 1, 12500000),
('A', 'Kabel UTP Belden Cat6 305m', 4, 0, 1500000),
('L', 'Lisensi Windows 11 Pro OEM', 3, 1, 2500000),
('A', 'Tinta Printer Epson 003', 1, 1, 85000);

USE pbdprak;

-- =========================
-- VIEW MASTER
-- =========================

CREATE OR REPLACE VIEW view_user AS
SELECT
    u.iduser,
    u.username,
    r.nama_role
FROM user u
JOIN role r ON u.idrole = r.idrole
ORDER BY u.iduser ASC;

CREATE OR REPLACE VIEW view_role AS
SELECT
    idrole,
    nama_role
FROM role
ORDER BY idrole ASC;

CREATE OR REPLACE VIEW view_vendor_all AS
SELECT
    idvendor,
    nama_vendor,
    badan_hukum,
    CASE
        WHEN badan_hukum = 'P' THEN 'Perseroan Terbatas (PT)'
        WHEN badan_hukum = 'C' THEN 'Persekutuan Komanditer (CV)'
        WHEN badan_hukum = 'U' THEN 'Usaha Dagang (UD)'
        WHEN badan_hukum = 'F' THEN 'Firma'
        ELSE 'Lainnya'
    END AS badan_hukum_text,
    status,
    CASE
        WHEN status = 'A' THEN 'Aktif'
        ELSE 'Non-Aktif'
    END AS status_text
FROM vendor
ORDER BY status ASC, nama_vendor ASC;

CREATE OR REPLACE VIEW view_vendor_default AS
SELECT *
FROM view_vendor_all
WHERE status = 'A';

CREATE OR REPLACE VIEW view_satuan_all AS
SELECT
    idsatuan,
    nama_satuan,
    status,
    CASE
        WHEN status = 1 THEN 'Aktif'
        ELSE 'Non-Aktif'
    END AS status_text
FROM satuan
ORDER BY status DESC, nama_satuan ASC;

CREATE OR REPLACE VIEW view_satuan_default AS
SELECT *
FROM view_satuan_all
WHERE status = 1;

-- =========================
-- VIEW BARANG
-- =========================

CREATE OR REPLACE VIEW view_barang_all AS
SELECT
    b.idbarang,
    b.jenis,
    CASE
        WHEN b.jenis = 'D' THEN 'Device'
        WHEN b.jenis = 'C' THEN 'Component'
        WHEN b.jenis = 'L' THEN 'License'
        WHEN b.jenis = 'A' THEN 'Accessory'
        ELSE 'Lainnya'
    END AS jenis_text,
    b.nama AS nama_barang,
    s.nama_satuan,
    s.idsatuan,
    b.harga,
    CONCAT('Rp ', FORMAT(b.harga, 0)) AS harga_format,
    b.status,
    CASE
        WHEN b.status = 1 THEN 'Aktif'
        ELSE 'Non-Aktif'
    END AS status_text
FROM barang b
JOIN satuan s ON b.idsatuan = s.idsatuan
ORDER BY b.status DESC, b.nama ASC;

CREATE OR REPLACE VIEW view_barang_default AS
SELECT *
FROM view_barang_all
WHERE status = 1;

-- =========================
-- VIEW MARGIN PENJUALAN
-- =========================

CREATE OR REPLACE VIEW view_margin_penjualan_all AS
SELECT
    mp.idmargin_penjualan,
    mp.persen,
    CONCAT(ROUND(mp.persen * 100, 2), '%') AS persen_display,
    mp.created_at,
    mp.updated_at,
    u.username AS created_by,
    mp.status,
    CASE
        WHEN mp.status = 1 THEN 'Aktif'
        ELSE 'Non-Aktif'
    END AS status_text
FROM margin_penjualan mp
JOIN user u ON mp.iduser = u.iduser
ORDER BY mp.status DESC, mp.persen ASC;

CREATE OR REPLACE VIEW view_margin_penjualan_default AS
SELECT *
FROM view_margin_penjualan_all
WHERE status = 1;

-- =========================
-- VIEW TRANSAKSI
-- =========================

CREATE OR REPLACE VIEW view_pengadaan AS
SELECT
    p.idpengadaan,
    p.timestamp,
    p.status,
    CASE
        WHEN p.status = 'I' THEN 'In Process'
        WHEN p.status = 'P' THEN 'Pending'
        WHEN p.status = 'S' THEN 'Selesai'
        WHEN p.status = 'C' THEN 'Canceled'
        ELSE 'Lainnya'
    END AS status_text,
    p.subtotal_nilai,
    p.ppn,
    p.total_nilai,
    u.username AS dibuat_oleh,
    v.nama_vendor,
    p.user_iduser,
    p.vendor_idvendor
FROM pengadaan p
JOIN user u ON p.user_iduser = u.iduser
JOIN vendor v ON p.vendor_idvendor = v.idvendor
ORDER BY p.idpengadaan DESC;

CREATE OR REPLACE VIEW view_penerimaan AS
SELECT
    pn.idpenerimaan,
    pn.created_at,
    pn.status,
    CASE
        WHEN pn.status = 'I' THEN 'In Process'
        WHEN pn.status = 'P' THEN 'Pending'
        WHEN pn.status = 'S' THEN 'Selesai'
        WHEN pn.status = 'C' THEN 'Canceled'
        ELSE 'Lainnya'
    END AS status_text,
    pn.idpengadaan,
    u.username AS diterima_oleh,
    pn.iduser
FROM penerimaan pn
JOIN user u ON pn.iduser = u.iduser
ORDER BY pn.idpenerimaan DESC;

CREATE OR REPLACE VIEW view_penjualan AS
SELECT
    pj.idpenjualan,
    pj.created_at,
    pj.subtotal_nilai,
    pj.ppn,
    pj.total_nilai,
    u.username AS dilakukan_oleh,
    m.persen AS margin_persen,
    pj.iduser,
    pj.idmargin_penjualan
FROM penjualan pj
JOIN user u ON pj.iduser = u.iduser
JOIN margin_penjualan m ON pj.idmargin_penjualan = m.idmargin_penjualan
ORDER BY pj.idpenjualan DESC;

CREATE OR REPLACE VIEW view_retur AS
SELECT
    r.idretur,
    r.created_at,
    r.idpenerimaan,
    u.username AS diproses_oleh,
    r.iduser
FROM `retur` r
JOIN user u ON r.iduser = u.iduser
ORDER BY r.idretur DESC;

-- =========================
-- VIEW DETAIL
-- =========================

CREATE OR REPLACE VIEW view_detail_pengadaan AS
SELECT
    dp.iddetail_pengadaan,
    dp.idpengadaan,
    b.nama AS nama_barang,
    s.nama_satuan,
    dp.harga_satuan,
    dp.jumlah,
    dp.sub_total
FROM detail_pengadaan dp
JOIN barang b ON dp.idbarang = b.idbarang
JOIN satuan s ON b.idsatuan = s.idsatuan
ORDER BY dp.idpengadaan DESC, dp.iddetail_pengadaan ASC;

CREATE OR REPLACE VIEW view_detail_penerimaan AS
SELECT
    dp.iddetail_penerimaan,
    dp.idpenerimaan,
    b.nama AS nama_barang,
    s.nama_satuan,
    dp.jumlah_terima,
    dp.harga_satuan_terima,
    dp.sub_total_terima
FROM detail_penerimaan dp
JOIN barang b ON dp.barang_idbarang = b.idbarang
JOIN satuan s ON b.idsatuan = s.idsatuan
ORDER BY dp.idpenerimaan DESC, dp.iddetail_penerimaan ASC;

CREATE OR REPLACE VIEW view_detail_penjualan AS
SELECT
    dp.iddetail_penjualan,
    dp.penjualan_idpenjualan AS idpenjualan,
    b.nama AS nama_barang,
    s.nama_satuan,
    dp.harga_satuan,
    dp.jumlah,
    dp.sub_total
FROM detail_penjualan dp
JOIN barang b ON dp.idbarang = b.idbarang
JOIN satuan s ON b.idsatuan = s.idsatuan
ORDER BY dp.penjualan_idpenjualan DESC, dp.iddetail_penjualan ASC;

CREATE OR REPLACE VIEW view_detail_retur AS
SELECT
    dr.iddetail_retur,
    dr.idretur,
    b.nama AS nama_barang,
    dr.jumlah AS jumlah_retur,
    dr.alasan
FROM detail_retur dr
JOIN detail_penerimaan dp ON dr.iddetail_penerimaan = dp.iddetail_penerimaan
JOIN barang b ON dp.barang_idbarang = b.idbarang
ORDER BY dr.idretur DESC, dr.iddetail_retur ASC;

-- =========================
-- VIEW KARTU STOK
-- =========================

CREATE OR REPLACE VIEW view_kartu_stok AS
SELECT
    ks.idkartu_stok,
    ks.created_at,
    b.nama AS nama_barang,
    CASE
        WHEN ks.jenis_transaksi = 'T' THEN 'Penerimaan (Masuk)'
        WHEN ks.jenis_transaksi = 'J' THEN 'Penjualan (Keluar)'
        WHEN ks.jenis_transaksi = 'R' THEN 'Retur (Keluar)'
        ELSE 'Lainnya'
    END AS jenis_transaksi_text,
    ks.jenis_transaksi,
    ks.idtransaksi,
    ks.masuk,
    ks.keluar,
    ks.stock AS sisa_stok
FROM kartu_stok ks
JOIN barang b ON ks.idbarang = b.idbarang
ORDER BY ks.created_at DESC, ks.idkartu_stok DESC;

USE pbdprak;

-- =========================
-- FUNCTION 1
-- Hitung Subtotal
-- =========================
DELIMITER $$

DROP FUNCTION IF EXISTS func_hitung_subtotal$$

CREATE FUNCTION func_hitung_subtotal(
    harga_satuan_param INT,
    jumlah_param INT
)
RETURNS INT
DETERMINISTIC
BEGIN
    RETURN harga_satuan_param * jumlah_param;
END$$

-- =========================
-- FUNCTION 2
-- Hitung PPN (10%)
-- =========================

DROP FUNCTION IF EXISTS func_hitung_ppn$$

CREATE FUNCTION func_hitung_ppn(
    nilai_subtotal_param INT
)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE v_tarif_ppn DOUBLE DEFAULT 0.10;
    DECLARE v_ppn_dihitung DOUBLE;

    SET v_ppn_dihitung = nilai_subtotal_param * v_tarif_ppn;
    RETURN CEILING(v_ppn_dihitung);
END$$

-- =========================
-- FUNCTION 3
-- Hitung Total + PPN
-- =========================

DROP FUNCTION IF EXISTS func_hitung_total_dengan_ppn$$

CREATE FUNCTION func_hitung_total_dengan_ppn(
    subtotal_param INT
)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE v_ppn_nilai INT;

    SET v_ppn_nilai = func_hitung_ppn(subtotal_param);
    RETURN subtotal_param + v_ppn_nilai;
END$$

-- =========================
-- FUNCTION 4
-- Hitung Harga Jual
-- =========================

DROP FUNCTION IF EXISTS func_hitung_harga_jual$$

CREATE FUNCTION func_hitung_harga_jual(
    id_barang_param INT
)
RETURNS INT
READS SQL DATA
BEGIN
    DECLARE v_harga_modal INT DEFAULT 0;
    DECLARE v_persen_margin DOUBLE DEFAULT 0;
    DECLARE v_harga_jual DOUBLE DEFAULT 0;

    SELECT harga
    INTO v_harga_modal
    FROM barang
    WHERE idbarang = id_barang_param;

    SELECT persen
    INTO v_persen_margin
    FROM margin_penjualan
    WHERE status = 1
    ORDER BY idmargin_penjualan DESC
    LIMIT 1;

    SET v_harga_jual = v_harga_modal * (1 + v_persen_margin);
    RETURN ROUND(v_harga_jual, 0);
END$$

DELIMITER ;

USE pbdprak;

DELIMITER $$

-- ==================================
-- PROCEDURE : Update Kartu Stok
-- ==================================

DROP PROCEDURE IF EXISTS proc_update_kartu_stok$$

CREATE PROCEDURE proc_update_kartu_stok(
    IN id_barang_param INT,
    IN id_transaksi_param INT,
    IN jenis_transaksi_param CHAR(1),
    IN qty_masuk_param INT,
    IN qty_keluar_param INT
)
BEGIN
    DECLARE stok_terakhir INT DEFAULT 0;
    DECLARE stok_baru INT DEFAULT 0;

    -- Ambil stok terakhir
    SELECT COALESCE(stock, 0)
    INTO stok_terakhir
    FROM kartu_stok
    WHERE idbarang = id_barang_param
    ORDER BY idkartu_stok DESC
    LIMIT 1;

    -- Hitung stok baru
    SET stok_baru = stok_terakhir + qty_masuk_param - qty_keluar_param;

    -- Validasi stok
    IF qty_keluar_param > 0 AND stok_baru < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Gagal: Stok barang tidak mencukupi!';
    ELSE
        INSERT INTO kartu_stok (
            idbarang,
            idtransaksi,
            jenis_transaksi,
            masuk,
            keluar,
            stock,
            created_at
        )
        VALUES (
            id_barang_param,
            id_transaksi_param,
            jenis_transaksi_param,
            qty_masuk_param,
            qty_keluar_param,
            stok_baru,
            NOW()
        );
    END IF;
END$$

DELIMITER ;

USE pbdprak;

DELIMITER $$

-- ==================================
-- TRIGGER 1
-- After Insert Detail Penerimaan
-- (Barang Masuk)
-- ==================================

DROP TRIGGER IF EXISTS trig_after_insert_detail_penerimaan$$

CREATE TRIGGER trig_after_insert_detail_penerimaan
AFTER INSERT ON detail_penerimaan
FOR EACH ROW
BEGIN
    CALL proc_update_kartu_stok(
        NEW.barang_idbarang,
        NEW.iddetail_penerimaan,
        'T',        -- Transaksi Masuk
        NEW.jumlah_terima,
        0
    );
END$$


-- ==================================
-- TRIGGER 2
-- After Insert Detail Penjualan
-- (Barang Keluar)
-- ==================================

DROP TRIGGER IF EXISTS trig_after_insert_detail_penjualan$$

CREATE TRIGGER trig_after_insert_detail_penjualan
AFTER INSERT ON detail_penjualan
FOR EACH ROW
BEGIN
    CALL proc_update_kartu_stok(
        NEW.idbarang,
        NEW.iddetail_penjualan,
        'J',        -- Transaksi Keluar
        0,
        NEW.jumlah
    );
END$$


-- ==================================
-- TRIGGER 3
-- After Insert Detail Retur
-- (Barang Keluar - Retur)
-- ==================================

DROP TRIGGER IF EXISTS trig_after_insert_detail_retur$$

CREATE TRIGGER trig_after_insert_detail_retur
AFTER INSERT ON detail_retur
FOR EACH ROW
BEGIN
    DECLARE v_id_barang INT;

    SELECT barang_idbarang
    INTO v_id_barang
    FROM detail_penerimaan
    WHERE iddetail_penerimaan = NEW.iddetail_penerimaan;

    CALL proc_update_kartu_stok(
        v_id_barang,
        NEW.iddetail_retur,
        'R',        -- Retur
        0,
        NEW.jumlah
    );
END$$

DELIMITER ;



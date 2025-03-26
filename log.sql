DELETE FROM buku_harian WHERE proyek_id = 16 AND supervisor_id = 2;





INSERT 
INTO buku_harian
(proyek_id, supervisor_id, tanggal, latitude, longitude, altitude, waktu_buat, waktu_ubah, ip_buat, ip_ubah, aktif) 
VALUES (168, '2', '2025-01-02', '-6.198645', '106.797267', '0', '2025-01-05 10:52:48', '2025-01-05 10:52:48', '127.0.0.1', '127.0.0.1', TRUE);

INSERT 
INTO pekerjaan
(pekerjaan_id, proyek_id, buku_harian_id, jenis_pekerjaan_id, lokasi_proyek_id, latitude, longitude, atitude, tipe_pondasi_id, kelas_tower_id, kegiatan, jumlah_pekerja, acuan_pengawasan, waktu_buat, waktu_ubah, ip_buat, ip_ubah, aktif) 
VALUES (0, 168, 0, 'EM', 5558, '-6.198645', '106.797267', '', 16, 5, 'Kegiatan', 3, '', '2025-01-05 10:52:48', '2025-01-05 10:52:48', '127.0.0.1', '127.0.0.1', FALSE);

INSERT 
INTO peralatan_proyek
(pekerjaan_id, peralatan_id, jumlah, proyek_id, aktif) 
VALUES ('123078', 538, 1, 168, TRUE);

INSERT 
INTO material_proyek
(pekerjaan_id, material_id, jumlah, proyek_id, aktif) 
VALUES ('123078', 486, 1, 168, TRUE);

INSERT 
INTO bill_of_quantity_proyek
(proyek_id, buku_harian_id, bill_of_quantity_id, volume, volume_proyek, persen, waktu_buat, waktu_ubah, supervisor_buat, supervisor_ubah, aktif) 
VALUES (168, '111128', 14, 4, '2', 50, '2025-01-05 10:52:48', '2025-01-05 10:52:48', '2', '2', TRUE);

UPDATE bill_of_quantity 
SET proyek_id = '168', parent_id = '13', level = '4', 
nama = '2.1.1 Conductor for 3-phase fly bus and conductor of line bay', 
satuan = 'Sets', volume = 4, bobot = 0.37736, volume_proyek = '2', 
harga = 342000000, sort_order = '0', admin_ubah = '1', 
waktu_ubah = '2024-12-29 23:14:56', ip_ubah = '172.70.142.82', aktif = '1' 
WHERE bill_of_quantity_id = '14' 
;

INSERT 
INTO bill_of_quantity_proyek
(proyek_id, buku_harian_id, bill_of_quantity_id, volume, volume_proyek, persen, waktu_buat, waktu_ubah, supervisor_buat, supervisor_ubah, aktif) 
VALUES (168, '111128', 11, 4, '2', 50, '2025-01-05 10:52:48', '2025-01-05 10:52:48', '2', '2', TRUE);

UPDATE bill_of_quantity 
SET proyek_id = '168', parent_id = '10', level = '3', 
nama = '2.4.1 150 kV 3 Phase CB for Line Bay', satuan = 'Sets', volume = 4, 
bobot = 0.02207, volume_proyek = '2', harga = 2180810000, sort_order = '0', 
admin_ubah = '1', waktu_ubah = '2024-12-29 22:22:56', ip_ubah = '172.70.206.75', 
aktif = '1' 
WHERE bill_of_quantity_id = '11' 
;

INSERT 
INTO progres_proyek
(proyek_id, persen, waktu_buat, waktu_ubah, supervisor_buat, supervisor_ubah, ip_buat, ip_ubah, aktif) 
VALUES (168, 96.276767221023, '2025-01-05 10:52:48', '2025-01-05 10:52:48', '2', '2', '127.0.0.1', '127.0.0.1', TRUE);

UPDATE proyek 
SET persen = 96.276767221023 
WHERE proyek_id = 168 
;


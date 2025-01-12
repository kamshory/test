-- perbaiki nama tabel dan kolom yang tertukar

create table kelas_tower like tipe_tower;
insert into kelas_tower select * from tipe_tower;
alter table kelas_tower change tipe_tower_id kelas_tower_id bigint(20); 

create table tipe_pondasi like kelas_pondasi;
insert into tipe_pondasi select * from kelas_pondasi;
alter table tipe_pondasi change kelas_pondasi_id tipe_pondasi_id bigint(20);

alter table pekerjaan change tipe_tower_id kelas_tower_id bigint(20); 
alter table pekerjaan change kelas_pondasi_id tipe_pondasi_id bigint(20);

alter table jenis_pekerjaan change tipe_tower_id kelas_tower_id bigint(20); 
alter table jenis_pekerjaan change kelas_pondasi_id tipe_pondasi_id bigint(20);

-- perbaiki nama kolom yang menggunakan reserved word

-- On development environtment
ALTER TABLE `acuan_pengawasan` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `akhir_pekan` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `cabang` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `gender` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `grup_modul` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `jabatan` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `jenis_cuti` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `jenis_hari_libur` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `jenis_pekerjaan` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `jenis_perjalanan_dinas` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `kelas_pondasi` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `lang` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `material` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `modul` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `peralatan` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `theme` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `tipe_tower` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  
ALTER TABLE `user_level` CHANGE `order` `sort_order` INT(11) NULL DEFAULT '1', CHANGE `default` `default_data` TINYINT(1) NULL DEFAULT '0';  

ALTER TABLE `user_level` 
    CHANGE `view` `allowed_detail` INT(11) NULL DEFAULT '1', 
    CHANGE `insert` `allowed_create` TINYINT(1) NULL DEFAULT '0',
    CHANGE `update` `allowed_update` TINYINT(1) NULL DEFAULT '0',
    CHANGE `delete` `allowed_delete` TINYINT(1) NULL DEFAULT '0'
;  

ALTER TABLE `hak_akses` 
    ADD `modul_id` BIGINT(20) NULL DEFAULT NULL AFTER `hak_akses_id`, 
    ADD `allowed_list` TINYINT(1) NULL DEFAULT NULL AFTER `kode_modul`,
    ADD `allowed_approve` TINYINT(1) NULL DEFAULT NULL AFTER `allowed_delete`,
    ADD `allowed_sort_order` TINYINT(1) NULL DEFAULT NULL AFTER `allowed_approve`
; 


UPDATE `hak_akses` SET 
    modul_id = (SELECT modul.modul_id FROM modul WHERE modul.kode_modul = hak_akses.kode_modul LIMIT 0,1);
UPDATE `hak_akses` SET
    `allowed_list` = true,
    `allowed_approve` = true,
    `allowed_sort_order` = true
;


UPDATE `hak_akses` SET modul_id = (SELECT modul.modul_id FROM modul WHERE modul.kode_modul = hak_akses.kode_modul LIMIT 0,1);
UPDATE `hak_akses` SET
`allowed_list` = true,
`allowed_detail` = `view`,
`allowed_create` = `insert`,
`allowed_update` = `update`,
`allowed_delete` = `delete`,
`allowed_approve` = true,
`allowed_sort_order` = true;


USE sipro;
UPDATE `hak_akses` SET
`allowed_list` = true,
`allowed_detail` = true,
`allowed_create` = true,
`allowed_update` = true,
`allowed_delete` = true,
`allowed_approve` = true,
`allowed_sort_order` = true
WHERE user_level_id = 1
;


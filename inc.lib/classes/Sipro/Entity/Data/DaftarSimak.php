<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The DaftarSimak class represents an entity in the "daftar_simak" table.
 *
 * This entity maps to the "daftar_simak" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="daftar_simak")
 */
class DaftarSimak extends MagicObject
{
	/**
	 * Daftar Simak ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="daftar_simak_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Daftar Simak ID")
	 * @var int
	 */
	protected $daftarSimakId;

	/**
	 * Umk ID
	 * 
	 * @Column(name="umk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Umk ID")
	 * @var int
	 */
	protected $umkId;

	/**
	 * Umk
	 * 
	 * @JoinColumn(name="umk_id", referenceColumnName="umk_id")
	 * @Label(content="Umk")
	 * @var UmkMin
	 */
	protected $umk;

	/**
	 * Tsk ID
	 * 
	 * @Column(name="tsk_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Tsk ID")
	 * @var int
	 */
	protected $tskId;

	/**
	 * Tsk
	 * 
	 * @JoinColumn(name="tsk_id", referenceColumnName="tsk_id")
	 * @Label(content="Tsk")
	 * @var TskMin
	 */
	protected $tsk;

	/**
	 * Proyek ID
	 * 
	 * @Column(name="proyek_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Proyek ID")
	 * @var int
	 */
	protected $proyekId;

	/**
	 * Proyek
	 * 
	 * @JoinColumn(name="proyek_id", referenceColumnName="proyek_id")
	 * @Label(content="Proyek")
	 * @var ProyekMin
	 */
	protected $proyek;

	/**
	 * Formulir Simak ID
	 * 
	 * @NotNull
	 * @Column(name="formulir_simak_id", type="bigint(20)", length=20, nullable=false)
	 * @Label(content="Formulir Simak ID")
	 * @var int
	 */
	protected $formulirSimakId;

	/**
	 * Formulir Simak
	 * 
	 * @JoinColumn(name="formulir_simak_id", referenceColumnName="formulir_simak_id")
	 * @Label(content="Formulir Simak")
	 * @var FormulirSimakMin
	 */
	protected $formulirSimak;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Nomor Formulir
	 * 
	 * @Column(name="nomor_formulir", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Formulir")
	 * @var string
	 */
	protected $nomorFormulir;

	/**
	 * Tanggal Mulai
	 * 
	 * @Column(name="tanggal_mulai", type="date", length=10, nullable=true)
	 * @Label(content="Tanggal Mulai")
	 * @var string
	 */
	protected $tanggalMulai;

	/**
	 * Tanggal Selesai
	 * 
	 * @Column(name="tanggal_selesai", type="date", length=10, nullable=true)
	 * @Label(content="Tanggal Selesai")
	 * @var string
	 */
	protected $tanggalSelesai;

	/**
	 * Pekerjaan
	 * 
	 * @Column(name="pekerjaan", type="longtext", nullable=true)
	 * @Label(content="Pekerjaan")
	 * @var string
	 */
	protected $pekerjaan;

	/**
	 * Nomor Kontrak
	 * 
	 * @Column(name="nomor_kontrak", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Kontrak")
	 * @var string
	 */
	protected $nomorKontrak;

	/**
	 * Lokasi
	 * 
	 * @Column(name="lokasi", type="text", nullable=true)
	 * @Label(content="Lokasi")
	 * @var string
	 */
	protected $lokasi;

	/**
	 * Judul Dokumen
	 * 
	 * @Column(name="judul_dokumen", type="text", nullable=true)
	 * @Label(content="Judul Dokumen")
	 * @var string
	 */
	protected $judulDokumen;

	/**
	 * Nomor Dokumen
	 * 
	 * @Column(name="nomor_dokumen", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Dokumen")
	 * @var string
	 */
	protected $nomorDokumen;

	/**
	 * Jenis Dokumen
	 * 
	 * @Column(name="jenis_dokumen", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Jenis Dokumen")
	 * @var string
	 */
	protected $jenisDokumen;

	/**
	 * Persentase
	 *
	 * @Column(name="persentase", type="double", nullable=true)
	 * @Label(content="Persentase")
	 * @var float
	 */
	protected $persentase;

	/**
	 * Catatan
	 * 
	 * @Column(name="catatan", type="longtext", nullable=true)
	 * @Label(content="Catatan")
	 * @var string
	 */
	protected $catatan;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, nullable=true)
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

	/**
	 * Admin Buat
	 * 
	 * @Column(name="admin_buat", type="varchar(40)", length=40, nullable=true, updatable=false)
	 * @Label(content="Admin Buat")
	 * @var string
	 */
	protected $adminBuat;

	/**
	 * Pembuat
	 * 
	 * @JoinColumn(name="admin_buat", referenceColumnName="admin_id")
	 * @Label(content="Pembuat")
	 * @var AdminMin
	 */
	protected $pembuat;

	/**
	 * Admin Ubah
	 * 
	 * @Column(name="admin_ubah", type="varchar(40)", length=40, nullable=true)
	 * @Label(content="Admin Ubah")
	 * @var string
	 */
	protected $adminUbah;

	/**
	 * Pengubah
	 * 
	 * @JoinColumn(name="admin_ubah", referenceColumnName="admin_id")
	 * @Label(content="Pengubah")
	 * @var AdminMin
	 */
	protected $pengubah;

	/**
	 * Waktu Buat
	 * 
	 * @Column(name="waktu_buat", type="timestamp", length=26, nullable=true, updatable=false)
	 * @Label(content="Waktu Buat")
	 * @var string
	 */
	protected $waktuBuat;

	/**
	 * Waktu Ubah
	 * 
	 * @Column(name="waktu_ubah", type="timestamp", length=26, nullable=true)
	 * @Label(content="Waktu Ubah")
	 * @var string
	 */
	protected $waktuUbah;

	/**
	 * IP Buat
	 * 
	 * @Column(name="ip_buat", type="varchar(50)", length=50, nullable=true, updatable=false)
	 * @Label(content="IP Buat")
	 * @var string
	 */
	protected $ipBuat;

	/**
	 * IP Ubah
	 * 
	 * @Column(name="ip_ubah", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="IP Ubah")
	 * @var string
	 */
	protected $ipUbah;

	/**
	 * Aktif
	 * 
	 * @NotNull
	 * @Column(name="aktif", type="tinyint(1)", length=1, defaultValue="1", nullable=false)
	 * @DefaultColumn(value="1")
	 * @Label(content="Aktif")
	 * @var bool
	 */
	protected $aktif;

	/**
	 * Update umk_id
	 *
	 * @query("
	 * update daftar_simak set daftar_simak.umk_id = (select tsk.umk_id from tsk where tsk.tsk_id = daftar_simak.tsk_id) where daftar_simak.daftar_simak_id = :daftarSimakId
	 * ")
	 * @param int $daftarSimakId
	 * @return void
	 */
	public function updateUmk($daftarSimakId)
	{
		return $this->executeNativeQuery();
	}

	/**
	 * Get daftar pengawas
	 *
	 * @return void
	 */
	public function daftarKontributor()
	{
		$loader = new RiwayatDaftarSimak(null, $this->currentDatabase());
		$pageData = $loader->findByDaftarSimakId($this->getDaftarSimakId());
		$daftarPengawas = [];
		foreach($pageData->getResult() as $riwayat)
		{
			$daftarPengawas[] = $riwayat->issetAdmin() ? $riwayat->getAdmin()->getNamaDepan() : "";
		}
		if(empty($daftarPengawas))
		{
			return "";
		}
		$daftarPengawas = array_unique($daftarPengawas);
		return "<ol><li>".implode("</li><li>", $daftarPengawas)."</li></ol>";
	}

}
<?php

namespace Sipro\Entity\Data;

use MagicObject\MagicObject;

/**
 * The FormulirSimakUmk class represents an entity in the "formulir_simak" table.
 *
 * This entity maps to the "formulir_simak" table in the database and supports ORM (Object-Relational Mapping) operations. 
 * You can establish relationships with other entities using the JoinColumn annotation. 
 * Ensure to include the appropriate "use" statement if related entities are defined in a different namespace.
 * 
 * For detailed guidance on using the MagicObject ORM, refer to the official tutorial:
 * @link https://github.com/Planetbiru/MagicObject/blob/main/tutorial.md#orm
 * 
 * @package Sipro\Entity\Data
 * @Entity
 * @JSON(property-naming-strategy=SNAKE_CASE, prettify=false)
 * @Table(name="formulir_simak")
 */
class FormulirSimakUmk extends MagicObject
{
	/**
	 * Formulir Simak ID
	 * 
	 * @Id
	 * @GeneratedValue(strategy=GenerationType.IDENTITY)
	 * @NotNull
	 * @Column(name="formulir_simak_id", type="bigint(20)", length=20, nullable=false, extra="auto_increment")
	 * @Label(content="Formulir Simak ID")
	 * @var int
	 */
	protected $formulirSimakId;

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
	 * Prosedur ID
	 * 
	 * @Column(name="prosedur_id", type="bigint(20)", length=20, nullable=true)
	 * @Label(content="Prosedur ID")
	 * @var int
	 */
	protected $prosedurId;

	/**
	 * Prosedur
	 * 
	 * @JoinColumn(name="prosedur_id", referenceColumnName="prosedur_id")
	 * @Label(content="Prosedur")
	 * @var ProsedurMin
	 */
	protected $prosedur;

	/**
	 * Nama
	 * 
	 * @Column(name="nama", type="varchar(50)", length=50, nullable=true)
	 * @Label(content="Nama")
	 * @var string
	 */
	protected $nama;

	/**
	 * Judul Formulir
	 * 
	 * @Column(name="judul_formulir", type="text", nullable=true)
	 * @Label(content="Judul Formulir")
	 * @var string
	 */
	protected $judulFormulir;

	/**
	 * Nomor Formulir
	 * 
	 * @Column(name="nomor_formulir", type="varchar(100)", length=100, nullable=true)
	 * @Label(content="Nomor Formulir")
	 * @var string
	 */
	protected $nomorFormulir;

	/**
	 * Jumlah Daftar Simak
	 * 
	 * @Column(name="jumlah_daftar_simak", type="int(11)", length=11, defaultValue=0, nullable=true)
	 * @Label(content="Jumlah Daftar Simak")
	 * @var string
	 */
	protected $jumlahDaftarSimak;

	/**
	 * Sort Order
	 * 
	 * @Column(name="sort_order", type="int(11)", length=11, nullable=true)
	 * @Label(content="Sort Order")
	 * @var int
	 */
	protected $sortOrder;

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
	 * Updates the `jumlah_daftar_simak` column in the `formulir_simak` table 
 	 * by counting the number of distinct `daftar_simak_id` values associated with a specific `formulir_simak_id`.
	 *
	 * @query("
		UPDATE formulir_simak
		SET formulir_simak.jumlah_daftar_simak = 
		(SELECT COUNT(DISTINCT daftar_simak.daftar_simak_id)
		FROM daftar_simak
		WHERE daftar_simak.formulir_simak_id = formulir_simak.formulir_simak_id
		)
		WHERE formulir_simak.formulir_simak_id = :formulirSimakId;
		")
	 * @param int $formulirSimakId
	 * @return void
	 */
	public function updateJumlahFormulirSimak($formulirSimakId)
	{
		return $this->executeNativeQuery();
	}

}
<?php

use MagicApp\Field;
use MagicObject\Database\PicoDatabaseQueryBuilder;
use MagicObject\Database\PicoPredicate;
use MagicObject\Database\PicoSort;
use MagicObject\Database\PicoSortable;
use MagicObject\Database\PicoSpecification;
use MagicObject\Request\InputGet;
use MagicObject\Request\PicoFilterConstant;
use MagicObject\Util\File\FileUtil;
use Sipro\Entity\Data\AkhirPekan;
use Sipro\Entity\Data\BukuHarian;
use Sipro\Entity\Data\Cuti;
use Sipro\Entity\Data\CutiSupervisor;
use Sipro\Entity\Data\HariLibur;
use Sipro\Entity\Data\PerjalananDinas;
use Sipro\Entity\Data\Proyek;
use Sipro\Entity\Data\Supervisor;
use Sipro\Util\CommonUtil;
use Sipro\Util\DateUtil;
use Sipro\Util\TimeSheetUtil;

require_once dirname(__DIR__) . "/inc.app/auth.php";

if (!isset($inputGet)) {
	$inputGet = new InputGet();
}

$supervisorId = $inputGet->getSupervisorId(PicoFilterConstant::FILTER_SANITIZE_NUMBER_INT, false, false, true);
$periodeId = $inputGet->getPeriodeId(PicoFilterConstant::FILTER_SANITIZE_SPECIAL_CHARS, false, false, true);

$dataFilter = null;

if($currentUser->getTskId() != 0)
{
	$dataFilter = PicoSpecification::getInstance()
		->addAnd(['tskId', $currentUser->getTskId()])
		;
}

if ($supervisorId != 0 && !empty($periodeId)) {

	$timeSheetUtil = new TimeSheetUtil();
	
	$dateFormatter = "%04d-%02d-%02d";
	$longDateFormat = "j F Y";

	$supervisor = new Supervisor(null, $database);
	try {
		$supervisor->find($supervisorId);
	} catch (Exception $e) {
		// do nothing
	}

	$nip = $supervisor->getNip();

	$tahun = substr($periodeId, 0, 4);
	$bulan = substr($periodeId, 4, 2);
	$tahun_bulan = sprintf("%04d-%02d", $tahun, $bulan);
	$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
	$jumlahHariKerja = 0;
	$jumlah_hari_libur = 0;
	$arrayHari = array();
	$arrayProyek = array();
	$arrayBukuHarian = array();
	$totalTanggal = array();

	$akhirPekanList = new AkhirPekan(null, $database);
	try {
		$akhirPekanPageData = $akhirPekanList->findByAktif(true);
		foreach ($akhirPekanPageData->getResult() as $akhirPekan) {
			$timeSheetUtil->addAkhirPekan($akhirPekan);
		}
	} catch (Exception $e) {
		// do nothing
	}

	$tanggalAwal = sprintf($dateFormatter, $tahun, $bulan, 1);
	$tanggalAkhir = sprintf($dateFormatter, $tahun, $bulan, $jumlah_hari);

	$specsPerjalananDinas = PicoSpecification::getInstance()
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->supervisorId, $supervisorId))
		->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals(Field::of()->dari, $tanggalAwal))
		->addAnd(PicoPredicate::getInstance()->lessThanOrEquals(Field::of()->hingga, $tanggalAkhir))
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true));
	$selectorPerjalananDinas = new PerjalananDinas(null, $database);

	$perjalanan_dinas = array();
	$perjalanan_dinas_note = array();

	try {
		$pdPageData = $selectorPerjalananDinas->findAll($specsPerjalananDinas);
		foreach ($pdPageData->getResult() as $perjalananDinas) {
			$dari = strtotime($perjalananDinas->getDari());
			$hingga = strtotime($perjalananDinas->getHingga());
			for ($time = $dari; $time <= $hingga; $time += 86400) {
				$perjalanan_dinas[] = date('Y-m-d', $time);
			}
			$timeSheetUtil->addPerjalananDinas($perjalananDinas);
			$perjalanan_dinas_note[] = "D : SPPD " . $perjalananDinas->getAsal() . " - " . $perjalananDinas->getTujuan() . " - " . $perjalananDinas->getKeperluan() . " " . DateUtil::translatedate($appLanguage, date($longDateFormat, strtotime($perjalananDinas->getDari()))) . " - " . DateUtil::translatedate($appLanguage, date($longDateFormat, strtotime($perjalananDinas->getHingga())));
		}
	} catch (Exception $e) {
		// do nothing
	}

	$bukuHarian = new BukuHarian(null, $database);
	$tabelInfoBukuHarian = $bukuHarian->tableInfo();
	$tabelBukuHarian = $tabelInfoBukuHarian->getTableName();
	$kolomTanggalBukuHarian = $tabelInfoBukuHarian->getColumns()[Field::of()->tanggal]['name'];
	$kolomSupervisorId = $tabelInfoBukuHarian->getColumns()[Field::of()->supervisorId]['name'];

	$cuti = new Cuti(null, $database);
	$tabelInfoCuti = $cuti->tableInfo();
	$tabelCuti = $tabelInfoCuti->getTableName();
	$kolomCutiDari = $tabelInfoCuti->getColumns()[Field::of()->cutiDari]['name'];
	$kolomSupervisorId = $tabelInfoCuti->getColumns()[Field::of()->supervisorId]['name'];

	$queryBuilder = new PicoDatabaseQueryBuilder($database);
	$queryBuilder
		->newQuery()
		->select($kolomTanggalBukuHarian)
		->from($tabelBukuHarian)
		->where("$kolomTanggalBukuHarian => ? and $kolomTanggalBukuHarian <= ? and $kolomSupervisorId = ?", $tanggalAwal, $tanggalAkhir, $supervisorId)
		->groupBy($kolomTanggalBukuHarian)
		->orderBy("$kolomTanggalBukuHarian asc");

	$tanggalKerja = array();
	$resultBukuHarian = $database->fetchAll($queryBuilder);
	if (!empty($resultBukuHarian)) {
		$timeSheetUtil->addTanggal($resultBukuHarian);
		$tanggalKerja = array_values($resultBukuHarian);
	}

	$specsCutiList = PicoSpecification::getInstance()
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->supervisorId, $supervisorId))
		->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals(Field::of()->cutiDari, $tanggalAwal))
		->addAnd(PicoPredicate::getInstance()->lessThanOrEquals(Field::of()->cutiDari, $tanggalAkhir))
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true))
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->status, 'A'));
	$cutiList = new Cuti(null, $database);

	$cuti_note = array();

	try {
		$cutiPageData = $cutiList->findAll($specsCutiList);
		foreach ($cutiPageData->getResult() as $cuti) {
			$timeSheetUtil->addCuti($cuti);
			$cuti_note[] = ($cuti->hasValueJenisCuti() ? $cuti->getJenisCuti()->getLambang() : "") . " : " . $cuti->getKeterangan() . " " . DateUtil::translatedate($appLanguage, date($longDateFormat, strtotime($cuti->getCutiDari()))) . " - " . DateUtil::translatedate($appLanguage, date($longDateFormat, strtotime($cuti->getCutiHingga())));
		}
	} catch (Exception $e) {
		$cutiPageData = null;
	}

	$specsHariLibur = PicoSpecification::getInstance()
		->addAnd(PicoPredicate::getInstance()->greaterThanOrEquals(Field::of()->tanggal, $tanggalAwal))
		->addAnd(PicoPredicate::getInstance()->lessThanOrEquals(Field::of()->tanggal, $tanggalAkhir))
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->aktif, true));

	$hari_libur_note = array();
	$hariLiburList = new HariLibur(null, $database);
	try {
		$hariLiburPageData = $hariLiburList->findAll($specsHariLibur);
		foreach ($hariLiburPageData->getResult() as $hariLibur) {
			$timeSheetUtil->addHariLibur($hariLibur);
			$hari_libur_note[] = "Tanggal " . DateUtil::translatedate($appLanguage, date($longDateFormat, strtotime($hariLibur->getTanggal()))) . " " . $hariLibur->getNama();
		}
	} catch (Exception $e) {
		$hariLiburPageData = null;
	}

	$hariAktif = 0;
	for ($i = 1; $i <=  $jumlah_hari; $i++) {
		$tanggalBerjalan = sprintf($dateFormatter, $tahun, $bulan, $i);
		$isAkhirPekan = $timeSheetUtil->isAkhirPekan($tanggalBerjalan);
		$isHariLibur = $timeSheetUtil->isHariLibur($tanggalBerjalan);
		$infoCuti = $timeSheetUtil->getInfoCuti($tanggalBerjalan);
		if (!$isAkhirPekan && !$isHariLibur) {
			$jumlahHariKerja++;
			$hariAktif++;
		} else {
			if (in_array($tanggalBerjalan, $tanggalKerja)) {
				$jumlahHariKerja++;
			}
		}

		$arrayHari[$tanggalBerjalan] = array(
			"tanggal" => $i,
			"akhir_pekan" => $isAkhirPekan ? 1 : 0,
			"tanggal_merah" => $isHariLibur ? 1 : 0,
			"cuti" => $infoCuti->hasValueCutiId(),
			"cuti_dibayar" => $infoCuti->hasValueCutiId() && $infoCuti->getDibayar() == 1 ? 1 : 0,
			"proyek_id" => $infoCuti->getProyekId(),
			"lambang" => $infoCuti->hasValueJenisCuti() ? $infoCuti->getJenisCuti()->getLambang() : null
		);
	}

	if (count($tanggalKerja) < $hariAktif) {
		$pembagi = $hariAktif;
	} else {
		$pembagi = count($tanggalKerja);
	}

	$specsBukuHarian = PicoSpecification::getInstance()
		->addAnd(PicoPredicate::getInstance()->equals(Field::of()->supervisorId, $supervisorId))
		->addAnd(PicoPredicate::getInstance()->like(Field::of()->tanggal, PicoPredicate::generateLikeStarts($tahun_bulan)));
	$bukuHarianList = new BukuHarian(null, $database);

	$adminKtskArray = array();

	try {
		$bukuHarianPageData = $bukuHarianList->findAll($specsBukuHarian);
		$arrayTanggal = array();
		$buff = array();
		foreach ($bukuHarianPageData->getResult() as $bukuHarian) {
			$tanggal = $bukuHarian->getTanggal();
			$proyekId = $bukuHarian->getProyekId();
			if (!isset($arrayTanggal[$tanggal])) {
				$arrayTanggal[$tanggal] = array();
			}
			if (!isset($arrayTanggal[$tanggal][$proyekId])) {
				$arrayTanggal[$tanggal][$proyekId] = array();
			}
			$arrayTanggal[$tanggal][$proyekId][] = $bukuHarian;
			if (!isset($arrayProyek[$proyekId])) {

				$proyek = $bukuHarian->hasValueProyek() ? $bukuHarian->getProyek() : new Proyek(null, $database);
				$supervisor = $bukuHarian->hasValueSupervisor() ? $bukuHarian->getSupervisor() : new Supervisor(null, $database);
				$namaProyek = $proyek->getNama();
				$kodeLokasi = $proyek->getKodeLokasi();
				$jabatan = $supervisor->hasValueJabatan() ? $supervisor->getJabatan()->getNama() : "";
				$ktskId = $proyek->getKtskId();

				$accKtsk = $bukuHarian->getAccKtsk();

				$arrayProyek[$proyekId] = array(
					"nama" => $namaProyek,
					"kode_lokasi" => $kodeLokasi,
					"jabatan" => $jabatan,
					"ktsk_id" => $ktskId,
					"acc_ktsk" => $accKtsk
				);
			}
			if (!isset($arrayBukuHarian[$tanggal])) {
				$arrayBukuHarian[$tanggal] = 0;
			}
			if (!in_array($tanggal . "-" . $proyekId, $buff)) {
				$buff[] = $tanggal . "-" . $proyekId;
				$arrayBukuHarian[$tanggal]++;
			}
		}
	} catch (Exception $e) {
		// do nothing
	}
	$anoterProjects = [];
	if (!empty($arrayProyek)) {

		$buff = array();

		$projects = array_keys($arrayProyek);


		$cutiSupervisorList = new CutiSupervisor(null, $database);

		$specsCutiSuper = PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->equals(Field::of()->supervisorId, $supervisorId))
			->addAnd(PicoPredicate::getInstance()->like(Field::of()->tanggal, PicoPredicate::generateLikeStarts($tahun_bulan)))
			->addAnd(PicoPredicate::getInstance()->notEquals('cuti.proyekId', null))
			->addAnd(PicoPredicate::getInstance()->notEquals('cuti.proyekId', 0));

		if (count($projects)) {
			$specsCutiSuper->addAnd(PicoPredicate::getInstance()->notIn('cuti.proyekId', $projects));
		}

		try {
			$sortable = PicoSortable::getInstance()
				->add(new PicoSort('cuti.proyekId', PicoSort::ORDER_TYPE_ASC));
			$cutiSupervisorPageData = $cutiSupervisorList->findAll($specsCutiSuper, null, $sortable);
			foreach ($cutiSupervisorPageData->getResult() as $cutiSupervisor) {

				if ($cutiSupervisor->hasValueCuti()) {
					$proyekId = $cutiSupervisor->getCuti()->getProyekId();
					if (!isset($arrayProyek[$proyekId])) {
						$proyek = $cutiSupervisor->getCuti()->hasValueProyek() ? $cutiSupervisor->getCuti()->getProyek() : new Proyek(null, $database);
						$supervisor = $cutiSupervisor->hasValueSupervisor() ? $cutiSupervisor->getSupervisor() : new Supervisor(null, $database);
						$namaProyek = $proyek->getNama();
						$kodeLokasi = $proyek->getKodeLokasi();
						$jabatan = $supervisor->hasValueJabatan() ? $supervisor->getJabatan()->getNama() : "";
						$ktskId = $proyek->getKtskId();
						$arrayProyek[$proyekId] = array(
							"nama" => $namaProyek,
							"kode_lokasi" => $kodeLokasi,
							"jabatan" => $jabatan,
							"ktsk_id" => $ktskId
						);
					}
				}
			}

			// proyek lain dalam PST yang sama

			$specsOtherProyects = PicoSpecification::getInstance()
			->addAnd(PicoPredicate::getInstance()->notIn(Field::of()->proyekId, $projects))
			->addAnd(PicoPredicate::getInstance()->equals(Field::of()->tskId, $supervisor->getTskId()))
			->addAnd(['aktif', true])
			->addAnd(['draft', false])
			;

			$otherProjectFinder = new Proyek(null, $database);

			try
			{
				$pd = $otherProjectFinder->findAll($specsOtherProyects);
				$anoterProjects = $pd->getResult();
			}
			catch(Exception $e)
			{
				// Do nothing
			}

				


		} catch (Exception $e) {
			// do nothing
		}

		/**
		 * Get nilai hari
		 *
		 * @param array $arrayBukuHarian
		 * @param array $arrayHari
		 * @param string $indeksArrayHari
		 * @param int $indeksArrayProyek
		 * @return int
		 */
		function getNominal($arrayBukuHarian, $arrayHari, $indeksArrayHari, $indeksArrayProyek)
		{
			$mm = 0;
			if (isset($arrayBukuHarian[$indeksArrayHari])) {
				if ($arrayBukuHarian[$indeksArrayHari] > 0) {
					$mm = 1 / $arrayBukuHarian[$indeksArrayHari];
				}
			} else if ($arrayHari[$indeksArrayHari]['cuti_dibayar'] == 1 && $arrayHari[$indeksArrayHari]['proyek_id'] == $indeksArrayProyek) {
				$mm = 1;
			}
			return $mm;
		}
?>
	
	<style type="text/css">
		.supervisor-sign-text {
			white-space: nowrap;
		}

		.time-sheet {
			border-collapse: collapse;
		}

		.time-sheet thead td {
			font-weight: bold;
		}

		.time-sheet td {
			padding: 3px 3px;
		}

		.day {
			text-align: center;
			padding: 3px 2px !important;
		}

		.weekend {
			background-color: #B9B9B9;
		}

		.dayoff {
			background-color: #B9B9B9;
		}

		.travel {
			background-color: #03F !important;
		}

		tfoot .leave {
			background-color: transparent;
		}

		.table-scroll-horizontal {
			overflow-x: auto;
			position: relative;
		}

		.table-scroll-horizontal h1 {
			font-weight: normal;
			font-size: 16px;
			text-align: center;
		}
		.time-sheet{
			font-family: Tahoma, Geneva, sans-serif;
			font-size: 12px;
		}

		.time-sheet td{
			border: 1px solid #555555;
		}

		.time-sheet td table td{
			border: none;
		}
		.time-sheet-noborder{
			border: none;
			font-family: Tahoma, Geneva, sans-serif;
			font-size: 12px;
		}
		td .signature-container{
			margin-top: -3px;
			margin-bottom: -3px;
		}
	</style>
	<div class="table-scroll-horizontal">
		<h1>LEMBAR WAKTU KERJA</h1>
		<?php
		$hari_kerja = 0;
		?>
		<table width="100%" cellpadding="0" cellspacing="0" border="1" class="time-sheet">
			<thead>
				<tr>
					<td colspan="<?php echo $jumlah_hari + 7; ?>">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td width="10%">NAMA</td>
								<td width="30%"><?php echo $supervisor->getNama(); ?></td>
								<td width="40%">&nbsp;</td>
								<td width="10%">Bulan</td>
								<td width="10%"><?php echo DateUtil::getMonthArray()[$bulan - 1]; ?></td>
							</tr>
							<tr>
								<td>NIP</td>
								<td><?php echo $nip; ?></td>
								<td>&nbsp;</td>
								<td>Tahun</td>
								<td><?php echo $tahun; ?></td>
							</tr>
							<tr>
								<td>JABATAN</td>
								<td><?php echo $supervisor->issetJabatan() ? $supervisor->getJabatan()->getNama() : ''; ?></td>
								<td>&nbsp;</td>
								<td>Lembar</td>
								<td>1</td>
							</tr>
							<tr>
								<td>UNIT/PST</td>
								<td>PUSMANPRO - <?php echo $supervisor->issetTsk() ? $supervisor->getTsk()->getNama() : ''; ?></td>
								<td>&nbsp;</td>
								<td></td>
								<td></td>
							</tr>
						</table>

					</td>
				</tr>
				<tr>
					<td width="20" rowspan="2">No</td>
					<td rowspan="2">Nama &amp; Lokasi Proyek</td>
					<td rowspan="2">Kode Lokasi</td>
					<td colspan="<?php echo $jumlah_hari; ?>" align="center">Tanggal</td>
					<td rowspan="2" align="center">Jumlah hari</td>
					<td rowspan="2" align="center">Propor-sional MM</td>
					<td rowspan="2" align="center">Paraf Atasan Langsung</td>
				</tr>
				<tr>
					<?php
					foreach ($arrayHari as $indeksArrayHari => $val) {
						$infoTanggal = $arrayHari[$indeksArrayHari];
						$class = CommonUtil::getCalendarClass($infoTanggal);
						
						if (!CommonUtil::isTrue($infoTanggal['akhir_pekan']) && !CommonUtil::isTrue($infoTanggal['tanggal_merah'])) {
							$hari_kerja++;
						}
					?>
						<td width="20" class="<?php echo $class; ?> day"><?php echo $val['tanggal']; ?></td>
					<?php
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 0;
				foreach ($arrayProyek as $indeksArrayProyek => $val2) {
					$no++;
				?>
					<tr>
						<td align="right"><?php echo $no; ?></td>
						<td><?php echo $val2['nama']; ?></td>
						<td><?php echo $val2['kode_lokasi']; ?></td>
						<?php
						$totalBaris = 0;
						foreach ($arrayHari as $indeksArrayHari => $val) {
							$infoTanggal = $arrayHari[$indeksArrayHari];
							$class = CommonUtil::getCalendarClass($infoTanggal);
							if (!isset($totalTanggal[$indeksArrayHari])) {
								$totalTanggal[$indeksArrayHari] = 0;
							}
						?>
							<td class="<?php echo trim($class); ?> day"><?php
							if (isset($arrayTanggal[$indeksArrayHari][$indeksArrayProyek]) || CommonUtil::isTrue($arrayHari[$indeksArrayHari]['cuti_dibayar'])) // && !@$arrayHari[$indeksArrayHari]['akhir_pekan'] && !@$arrayHari[$indeksArrayHari]['tanggal_merah'])
							{
								$mm = getNominal($arrayBukuHarian, $arrayHari, $indeksArrayHari, $indeksArrayProyek);
								
								echo number_format($mm, 2, ".", ",");
								$totalBaris += $mm;
								$totalTanggal[$indeksArrayHari] += $mm;
							} else {
								echo "0";
							}
							?></td>
						<?php
						}
						if ($totalBaris > $pembagi) {
							$pembagi = $totalBaris;
						}
						
						?>
						<td align="center"><?php echo number_format($totalBaris, 2); ?></td>
						<td align="center"><?php echo number_format($totalBaris / $pembagi, 2); ?></td>
						<td align="center">
						<div style="height:80px; overflow:visible" class="signature-container">
						<?php
						if(isset($val2['acc_ktsk']))
						{
							$signaturePath1 = dirname(dirname(__FILE__)) . "/lib.signature/" . $val2['acc_ktsk'] . "/signature.png";
							$signaturePath1 = FileUtil::fixFilePath($signaturePath1);
							if(file_exists($signaturePath1))
							{
							$filetime = filemtime($signaturePath1);
							?>
							<img class="signature-image" src="../lib.signature/<?php echo $val2['acc_ktsk']; ?>/signature.png?_=<?php echo $filetime; ?>" width="80" height="80" />
							<?php
							}
						}
						?>
						</div>
					</td>
					</tr>
				<?php
				}

				foreach($anoterProjects as $indeksArrayProyek => $val2)
				{
					$no++;
				?>
					<tr>
						<td align="right"><?php echo $no; ?></td>
						<td><?php echo $val2->getNama();?></td>
						<td><?php echo $val2->getKodeLokasi();?></td>
						<?php
						$totalBaris = 0;
						foreach ($arrayHari as $indeksArrayHari => $val) {
							$infoTanggal = $arrayHari[$indeksArrayHari];
							$class = CommonUtil::getCalendarClass($infoTanggal);
							if (!isset($totalTanggal[$indeksArrayHari])) {
								$totalTanggal[$indeksArrayHari] = 0;
							}
						?>
							<td class="<?php echo trim($class); ?> day"></td>
						<?php
						}
						if ($totalBaris > $pembagi) {
							$pembagi = $totalBaris;
						}
						
						?>
						<td align="center"></td>
						<td align="center"></td>
						<td align="center"></td>
					</tr>
				<?php
				}

				?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td>KETERANGAN KEGIATAN</td>
					<td></td>
					<?php
					$totalBaris = 0;
					foreach ($arrayHari as $indeksArrayHari => $val) {
						$infoTanggal = $arrayHari[$indeksArrayHari];
						$class = CommonUtil::getCalendarClass($infoTanggal);

						if((!isset($infoTanggal['lambang']) || empty($infoTanggal['lambang'])) && stripos($class, 'leave') !== false)
						{
							$infoTanggal['lambang'] = 'C';
						}

						if (in_array($indeksArrayHari, $perjalanan_dinas)) {
							$class .= " travel";
						?>
							<td class="<?php echo trim($class); ?> day">D</td>
						<?php
						} else {
						?>
							<td class="<?php echo trim($class); ?> day"><?php echo $infoTanggal['lambang']; ?></td>
					<?php
						}
					}
					if ($totalBaris > $pembagi) {
						$pembagi = $totalBaris;
					}
					?>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
				</tr>


				<tr>
					<td colspan="3">Total</td>
					<?php
					$totalBaris = 0;
					foreach ($arrayHari as $indeksArrayHari => $val) {
						$infoTanggal = $arrayHari[$indeksArrayHari];
						$class = CommonUtil::getCalendarClass($infoTanggal);
					?>
						<td class="<?php echo trim($class); ?> day"><?php
						if (isset($arrayBukuHarian[$indeksArrayHari]) || CommonUtil::isTrue($arrayHari[$indeksArrayHari]['cuti_dibayar'])) // && !@$arrayHari[$indeksArrayHari]['akhir_pekan'] && !@$arrayHari[$indeksArrayHari]['tanggal_merah'])
						{
							echo isset($totalTanggal[$indeksArrayHari])?$totalTanggal[$indeksArrayHari]:"";
							$totalBaris += $totalTanggal[$indeksArrayHari];
						} else {
							echo "0";
						}
						?></td>
					<?php
					}
					if ($totalBaris > $pembagi) {
						$pembagi = $totalBaris;
					}
					?>
					<td align="center"><?php echo number_format($totalBaris, 2); ?></td>
					<td align="center"><?php echo number_format($totalBaris / $pembagi, 2); ?></td>
					<td align="center"></td>
				</tr>
			</tfoot>
		</table>
		<p>&nbsp;</p>
		<table class="time-sheet-noborder" width="100%">
			<tr>
				<td rowspan="2" valign="top">
					<p>Keterangan : </p>
					<ol>
						<li>Jumlah maks proporsional MM per orang adalah 1 MM</li>
						<li>hari Sabtu, Minggu dan hari libur diberi warna abu-abu</li>
						
					</ol>
				</td>
				<td width="200" align="center">
					<div style="height: 80px; overflow:visible" class="signature-container">
					<?php
					$signaturePath2 = dirname(dirname(__FILE__)) . "/lib.signature/supervisor/$supervisorId/signature.png";
					$signaturePath2 = FileUtil::fixFilePath($signaturePath2);
					if(file_exists($signaturePath2))
					{
						$filetime = filemtime($signaturePath2);
						?><img class="signature-image" src="../lib.signature/supervisor/<?php echo $supervisorId; ?>/signature.png?_=<?php echo $filetime; ?>" width="80" height="80" /><?php
					}
					?>
					</div>
					<div class="supervisor-sign-text">
						(<?php echo $supervisor->getNama(); ?>)</div>
				</td>
			</tr>
			<tr>
				<td align="center"></td>
			</tr>
		</table>

	</div>
		<?php
        if($appConfig->getSite()->getSignatureImageTransparent())
        {
        ?>
        <script type="text/javascript" src="<?php echo $baseAssetsUrl;?><?php echo $themePath;?>js/transparent-image.min.js"></script>
        <?php
        }
        ?>
		<?php
        if($appConfig->getSite()->getSignatureImageMonochrome())
        {
        ?>
        <style>
            .signature-container
            {
                filter: saturate(0%) contrast(104%);
            }
        </style>
        <?php
        }
        ?>
<?php
} else {
?>
	<div class="warning">Tidak ada time sheet untuk periode ini. <a href="<?php echo basename($_SERVER['PHP_SELF']); ?>">Klik di sini untuk kembali</a>.</div>
<?php
}
?>
<?php
}

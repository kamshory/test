<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Lembar Waktu Kerja</title>
	<link rel="icon" type="image/png" sizes="96x96" href="../favicon-96x96.png">
	<script type="text/javascript" src="../lib.assets/jquery/jquery-1.12.4.min.js"></script>
</head>
<style type="text/css">
	body {
		margin: 0;
		padding: 0;
		font-size: 11px;
		font-family: Tahoma, Geneva, sans-serif;
	}

	.supervisor-sign-text {
		white-space: nowrap;
	}

	.time-sheet {
		border-collapse: collapse;
	}

	.time-sheet td {
		padding: 3px 3px;
		font-size: 11px;
	}

	.time-sheet thead td table td {
		padding: 0;
		font-weight: normal;
	}

	.day {
		text-align: center;
	}

	.weekend {
		background-color: #B9B9B9;
	}

	.dayoff {
		background-color: #F60;
	}

	.travel {
		background-color: #03F !important;
	}

	tfoot .leave {
		background-color: #EE0000;
	}

	.table-scroll-horizontal {
		overflow-x: auto;
		position: relative;
	}

	.table-scroll-horizontal h1 {
		font-weight: normal;
		font-size: 16px;
		text-align: center;
		margin: 4px;
		padding: 0px;
	}
</style>

<body>
	<?php
	include_once __DIR__ . "/time-sheet-core.php";
	?>
</body>

</html>
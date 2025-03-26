<?php
use MagicObject\Request\InputPost;
use Sipro\Entity\Data\BukuHarian;

require_once dirname(__DIR__) . "/inc.app/auth-supervisor-no-form.php";

$inputPost = new InputPost();
error_log($inputPost);
$bukuHarianId = $inputPost->getBukuHarianId();
$id = $inputPost->getId();
$value = $inputPost->getValue();
if($id < 0)
{
	$id = 0;
}
if($id > 23)
{
	$id = 23;
}
$field = sprintf("c_%02d", $id);
$arr = array(
	'cerah'       => 1, 
	'berawan'     => 2, 
	'hujan'       => 3, 
	'hujan-lebat' => 4
);
$val = isset($arr[$value]) ? $arr[$value] : null;
$bukuHarian = new BukuHarian(null, $database);
try
{
	$bukuHarian
		->setBukuHarianId($bukuHarianId)
		->set($field, $val)
		->update()
	;
}
catch(Exception $e)
{
	error_log($e->getMessage());
}
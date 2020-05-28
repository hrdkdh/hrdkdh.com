<?
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

$root=".";
include_once("_common.php");
include_once("_class.php");
$class=new recorder;


if ($_GET["pagerNo"]) {
	$data=$class->GetInfo($_GET["pagerNo"]);
	$results=json_encode($data, JSON_UNESCAPED_UNICODE);
	echo $results;
}
?>
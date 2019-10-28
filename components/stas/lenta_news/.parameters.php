<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

if(strlen($arCurrentValues["SITE"]) > 0)
{
	$url_default = "http://".$arCurrentValues["SITE"];

	$port = intval($arCurrentValues["PORT"]);
	if($port > 0 && $port != 80)
		$url_default .= ":".$port;

	if(strlen($arCurrentValues["PATH"]) > 0)
		$url_default .= "/".ltrim($arCurrentValues["PATH"], "/");

	if(strlen($arCurrentValues["QUERY_STR"]) > 0)
		$url_default .= "?".ltrim($arCurrentValues["QUERY_STR"], "?");
}
else
{
	$url_default = "";
}

$arComponentParameters = array(
	"PARAMETERS" => array(
		"URL" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("LENTA_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => $url_default,
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
	),
);
?>

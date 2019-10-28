<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!CModule::IncludeModule("iblock"))
{
	ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
	return;
}
//получение параметров
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;

if (isset($arParams["URL"]))
{
	$parsedUrl = parse_url($arParams["URL"]);
	if (is_array($parsedUrl))
	{
		if (isset($parsedUrl["scheme"]))
			$arParams["SITE"] = $parsedUrl["scheme"]."://".$parsedUrl["host"];
		else
			$arParams["SITE"] = $parsedUrl["host"];
		
		if (isset($parsedUrl["port"]))
			$arParams["PORT"] = $parsedUrl["port"];
		else
			$arParams["PORT"] = ($parsedUrl["scheme"] == "https"? 443 : 80);

		$arParams["PATH"] = $parsedUrl["path"];
		$arParams["QUERY_STR"] = $parsedUrl["query"];
	}
}
else
{
	$arParams["SITE"] = trim($arParams["SITE"]);
	$arParams["PORT"] = intval($arParams["PORT"]);
	$arParams["PATH"] = trim($arParams["PATH"]);
	$arParams["QUERY_STR"] = trim($arParams["QUERY_STR"]);
}
//кэширование
if($this->StartResultCache())
{
	//получаем RSS
	$arResult = CIBlockRSS::GetNewsEx($arParams["SITE"], $arParams["PORT"], $arParams["PATH"], $arParams["QUERY_STR"]);
	$arResult = CIBlockRSS::FormatArray($arResult);
	if (
		$arParams["NUM_NEWS"]>0
		&& !empty($arResult["item"])
		&& is_array($arResult["item"])
	)
	{
		while (count($arResult["item"]) > $arParams["NUM_NEWS"])
			array_pop($arResult["item"]);
	}
	//получаем 100 самых популярных слов
	$url  = 'https://ru.wiktionary.org/wiki/%D0%9F%D1%80%D0%B8%D0%BB%D0%BE%D0%B6%D0%B5%D0%BD%D0%B8%D0%B5:%D0%A1%D0%BF%D0%B8%D1%81%D0%BE%D0%BA_%D1%87%D0%B0%D1%81%D1%82%D0%BE%D1%82%D0%BD%D0%BE%D1%81%D1%82%D0%B8_%D0%BF%D0%BE_%D0%9D%D0%9A%D0%A0%D0%AF';
	$file = file_get_contents($url);
	$pattern = '#<div style="-moz-column-count: 4; -webkit-column-count: 4; column-count: 4;">.+?</div>#s';
	preg_match($pattern, $file, $matches);
	preg_match_all('#<li>.+</li>#', $matches[0], $matches);
	$arResult['words'] = array_flip(explode(',', strip_tags(implode(',', $matches[0]))));
	//создаём массив для новостей
	$arr = [];
	//собираем новости в массив
	foreach($arResult["item"] as $arItem):
		$arItem["description"] = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', ' ', mb_strtolower($arItem["description"])); 
		$arr2 = explode(' ', $arItem["description"]);
		$arr = array_merge($arr, $arr2);
	endforeach;
	//высчитываем самые популярные слова и выкидываем оттуда входящие в топ 100
	$arr = array_count_values($arr);
	arsort($arr);
	$arResult['result'] = array_slice(array_diff_key($arr, $arResult['words']),1,16);
	//подключаем шаблон
	$this->IncludeComponentTemplate();
}
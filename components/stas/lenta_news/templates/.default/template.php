<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->addExternalJS("/local/jquery.js");
$this->addExternalJS("/local/app.js");
$this->addExternalCss("/local/bootstrap.min.css");
$this->setFrameMode(true);
?>
<div class="container">
<h2>15 наиболее часто встречающихся слов</h2>
<div class="lenta_words jumbotron">
	<?foreach ($arResult['result'] as $key => $value) {
		if (!next($arResult['result']))
			echo $key . ".";
		else
			echo $key . ', ';
	}?>
</div>
<?
//пагинация
$data = $arResult['item'];   
$currentPage = trim($_REQUEST[page]);   
$perPage = 5;
$numPages = ceil(count($data) / $perPage);   
if(!$currentPage || $currentPage > $numPages)   
    $currentPage = 0;   
$start = $currentPage * $perPage;   
$end = ($currentPage * $perPage) + $perPage;   
//получение нужных новостей
foreach($data as $key => $val)   
{   
    if($key >= $start && $key < $end)   
        $pagedData[] = $data[$key];   
}?>
<div class="lenta_news">
	<h3><?=$arResult["title"]?></h3>
	<?if(is_array($arResult["item"])):?>
		<?foreach($pagedData as $arItem):?>
			<div class="lenta_news_item">   
			    <?if(strlen($arItem["enclosure"]["url"])>0):?>
					<img src="<?=$arItem["enclosure"]["url"]?>" alt="<?=$arItem["enclosure"]["url"]?>" class="rounded mx-auto d-block">
				<?endif;?>
				<?if(strlen($arItem["pubDate"])>0):?>
					<h6><?=CIBlockRSS::XMLDate2Dec($arItem["pubDate"], FORMAT_DATE)?></h6>
				<?endif;?>
				<?if(strlen($arItem["link"])>0):?>
					<h5><a href="<?=$arItem["link"]?>"><?=$arItem["title"]?></a></h5>
				<?else:?>
					<?=$arItem["title"]?>
				<?endif;?>
				<p>
				<?=$arItem["description"];?>
				</p>
			</div>
		<?endforeach;?>
	<?endif;?>
	<?if($currentPage > 0 && $currentPage < $numPages):?>
		<button class="change_page btn btn-primary" id="prev_page" data-url="page=<?=$currentPage - 1?>">Предыдущая страница</button>
	<?endif;?>
	<?if($numPages > $currentPage && ($currentPage + 1) < $numPages):?>   
		<button class="change_page btn btn-success" id="next_page" data-url="page=<?=$currentPage + 1?>">Следующая страница</button>
	<?endif;?>
</div>
</div>
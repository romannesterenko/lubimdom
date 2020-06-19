<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="basket_props_block" id="bx_basket_div_<?=$arResult["ID"];?>" style="display: none;">
	<?if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])){
		foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
			<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
			<?if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
				unset($arResult['PRODUCT_PROPERTIES'][$propID]);
		}
	}
	$arResult["EMPTY_PROPS_JS"]="Y";
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if (!$emptyProductProperties){
		$arResult["EMPTY_PROPS_JS"]="N";?>
		<div class="wrapper">
			<table>
				<?foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
					<tr>
						<td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
						<td>
							<?if('L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']){
								foreach($propInfo['VALUES'] as $valueID => $value){?>
									<label>
										<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
									</label>
								<?}
							}else{?>
								<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]">
									<?foreach($propInfo['VALUES'] as $valueID => $value){?>
										<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
									<?}?>
								</select>
							<?}?>
						</td>
					</tr>
				<?}?>
			</table>
		</div>
	<?}?>
</div>
<?
$this->setFrameMode(true);
use \Bitrix\Main\Localization\Loc;
$currencyList = '';
if (!empty($arResult['CURRENCIES'])){
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'STORES' => array(
		"USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
		"SCHEDULE" => $arParams["SCHEDULE"],
		"USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
		"MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
		"ELEMENT_ID" => $arResult["ID"],
		"STORE_PATH"  =>  $arParams["STORE_PATH"],
		"MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
		"MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
		"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
		"SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
		"SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
		"USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
		"USER_FIELDS" => $arParams['USER_FIELDS'],
		"FIELDS" => $arParams['FIELDS'],
		"STORES" => $arParams['STORES'] = array_diff($arParams['STORES'], array('')),
	)
);
unset($currencyList, $templateLibrary);


$arSkuTemplate = array();
if (!empty($arResult['SKU_PROPS'])){
	$arSkuTemplate=CMax::GetSKUPropsArray($arResult['SKU_PROPS'], $arResult["SKU_IBLOCK_ID"], "list", $arParams["OFFER_HIDE_NAME_PROPS"]);
	// print_r($arResult['SKU_PROPS']);
}
$strMainID = $this->GetEditAreaId($arResult['ID']);
$item_id = $arResult["ID"];

$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$arResult["strMainID"] = $this->GetEditAreaId($arResult['ID'])."f";
$arItemIDs=CMax::GetItemsIDs($arResult, "Y");
$totalCount = CMax::GetTotalCount($arResult, $arParams);

// $arQuantityData = CMax::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], "Y");
$arQuantityData = CMax::GetQuantityArray($totalCount, array('ID' => $item_id), 'N', ($arResult["OFFERS"] || $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || !$arResult['STORES_COUNT'] ? false : true));

$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
$useStores = $arParams["USE_STORE"] == "Y" && $arResult["STORES_COUNT"] && $arQuantityData["RIGHTS"]["SHOW_QUANTITY"];
$showCustomOffer=(($arResult['OFFERS'] && $arParams["TYPE_SKU"] !="N") ? true : false);
$bUseSkuProps = ($arResult["OFFERS"] && !empty($arResult['OFFERS_PROP']));
if($showCustomOffer){
	$templateData['JS_OBJ'] = $strObName;
}
$strMeasure='';
$arAddToBasketData = array();
if($arResult["OFFERS"]){
	$strMeasure=$arResult["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
	$templateData["STORES"]["OFFERS"]="Y";
	foreach($arResult["OFFERS"] as $arOffer){
		$templateData["STORES"]["OFFERS_ID"][]=$arOffer["ID"];
	}
}else{
	if (($arParams["SHOW_MEASURE"]=="Y")&&($arResult["CATALOG_MEASURE"])){
		$arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arResult["CATALOG_MEASURE"]), false, false, array())->GetNext();
		$strMeasure=$arMeasure["SYMBOL_RUS"];
	}
	$arAddToBasketData = CMax::GetAddToBasketArray($arResult, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'btn-lg no-icons', $arParams);
}
$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);

// save item viewed
$arFirstPhoto = reset($arResult['MORE_PHOTO']);
$arItemPrices = $arResult['MIN_PRICE'];
if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX'])
{
	$rangSelected = $arResult['ITEM_QUANTITY_RANGE_SELECTED'];
	$priceSelected = $arResult['ITEM_PRICE_SELECTED'];
	if(isset($arResult['FIX_PRICE_MATRIX']) && $arResult['FIX_PRICE_MATRIX'])
	{
		$rangSelected = $arResult['FIX_PRICE_MATRIX']['RANGE_SELECT'];
		$priceSelected = $arResult['FIX_PRICE_MATRIX']['PRICE_SELECT'];
	}
	$arItemPrices = $arResult['ITEM_PRICES'][$priceSelected];
	$arItemPrices['VALUE'] = $arItemPrices['BASE_PRICE'];
	$arItemPrices['PRINT_VALUE'] = \Aspro\Functions\CAsproMaxItem::getCurrentPrice('BASE_PRICE', $arItemPrices);
	$arItemPrices['DISCOUNT_VALUE'] = $arItemPrices['PRICE'];
	$arItemPrices['PRINT_DISCOUNT_VALUE'] = \Aspro\Functions\CAsproMaxItem::getCurrentPrice('PRICE', $arItemPrices);
}
$arViewedData = array(
	'PRODUCT_ID' => $arResult['ID'],
	'IBLOCK_ID' => $arResult['IBLOCK_ID'],
	'NAME' => $arResult['NAME'],
	'DETAIL_PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
	'PICTURE_ID' => $arResult['PREVIEW_PICTURE'] ? $arResult['PREVIEW_PICTURE']['ID'] : ($arFirstPhoto ? $arFirstPhoto['ID'] : false),
	'CATALOG_MEASURE_NAME' => $arResult['CATALOG_MEASURE_NAME'],
	'MIN_PRICE' => $arItemPrices,
	'CAN_BUY' => $arResult['CAN_BUY'] ? 'Y' : 'N',
	'IS_OFFER' => 'N',
	'WITH_OFFERS' => $arResult['OFFERS'] ? 'Y' : 'N',
);
$elementName = ((isset($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arResult['NAME']);
?>

<div class="form">
	<div class="form_head">
		<h2><a href="<?=$arResult['DETAIL_PAGE_URL'];?>" class="dark_link"><?=$elementName;?></a></h2>

		<div class="flexbox flexbox--row align-items-center justify-content-between flex-wrap">
			<div class="col-auto">
				<div class="product-info-headnote__inner">
					<?//\Aspro\Functions\CAsproMaxItem::showDelayCompareBtn($arParams, $arResult, $arAddToBasketData, $totalCount, $bUseSkuProps, 'list static', false, false, '_small', $currentSKUID, $currentSKUIBlock);?>
					<?if($arParams["SHOW_RATING"] == "Y"):?>
						<div class="product-info-headnote__rating">
							<?$frame = $this->createFrame('dv_'.$arResult["ID"])->begin('');?>
								<div class="rating">
									<?
									global $arTheme;
									if($arParams['REVIEWS_VIEW'] == 'EXTENDED'):?>
										<div class="blog-info__rating--top-info">
											<div class="votes_block nstar ">
												<div class="ratings">
													<?$message = $arResult['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? GetMessage('VOTES_RESULT', array('#VALUE#' => $arResult['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'])) : GetMessage('VOTES_RESULT_NONE')?>
													<div class="inner_rating" title="<?=$message?>">
														<?for($i=1;$i<=5;$i++):?>
															<div class="item-rating <?=$i<=$arResult['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ? 'filed' : ''?>"><?=CMax::showIconSvg("star", SITE_TEMPLATE_PATH."/images/svg/catalog/star_small.svg");?></div>
														<?endfor;?>
													</div>
												</div>
											</div>
											<?if($arResult['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']):?>
												<span class="font_sxs"><?=$arResult['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']?></span>
											<?endif;?>
										</div>
									<?else:?>
										<?$APPLICATION->IncludeComponent(
											"bitrix:iblock.vote",
											"element_rating",
											Array(
												"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
												"IBLOCK_ID" => $arResult["IBLOCK_ID"],
												"ELEMENT_ID" => $arResult["ID"],
												"MAX_VOTE" => 5,
												"VOTE_NAMES" => array(),
												"CACHE_TYPE" => $arParams["CACHE_TYPE"],
												"CACHE_TIME" => $arParams["CACHE_TIME"],
												"DISPLAY_AS_RATING" => 'vote_avg'
											),
											$component, array("HIDE_ICONS" =>"Y")
										);?>
									<?endif;?>
								</div>
							<?$frame->end();?>
						</div>
					<?endif;?>
					<div class="product-info-headnote__article">
						<div class="article muted font_xs" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue" <?if($arResult['SHOW_OFFERS_PROPS']){?>id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_ARTICLE_DIV'] ?>" style="display: none;"<?}?>>
							<span class="article__title" itemprop="name"><?=($arResult["ARTICLE"] ? $arResult["PROPERTIES"]["CML2_ARTICLE"]["NAME"].": " : "");?></span>
							<span class="article__value" itemprop="value"><?=($arResult["ARTICLE"] ? $arResult["PROPERTIES"]["CML2_ARTICLE"]["VALUE"] : "");?></span>
						</div>
					</div>
				</div>
			</div>
			<?if($arResult["BRAND_ITEM"]):?>
				<div class="col-auto">
					<div class="product-info-headnote__brand">
						<div class="brand">
							<?if(!$arResult["BRAND_ITEM"]["IMAGE"]):?>
								<a href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>" class="brand__link dark_link"><?=$arResult["BRAND_ITEM"]["NAME"]?></a>
							<?else:?>
								<a class="brand__picture" href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>">
									<img  src="<?=$arResult["BRAND_ITEM"]["IMAGE"]["src"]?>" alt="<?=$arResult["BRAND_ITEM"]["IMAGE"]["ALT"]?>" title="<?=$arResult["BRAND_ITEM"]["IMAGE"]["TITLE"]?>" />
								</a>
							<?endif;?>
						</div>
					</div>
				</div>
			<?endif;?>
		</div>
	</div>

	<script type="text/javascript">
	setViewedProduct(<?=$arResult['ID']?>, <?=CUtil::PhpToJSObject($arViewedData, false)?>);
	</script>

	<div class="fastview-product flexbox flexbox--row <?=(!$showCustomOffer ? "noffer" : "");?> <?=($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props");?>" id="<?=$arItemIDs["strMainID"];?>">
		<div class="fastview-product__image">
			<div class="product-detail-gallery product-detail-gallery--small">
				<div class="fastview-product__top-info">
					<?\Aspro\Functions\CAsproMaxItem::showStickers($arParams, $arResult, true, "");?>
					<?if($arResult['PROPERTIES']['POPUP_VIDEO']['VALUE']):?>
						<div class="video-block popup_video sm"><a class="various video_link image dark_link" href="<?=$arResult['PROPERTIES']['POPUP_VIDEO']['VALUE'];?>" title="<?=Loc::getMessage("VIDEO")?>"><span class="play text-upper font_xs"><?=Loc::getMessage("VIDEO")?></span></a></div>
					<?endif;?>
				</div>
				<div class="product-detail-gallery__wrapper">
					<?reset($arResult['MORE_PHOTO']);
					$countPhoto = count($arResult["MORE_PHOTO"]);
					$arFirstPhoto = current($arResult['MORE_PHOTO']);
					$viewImgType=$arParams["DETAIL_PICTURE_MODE"];
					$bMagnifier = ($viewImgType=="MAGNIFIER");
					?>

					<div class="product-detail-gallery__slider big<?if(!$bMagnifier):?> owl-carousel owl-theme big owl-bg-nav short-nav<?endif;?>" data-plugin-options='{"items": "1", "dots": true, "nav": true, "relatedTo": ".product-detail-gallery__slider.thmb", "loop": false}'>
						<?if($showCustomOffer && !empty($arResult['OFFERS_PROP'])){?>
							<?$alt=$arFirstPhoto["ALT"];
							$title=$arFirstPhoto["TITLE"];?>
							<div id="photo-sku" class="product-detail-gallery__item product-detail-gallery__item--big text-center">
								<?if($arFirstPhoto["BIG"]["src"]):?>
									<a href="<?=($viewImgType=="POPUP" ? $arFirstPhoto["BIG"]["src"] : "javascript:void(0)");?>" <?=($bIsOneImage ? '' : 'data-fancybox="gallery"')?> class="product-detail-gallery__link <?=($viewImgType=="POPUP" ? "popup_link fancy" : "line_link");?>" title="<?=$title;?>">
										<img id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" class="lazy product-detail-gallery__picture <?=($viewImgType=="MAGNIFIER" ? "zoom_picture" : "");?>" data-src="<?=$arFirstPhoto["SMALL"]["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arFirstPhoto["SMALL"]["src"])?>" alt="<?=$alt;?>" title="<?=$title;?>"<?//=(!$i ? ' itemprop="image"' : '')?>/>
									</a>
								<?else:?>
									<img id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" class="lazy product-detail-gallery__picture" data-src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arFirstPhoto["SRC"])?>" src="<?=$arFirstPhoto["SRC"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
								<?endif;?>
							</div>
						<?}else{
							if($arResult["MORE_PHOTO"]){?>
								<?foreach($arResult["MORE_PHOTO"] as $i => $arImage){
									if($i && $bMagnifier):?>
										<?continue;?>
									<?endif;?>
									<?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
									<?
									$alt=$arImage["ALT"];
									$title=$arImage["TITLE"];
									?>
									<div id="photo-<?=$i?>" class="product-detail-gallery__item product-detail-gallery__item--big text-center">
										<?if(!$isEmpty){?>
											<a href="<?=($viewImgType=="POPUP" ? $arImage["BIG"]["src"] : "javascript:void(0)");?>" <?=($bIsOneImage ? '' : 'data-fancybox="gallery"')?> class="product-detail-gallery__link <?=($viewImgType=="POPUP" ? "popup_link fancy" : "line_link");?>" title="<?=$title;?>">
												<img class="lazy product-detail-gallery__picture <?=($viewImgType=="MAGNIFIER" ? "zoom_picture" : "");?>" data-src="<?=$arImage["SMALL"]["src"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImage["SMALL"]["src"])?>" alt="<?=$alt;?>" title="<?=$title;?>"<?//=(!$i ? ' itemprop="image"' : '')?>/>
											</a>
										<?}else{?>
											<img class="lazy product-detail-gallery__picture" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arImage["SRC"])?>" data-src="<?=$arImage["SRC"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
										<?}?>
									</div>
								<?}?>
							<?}
						}?>
					</div>
				</div>
			</div>
		</div>
		<div class="fastview-product__info item_info">
			<div class="prices_item_block scrollbar">
				<div class="middle_info1 main_item_wrapper">
					<a href="<?=$arResult["DETAIL_PAGE_URL"];?>"></a>
					<?$frame = $this->createFrame()->begin('');?>
					<div class="prices_block">
						<div class="cost prices detail">

							<div class="price_matrix_wrapper ">
								цена от
									<div class="price font-bold font_mxs">
											<span class="values_wrapper">
												<span class="price_value"><?=$arResult['COLLECTION_PRICE']?></span>
												<span class="price_currency"> руб.</span>
											</span>
											<span class="price_measure">/шт</span>
									</div>
							</div>

						</div>

					</div>
					<div class="buy_block">
						<div class="wrapp-one-click">
								<span class="btn btn-transparent-border-color  type_block transition_bg" data-autoload-product_name="<?=$arResult['NAME']?>" data-autoload-product_id="<?=$arResult['ID']?>" data-event="jqm" data-param-form_id="SIMPLE_FORM_12" data-name="simple_form_12">
									<span>Заказать бесплатный дизайн-проект</span>
								</span>
							</div>
					</div>
					<?$frame->end();?>

					<?//delivery calculate?>
					<?if(
						(
							!$arResult["OFFERS"] &&
							$arAddToBasketData["ACTION"] == "ADD" &&
							$arAddToBasketData["CAN_BUY"]
						) ||
						(
							$arResult["OFFERS"] &&
							$arParams['TYPE_SKU'] === 'TYPE_1'
						)
					):?>
						<?=\Aspro\Functions\CAsproMax::showCalculateDeliveryBlock($arResult['ID'], $arParams);?>
					<?endif;?>

					<?//help text?>
					<?if($arResult['HELP_TEXT']):?>
						<div class="text-form">
							<div class="price_txt muted777 font_sxs muted ncolor">
								<?=CMax::showIconSvg("info_big pull-left", SITE_TEMPLATE_PATH.'/images/svg/catalog/info_big.svg', '', '', true, false);?>
								<div class="text-form-info">
									<?if(!$arResult['HELP_TEXT_FILE']):?>
										<?=$arResult['HELP_TEXT'];?>
									<?else:?>
										<?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "page",
												"AREA_FILE_SUFFIX" => "help_text",
												"EDIT_TEMPLATE" => ""
											)
										);?>
									<?endif;?>
								</div>
							</div>
						</div>
					<?endif;?>

					<?//preview_text?>
					<?if($arResult["PREVIEW_TEXT"]):?>
						<div class="preview_text font_xs muted777">
							<?=$arResult["PREVIEW_TEXT"];?>
						</div>
					<?endif;?>

					<?$boolShowOfferProps = ($arResult['OFFERS_PROPS_DISPLAY']);
					$boolShowProductProps = (isset($arResult['DISPLAY_PROPERTIES']) && !empty($arResult['DISPLAY_PROPERTIES']));?>
					<?if($boolShowProductProps || $boolShowOfferProps):?>
						<div class="props_list_wrapp">
							<div class="show_props">
								<span class="darken font_sm char_title"><span class=""><?=Loc::getMessage('CT_NAME_DOP_CHAR')?></span></span>
							</div>
							<div class="properties list">
								<div class="properties__container properties">
									<?foreach($arResult['DISPLAY_PROPERTIES'] as $arProp):?>
										<div class="properties__item properties__item--compact font_xs">
											<div class="properties__title muted properties__item--inline"><?=$arProp['NAME']?></div>
											<div class="properties__hr muted properties__item--inline">&mdash;</div>
											<div class="properties__value darken properties__item--inline">
												<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
													<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
												<?else:?>
													<?=($arProp['DISPLAY_VALUE'] ? $arProp['DISPLAY_VALUE'] : $arProp['VALUE']);?>
												<?endif;?>
											</div>
										</div>
									<?endforeach;?>
								</div>
								<div class="properties__container properties__container_js">
									<?if($arResult["OFFERS"][$arResult["OFFERS_SELECTED"]]['DISPLAY_PROPERTIES']):?>
										<?foreach($arResult["OFFERS"][$arResult["OFFERS_SELECTED"]]['DISPLAY_PROPERTIES'] as $arProp):?>
											<div class="properties__item properties__item--compact font_xs">
												<div class="properties__title muted properties__item--inline"><?=$arProp['NAME']?></div>
												<div class="properties__hr muted properties__item--inline">&mdash;</div>
												<div class="properties__value darken properties__item--inline">
													<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
														<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
													<?else:?>
														<?=($arProp['DISPLAY_VALUE'] ? $arProp['DISPLAY_VALUE'] : $arProp['VALUE']);?>
													<?endif;?>
												</div>
											</div>
										<?endforeach;?>
									<?endif;?>
								</div>
							</div>
						</div>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
	<div class="btn-wrapper"><a href="<?=$arResult['DETAIL_PAGE_URL'];?>" class="btn btn-default btn-lg round-ignore"><?=Loc::getMessage('MORE_TEXT_ITEM');?><?=CMax::showIconSvg("down", SITE_TEMPLATE_PATH.'/images/svg/catalog/arrow_quicklook.svg', '', '', true, false);?></a></div>

	<script type="text/javascript">
		BX.message({
			QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.max", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
			QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.max", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
			ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
			ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
			ONE_CLICK_BUY: '<? echo GetMessage("ONE_CLICK_BUY"); ?>',
			SITE_ID: '<? echo SITE_ID; ?>'
		})
		InitOwlSlider();
		var navs = $('#popup_iframe_wrapper .navigation-wrapper-fast-view .fast-view-nav');
		if(navs.length) {
			var ajaxData = {
				element: "<?=$arResult['ID']?>",
				iblock: "<?=$arParams['IBLOCK_ID']?>",
				section: "<?=$arResult['IBLOCK_SECTION_ID']?>",
			};
			if($('.smart-filter-filter').length && $('.smart-filter-filter').text().length) {
				try {
					var text = $('.smart-filter-filter').text().replace('var filter = ', '');
			        JSON.parse(text);
					ajaxData.filter = text;
			    } catch (e) {}
			}

			if($('.smart-filter-sort').length && $('.smart-filter-sort').text().length) {
				try {
					var text = $('.smart-filter-sort').text().replace('var filter = ', '');
			        JSON.parse(text);
					ajaxData.sort = text;
			    } catch (e) {}
			}
			navs.data('ajax', ajaxData);
		}
	</script>
</div>

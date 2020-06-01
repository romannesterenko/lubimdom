<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>
<?
global $arTheme;
$countSldes = (is_array($arResult['ITEMS'])? count($arResult['ITEMS']) : '0');
$slideshowSpeed = abs(intval($arTheme['PARTNERSBANNER_SLIDESSHOWSPEED']['VALUE']));
$animationSpeed = abs(intval($arTheme['PARTNERSBANNER_ANIMATIONSPEED']['VALUE']));
$bAnimation = (bool)$slideshowSpeed && $countSldes>6;

?>
<?if($arResult['ITEMS']):?>
	<?$bShowTopBlock = ($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']);
	$bBordered = ($arParams['BORDERED'] == 'Y');
	?>
	<div class="content_wrapper_block <?=$templateName;?>">
	<div class="maxwidth-theme only-on-front <?=($bShowTopBlock ? '' : 'no-title')?>">
		<?if($bShowTopBlock):?>
			<div class="top_block">
				<h3><?=$arParams['TITLE_BLOCK'];?></h3>
				<?if($arParams['TITLE_BLOCK_ALL']):?>
					<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
				<?endif;?>
			</div>
		<?endif;?>
		<div class="item-views brands flexslider appear-block loading_state <?=($bBordered ? 'with_border':'')?>"  data-plugin-options='{"animation": "slide", "directionNav": true, <?=($bBordered ? '"itemMargin":0,' : '"itemMargin":30,')?> "controlNav" :false, "animationLoop": true, "useCSS": true, <?=($bAnimation ? '"slideshow": true,' : '"slideshow": false,')?> <?=($slideshowSpeed >= 0 ? '"slideshowSpeed": '.$slideshowSpeed.',' : '')?> <?=($animationSpeed >= 0 ? '"animationSpeed": '.$animationSpeed.',' : '')?> <?=($bBordered ? ' "counts": [5,4,3,2,1]' : ' "counts": [6,4,3,2,1]')?>}'>
			<ul class="brands_slider slides">
				<?foreach($arResult["ITEMS"] as $arItem){?>
					<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
					?>
					<?if( is_array($arItem["PREVIEW_PICTURE"]) ){?>
						<li class="visible item pull-left text-center <?=($bBordered ? 'bordered' : '')?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
							<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
								<img class="noborder lazy" data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" src="<?=\Aspro\Functions\CAsproMax::showBlankImg($arItem["PREVIEW_PICTURE"]["SRC"]);?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
							</a>
						</li>
					<?}?>
				<?}?>
			</ul>
		</div>
	</div></div>
<?endif;?>
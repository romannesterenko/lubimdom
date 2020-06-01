<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>
	<?if(!$arParams['IS_AJAX']):?>
		<div class="content_wrapper_block <?=$templateName;?> <?=$arParams['TYPE_IMG'] == 'bg' ? 'text-inside' : ''?> <?=$arParams['TYPE_IMG'] == 'sm' ? 'with-border' : ''?> <?=$arResult['NAV_STRING'] ? '' : 'without-border'?>">
		<div class="maxwidth-theme only-on-front">
		<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
			<?if($arParams['INCLUDE_FILE']):?>
				<div class="with-text-block-wrapper">
					<div class="row">
						<div class="col-md-3">
							<?if($arParams['TITLE_BLOCK'] || $arParams['TITLE_BLOCK_ALL']):?>
								<h3><?=$arParams['TITLE_BLOCK'];?></h3>
								<?// intro text?>
								<?if($arParams['INCLUDE_FILE']):?>
									<div class="text_before_items font_xs">
										<?$APPLICATION->IncludeComponent(
											"bitrix:main.include",
											"",
											Array(
												"AREA_FILE_SHOW" => "file",
												"PATH" => SITE_DIR."include/mainpage/inc_files/".$arParams['INCLUDE_FILE'],
												"EDIT_TEMPLATE" => ""
											)
										);?>
									</div>
								<?endif;?>
								<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="btn btn-transparent-border-color btn-sm"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
							<?endif;?>
						</div>
						<div class="col-md-9">
			<?else:?>
				<div class="top_block">
					<h3><?=$arParams['TITLE_BLOCK'];?></h3>
					<a href="<?=SITE_DIR.$arParams['ALL_URL'];?>" class="pull-right font_upper muted"><?=$arParams['TITLE_BLOCK_ALL'] ;?></a>
				</div>
			<?endif;?>
		<?endif;?>
		<div class="item-views sales2 <?=$arParams['TYPE_IMG'];?>">
			<div class="items<?=(!$arParams['INCLUDE_FILE'] ? '' : ' list');?> s_<?=$arParams['SIZE_IN_ROW'];?>">
				<div class="row flexbox<?=($arParams['NO_MARGIN'] == 'Y' ? ' margin0' : '');?>">
	<?endif;?>
		<?$bFonImg = ($arParams['TYPE_IMG'] == 'bg');
		$col_lg = (12/$arParams['SIZE_IN_ROW']);
		//$col_md = ((int)$arParams['SIZE_IN_ROW']-1>0) ? (12/( $arParams['SIZE_IN_ROW']-1)) : 1 ;
		$col_md = (12/( $arParams['SIZE_IN_ROW']-1));
		$isLeftBlock = (isset($arParams['WITH_LEFT_BLOCK']) && $arParams['WITH_LEFT_BLOCK']=='Y') ? true: false;
		
		
		$position = ($arParams['BG_POSITION'] ? ' set-position '.$arParams['BG_POSITION'] : '');
		?>
			<?foreach($arResult['ITEMS'] as $i => $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
				// preview image
				$bImage = true;
				$imageSrc = ($arItem['FIELDS']['PREVIEW_PICTURE'] ? $arItem['FIELDS']['PREVIEW_PICTURE']['SRC'] : SITE_TEMPLATE_PATH.'/images/svg/noimage_content.svg');

				// show active date period
				$bActiveDate = strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']) || ($arItem['DISPLAY_ACTIVE_FROM'] && in_array('DATE_ACTIVE_FROM', $arParams['FIELD_CODE']));
				$bDiscountCounter = ($arItem['ACTIVE_TO'] && in_array('ACTIVE_TO', $arParams['FIELD_CODE']));
				?>
				<div class="item-wrapper col-lg-<?=$col_lg;?> col-md-<?=$col_md;?> col-sm-6 col-xs-6 col-xxs-12 clearfix" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?if($bFonImg):?>
					<div class="item box-shadow rounded3 darken-bg-animate lazy<?=$position;?>" <?=($bImage ? 'data-src="'.$imageSrc.'"' : '');?> style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>)">
						<?if($bDetailLink):?><a href="<?=$arItem['DETAIL_PAGE_URL']?>"></a><?endif;?>
					<?else:?>
					<div class="item<?=($arParams['FILLED'] == 'Y' ? ' bg-fill-grey' : ($arParams['TRANSPARENT'] == 'Y' ? '' : ' bg-fill-white'));?><?=($arParams['TRANSPARENT'] == 'Y' ? '' : ' box-shadow');?><?=($arParams['TYPE_IMG'] == 'sm' ? ' bordered text-center' : '');?>">
						<?if($bImage):?>
							<div class="image shine">
								<?if($bDetailLink):?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?endif;?>
									<span class="rounded<?=($arParams['TYPE_IMG'] != 'sm' ? 3 : '');?> bg-fon-img lazy<?=$position;?>" data-src="<?=$imageSrc?>" style="background-image:url(<?=\Aspro\Functions\CAsproMax::showBlankImg($imageSrc);?>)"></span>
								<?if($bDetailLink):?>
									</a>
								<?endif;?>
							</div>
						<?endif;?>
					<?endif;?>
						<div class="inner-text">
							<?// date active period?>
							<?if($bActiveDate):?>
								<div class="period-block<?=(!$bFonImg ? ' muted ncolor' : '');?> font_xs <?=($arItem['ACTIVE_TO'] ? 'red' : '');?>">
									<?=CMax::showIconSvg("sale", SITE_TEMPLATE_PATH.'/images/svg/icon_discount.svg', '', '', true, false);?>
									<?if(strlen($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE'])):?>
										<span class="date"><?=$arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></span>
									<?else:?>
										<span class="date"><?=$arItem['DISPLAY_ACTIVE_FROM']?></span>
									<?endif;?>
								</div>
							<?endif;?>

							<div class="title <?=($bFonImg ? 'font_mlg' : '');//'font_md'?>">
								<?if($bDetailLink):?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
								<?endif;?>
								<?=$arItem['NAME'];?>
								<?if($bDetailLink):?>
									</a>
								<?endif;?>
							</div>

						</div>
						<?if($arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE'] || $bDiscountCounter):?>
							<div class="info-sticker-block top">
								<?if($arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE']):?>
									<div class="sale-text font_sxs rounded2"><?=$arItem['DISPLAY_PROPERTIES']['SALE_NUMBER']['VALUE'];?></div>
								<?endif;?>
								<?if($bDiscountCounter): ?>
									<?\Aspro\Functions\CAsproMax::showDiscountCounter(0, $arItem, array(), array(), '', 'compact');?>
								<?endif;?>
							</div>
						<?endif;?>
					</div>
				</div>
			<?endforeach;?>

	<?if(!$arParams['IS_AJAX']):?>
			</div>
		</div>
	<?endif;?>
		
		<?// bottom pagination?>
		<div class="bottom_nav_wrapper">
			<div class="bottom_nav animate-load-state has-nav" <?=($arParams['IS_AJAX'] ? "style='display: none; '" : "");?> data-parent=".item-views" data-append=".items > .row">
				<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
					<?=$arResult['NAV_STRING']?>
				<?endif;?>
			</div>
		</div>

	<?if(!$arParams['IS_AJAX']):?>
		</div>
		<?if($arParams['INCLUDE_FILE']):?>
			</div></div></div>
		<?endif;?>
	</div></div>
	<?endif;?>
<?endif;?>
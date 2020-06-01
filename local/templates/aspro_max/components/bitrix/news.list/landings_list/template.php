<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>
<?if($arResult['ITEMS']):?>
	<?$i = 0;?>
	<?$bFilled = ($arParams['BG_FILLED'] == 'Y');?>
	<div class="landings-list <?=$templateName;?>">
		<?if($arParams["TITLE_BLOCK"]):?>
			<div class="landings-list__title darken font_mlg"><?=$arParams["TITLE_BLOCK"];?></div>
		<?endif;?>
		<div class="landings-list__info">
			<?$compare_field = (isset($arParams["COMPARE_FIELD"]) && $arParams["COMPARE_FIELD"] ? $arParams["COMPARE_FIELD"] : "DETAIL_PAGE_URL");
			$bProp = (isset($arParams["COMPARE_PROP"]) && $arParams["COMPARE_PROP"] == "Y");

			$textExpand = Loc::getMessage("SHOW_ALL");
			$textHide = Loc::getMessage("HIDE");
			$opened = "N";
			$classOpened = "";

			$bWithHidden = $bCheckItemActive = $bHiddenOK = false;?>

			<?foreach ($arResult['ITEMS'] as $key => $arItem) {
				++$i;
				$bHidden = ($i > $arParams["SHOW_COUNT"]);
				
				if ($bHidden) {
					$bWithHidden = true;
				}

				$url = $arItem[$compare_field];
				if ($bProp) {
					$url = $arItem["PROPERTIES"][$compare_field]["VALUE"];
				}

				if ($url) {
					$arFilterQuery = \Aspro\Functions\CAsproMax::checkActiveFilterPage($arParams["SEF_CATALOG_URL"], $url);
					$bActiveFilter = ($arFilterQuery && !in_array('clear', $arFilterQuery));
					$curDir = $APPLICATION->GetCurDir();
					$curDirDec = urldecode(str_replace(' ', '+', $curDir));
					$urlDec= urldecode($url); 
					$urlDecCP = iconv("utf-8","windows-1251", $urlDec);
					$bCurrentUrl = ($curDirDec == $urlDec) || ($curDir == $urlDec) || ($curDir == $urlDecCP);

					if ($bCurrentUrl) {
						if($bActiveFilter){
							if ($bHidden) {
								$bCheckItemActive = true;
								$textExpand = $textHide;
								$textHide = Loc::getMessage("SHOW_ALL");
								$opened = "Y";
								$classOpened = "opened";
							}

							$arResult['ITEMS'][$key]['ACTIVE'] = 'Y';
							$arResult['ITEMS'][$key]['ACTIVE_URL'] = $bCurrentUrl ? 'Y' : 'N';
						}
					}
				}
			}?>
			<?$i = 0;?>
			<div class="d-inline landings-list__info-wrapper <?=($bWithHidden ? 'last' : '');?>">
				<?foreach($arResult['ITEMS'] as $arItem):?>
					<?
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

					++$i;
					$bHidden = ($i > $arParams["SHOW_COUNT"]);

					$url = $arItem[$compare_field];
					if ($bProp) {
						$url = $arItem["PROPERTIES"][$compare_field]["VALUE"];
					}
					?>
					<?if ($bHidden && !$bHiddenOK):?>
						<?$bHiddenOK = true;?>
						<div class="landings-list__item-more <?=(!$bCheckItemActive ? 'hidden' : 'd-inline');?>">
					<?endif?>
					<div class="landings-list__item font_xs" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<div>
							<?if(strlen($url)):?>
								<?if($arItem['ACTIVE_URL'] == 'Y'):?>
									<span class="landings-list__name rounded3 landings-list__item--active <?=($bActiveFilter ? 'landings-list__item--reset' : '');?>"><span><?=$arItem['NAME']?></span>
										<?if($arItem['ACTIVE']):?>
											<span class="landings-list__clear-filter colored_theme_bg_hovered_hover" title="<?=Loc::getMessage('RESET_LANDING');?>">
												<?=CMax::showIconSvg("delete_filter", SITE_TEMPLATE_PATH.'/images/svg/catalog/cancelfilter.svg', '', '', false, false);?>
											</span>
										<?endif;?>
									</span>
								<?else:?>
									<a class="landings-list__name<?=($bFilled ? ' landings-list__item--filled-bg box-shadow-sm' : ' landings-list__item--hover-bg');?> rounded3" href="<?=$url?>"><span><?=$arItem['NAME']?></span></a>
								<?endif;?>
							<?else:?>
								<span class="landings-list__name<?=($bFilled ? ' landings-list__item--filled-bg box-shadow-sm' : ' landings-list__item--hover-bg');?> rounded3"><span><?=$arItem['NAME']?></span></span>
							<?endif?>
						</div>
					</div>
				<?endforeach?>
			</div>
			<?if($bHidden):?>
				</div>
				<div class="landings-list__item font_xs">
					<span class="landings-list__name landings-list__item--js-more colored_theme_text_with_hover <?=$classOpened;?>" data-opened="<?=$opened;?>">
						<span data-opened="<?=$opened;?>" data-text="<?=$textHide;?>"><?=$textExpand;?></span><?=CMax::showIconSvg("wish ncolor", SITE_TEMPLATE_PATH."/images/svg/arrow_showmoretags.svg");?>
					</span>
				</div>
			<?endif?>
		</div>
	</div>
<?endif?>
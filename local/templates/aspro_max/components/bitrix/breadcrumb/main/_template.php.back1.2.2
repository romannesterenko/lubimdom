<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$strReturn = '';
if($arResult){
	\Bitrix\Main\Loader::includeModule("iblock");
	global $NextSectionID;
	$cnt = count($arResult);
	$lastindex = $cnt - 1;
	if(\Bitrix\Main\Loader::includeModule('aspro.max'))
	{
		global $arTheme;
		$bShowCatalogSubsections = ($arTheme["SHOW_BREADCRUMBS_CATALOG_SUBSECTIONS"]["VALUE"] == "Y");
	}

	for($index = 0; $index < $cnt; ++$index){
		$arSubSections = array();
		$arItem = $arResult[$index];
		$title = htmlspecialcharsex($arItem["TITLE"]);
		$bLast = $index == $lastindex;
		if($NextSectionID && $bShowCatalogSubsections){
			$arSubSections = CMax::getChainNeighbors($NextSectionID, $arItem['LINK']);
		}
		if($index){
			$strReturn .= '<span class="breadcrumbs__separator">&mdash;</span>';
		}
		if($arItem["LINK"] <> "" && $arItem['LINK'] != GetPagePath() && $arItem['LINK']."index.php" != GetPagePath() || $arSubSections){
			$strReturn .= '<div class="breadcrumbs__item'.($arSubSections ? ' breadcrumbs__item--with-dropdown colored_theme_hover_bg-block' : '').($bLast ? ' cat_last' : '').'" id="bx_breadcrumb_'.$index.'" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
			if($arSubSections){
				if($index == ($cnt-1)):
					$strReturn .= '<link href="'.GetPagePath().'" itemprop="item" /><span>';
				else:
					$strReturn .= '<a class="breadcrumbs__link" href="'.$arItem["LINK"].'" itemprop="item">';
				endif;
				$strReturn .=($arSubSections ? '<span itemprop="name" class="breadcrumbs__item-name font_xs">'.$title.'</span><span class="breadcrumbs__arrow-down '.(!$bLast ? 'colored_theme_hover_bg-el-svg' : '').'">'.CMax::showIconSvg("arrow", SITE_TEMPLATE_PATH."/images/svg/trianglearrow_down.svg").'</span>' : '<span>'.$title.'</span>');
				$strReturn .= '<meta itemprop="position" content="'.($index + 1).'">';
				if($index == ($cnt-1)):
					$strReturn .= '</span>';
				else:
					$strReturn .= '</a>';
				endif;
				$strReturn .= '<div class="breadcrumbs__dropdown-wrapper"><div class="breadcrumbs__dropdown rounded3">';
					foreach($arSubSections as $arSubSection){
						$strReturn .= '<a class="breadcrumbs__dropdown-item dark_link font_xs" href="'.$arSubSection["LINK"].'">'.$arSubSection["NAME"].'</a>';
					}
				$strReturn .= '</div></div>';
			}
			else{
				$strReturn .= '<a class="breadcrumbs__link" href="'.$arItem["LINK"].'" title="'.$title.'" itemprop="item"><span itemprop="name" class="breadcrumbs__item-name font_xs">'.$title.'</span><meta itemprop="position" content="'.($index + 1).'"></a>';
			}
			$strReturn .= '</div>';
		}
		else{
			$strReturn .= '<span class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><link href="'.GetPagePath().'" itemprop="item" /><span><span itemprop="name" class="breadcrumbs__item-name font_xs">'.$title.'</span><meta itemprop="position" content="'.($index + 1).'"></span></span>';
		}
	}

	return '<div class="breadcrumbs" itemscope="" itemtype="http://schema.org/BreadcrumbList">'.$strReturn.'</div>';
	//return $strReturn;
}
else{
	return $strReturn;
}
?>
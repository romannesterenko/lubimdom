<?
// get all subsections of PARENT_SECTION or root sections
/*global $arTheme, $arRegion;

$arSectionsFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y', '<=DEPTH_LEVEL' => 1);

// current section
$arParentSection = array();
if($arParams['PARENT_SECTION'] || $arParams['PARENT_SECTION_CODE']){
	$arSectionFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);

	if($arParams['PARENT_SECTION']){
		$arSectionFilter['ID'] = $arParams['PARENT_SECTION'];
	}
	elseif($arParams['PARENT_SECTION_CODE']){
		$arSectionFilter['CODE'] = $arParams['PARENT_SECTION_CODE'];
	}

	if($arParentSection = CMaxCache::CIBLockSection_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arSectionFilter, false, array('ID', 'IBLOCK_ID', 'DEPTH_LEVEL'))){
		$arSectionsFilter['SECTION_ID'] = $arParentSection['ID'];
		$arSectionsFilter['<=DEPTH_LEVEL'] = $arParentSection['DEPTH_LEVEL'] + 1;
	}
}

// current sub sections
$arResult['SECTIONS'] = CMaxCache::CIBlockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), $arSectionsFilter, false, array('ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL', 'PICTURE', 'UF_ICON', 'UF_BACKGROUND', 'DETAIL_PICTURE', 'UF_TOP_SEO', 'DESCRIPTION'));

if($arResult['SECTIONS']){
	// childs sections of current sub sections
	if($arParams['SHOW_CHILD_SECTIONS'] === 'Y'){
		$arChildsSectionsFilter = $arSectionsFilter;
		$arChildsSectionsFilter['>DEPTH_LEVEL'] = $arChildsSectionsFilter['<=DEPTH_LEVEL'];
		$arChildsSectionsFilter['<=DEPTH_LEVEL'] = $arChildsSectionsFilter['<=DEPTH_LEVEL'] + 1;
		$arChildsSections = CMaxCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('IBLOCK_SECTION_ID'), 'MULTI' => 'Y')), $arChildsSectionsFilter, false, array('ID', 'NAME', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL'), false);

		foreach($arChildsSections as $SID => $arSections){
			$arResult['SECTIONS'][$SID]['CHILD'] = $arSections;
		}
	}

	// elements filter
	$arItemsFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y', 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');

	// add filter elements by region
	if($arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arRegion && $arTheme['USE_REGIONALITY']['DEPENDENT_PARAMS']['REGIONALITY_FILTER_ITEM']['VALUE'] === 'Y'){
		$arItemsFilter['PROPERTY_LINK_REGION'] = $arRegion['ID'];
	}

	foreach($arResult['SECTIONS'] as $SID => &$arSection){
		$arItemsFilter['SECTION_ID'] = $arSection['ID'];

		// elements count
		$arSection['ELEMENTS_COUNT'] = CMaxCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arItemsFilter, array());

		// remove empty sections in region
		if(!$arSection['ELEMENTS_COUNT'] && $arTheme['USE_REGIONALITY']['VALUE'] === 'Y' && $arRegion && $arTheme['SHOW_SECTIONS_REGION']['VALUE'] == 'Y'){
			unset($arResult['SECTIONS'][$SID]);
			continue;
		}

		if($arSection['CHILD']){
			foreach($arSection['CHILD'] as $cSID => $arChildSection){
				$arItemsFilter['SECTION_ID'] = $arChildSection['ID'];
				$cntItems = CMaxCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arItemsFilter, array());
				if(!$cntItems){
					unset($arSection['CHILD'][$cSID]);
				}
			}

			if(!$arSection['CHILD']){
				unset($arSection['CHILD']);
			}
		}

		// elements if no childs sections
		if(!isset($arParams['SHOW_ELEMENTS_IN_LAST_SECTION']) || ($arParams['SHOW_CHILD_SECTIONS'] === 'Y' && $arParams['SHOW_ELEMENTS_IN_LAST_SECTION'] === 'Y' && !$arSection['CHILD'] && $arSection['ELEMENTS_COUNT'])){
			$arSection['CHILD'] = CMaxCache::CIBlockElement_GetList(array('CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), $arItemsFilter, false, false, array('ID', 'NAME', 'DETAIL_PAGE_URL'));
		}

		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection['IBLOCK_ID'], $arSection['ID']);
		$arSection['IPROPERTY_VALUES'] = $ipropValues->getValues();
		CMax::getFieldImageData($arSection, array('PICTURE'), 'SECTION');
	}
	unset($arSection);
}*/
?>
<?
// get all subsections of PARENT_SECTION or root sections
$arSectionsFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y');
$start_level = $arParams['DEPTH_LEVEL'];
$end_level = $arParams['DEPTH_LEVEL']+1;

if($arParams['PARENT_SECTION'])
{
	$arParentSection = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N', "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), array('ID' => $arParams['PARENT_SECTION']), false, array('ID', 'IBLOCK_ID', 'LEFT_MARGIN', 'RIGHT_MARGIN'));

	$arSectionsFilter = array_merge($arSectionsFilter, array('>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'], '<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'], '>DEPTH_LEVEL' => '1'));
	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
	{
		$arSectionsFilter['INCLUDE_SUBSECTIONS'] = 'Y';
		$arSectionsFilter['<=DEPTH_LEVEL'] = $end_level;
	}
}
else
{
	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
		$arSectionsFilter['<=DEPTH_LEVEL'] = $end_level;
	else
		$arSectionsFilter['DEPTH_LEVEL'] = '1';
}
$arResult['SECTIONS'] = CMaxCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N', "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), $arSectionsFilter, false, array('ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL', 'IBLOCK_SECTION_ID', 'SECTION_PAGE_URL', 'PICTURE', 'DETAIL_PICTURE', 'UF_TOP_SEO', 'DESCRIPTION'));

//echo '<pre>',var_dump($arResult['SECTIONS'] ),'</pre>';

if($arResult['SECTIONS'])
{
	$arSections = array();
	foreach($arResult['SECTIONS'] as $key => $arSection)
	{
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arSection['IBLOCK_ID'], $arSection['ID']);
		$arResult['SECTIONS'][$key]['IPROPERTY_VALUES'] = $ipropValues->getValues();
		CMax::getFieldImageData($arResult['SECTIONS'][$key], array('PICTURE'), 'SECTION');
	}
	
	if($arParams['SHOW_CHILD_SECTIONS'] == 'Y')
	{
		foreach($arResult['SECTIONS'] as $arItem)
		{
			/*if( $arItem['DEPTH_LEVEL'] == $start_level )
				$arSections[$arItem['ID']] = array_merge($arSections[$arItem['ID']], $arItem);//$arSections[$arItem['ID']] = $arItem;
			elseif( $arItem['DEPTH_LEVEL'] == $end_level )
				$arSections[$arItem['IBLOCK_SECTION_ID']]['CHILD'][$arItem['ID']] = $arItem;//echo '<pre>',var_dump($arSections ),'</pre>';*/
			
			if( $arItem['DEPTH_LEVEL'] == $start_level ){
				if(!$arSections[$arItem['ID']]){
					$arSections[$arItem['ID']] = $arItem;
				}else{
					$arSections[$arItem['ID']] = array_merge($arSections[$arItem['ID']], $arItem);
				}
				
			}
			elseif( $arItem['DEPTH_LEVEL'] == $end_level ){
				$arSections[$arItem['IBLOCK_SECTION_ID']]['CHILD'][$arItem['ID']] = $arItem;//echo '<pre>',var_dump($arSections ),'</pre>';
			}
		}

		// fill elements
		foreach($arSections as $key => $arSection)
		{
			$arItems = CMaxCache::CIBlockElement_GetList(array('SORT' => 'ASC', 'ID' => 'DESC', 'CACHE' => array('TAG' => CMaxCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), "CACHE_GROUP" => array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()))), array_merge($arSectionsFilter, array('SECTION_ID' => $arSection['ID'])), false, false, array('ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PAGE_URL'));
			if($arItems)
			{
				if(!$arSections[$key]['CHILD'])
					$arSections[$key]['CHILD'] = $arItems;
				else
					$arSections[$key]['CHILD'] = array_merge($arSections[$key]['CHILD'], $arItems);
			}
		}
		$arResult['SECTIONS'] = $arSections;
	}
}
?>
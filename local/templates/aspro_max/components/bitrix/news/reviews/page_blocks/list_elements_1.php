
<?if($arParams["SHOW_ADD_REVIEW_BUTTON"] == "Y"):?>
	<div class="add_review">
		<div class="button_wrap">
			<span><span class="btn btn-default btn-lg animate-load" data-event="jqm" data-param-form_id="REVIEW" data-name="send_review"><?=(strlen($arParams["ADD_REVIEW_BUTTON"]) ? $arParams["ADD_REVIEW_BUTTON"] : GetMessage('ADD_REVIEW'))?></span></span>
		</div>
	</div>
<?endif;?>

<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"front_review", 
	array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"NEWS_COUNT" => $arParams["NEWS_COUNT"],
		"SORT_BY1" => $arParams["SORT_BY1"],
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
		"SORT_BY2"	=>	$arParams["SORT_BY2"],
		"SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
		"FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
		"PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
		"CACHE_TIME"	=>	$arParams["CACHE_TIME"],
		"CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT" => $arParams["ACTIVE_DATE_FORMAT"],
		"DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
		"SET_TITLE"	=>	$arParams["SET_TITLE"],
		"SHOW_DETAIL_LINK" => 'N',//$arParams["SHOW_DETAIL_LINK"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
		"ADD_SECTIONS_CHAIN"	=>	$arParams["ADD_SECTIONS_CHAIN"],
		"FILTER_NAME"	=>	$arParams["FILTER_NAME"],
		"HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
		"CHECK_DATES"	=>	$arParams["CHECK_DATES"],
		"PARENT_SECTION"	=>	$arResult["VARIABLES"]["SECTION_ID"],
		"PARENT_SECTION_CODE"	=>	$arResult["VARIABLES"]["SECTION_CODE"],
		"DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
		"PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
		"PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
		"DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
		"DISPLAY_NAME"	=>	$arParams["DISPLAY_NAME"],
		"DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
		"DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
		"PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
		"ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
		"USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
		"GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
		"SHOW_SECTION_PREVIEW_DESCRIPTION" => $arParams["SHOW_SECTION_PREVIEW_DESCRIPTION"],
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "front_review",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"STRICT_SECTION_CHECK" => $arParams["STRICT_SECTION_CHECK"],
		"TITLE_BLOCK" => "",
		"TITLE_BLOCK_ALL" => "",
		"SHOW_ADD_REVIEW" => "",
		"TITLE_ADD_REVIEW" => "",
		"ALL_URL" => "",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => $arParams["SHOW_404"],
		"DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
		"SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
		"MESSAGE_404" => "",
		"COMPACT" => "Y",
		"LINKED_MODE" => "Y",
		"NOT_SLIDER" => "Y",
		"STAFF_IBLOCK_ID" => $arParams["STAFF_IBLOCK_ID"],
	),
	$component
);?>

						<?CMax::checkRestartBuffer();?>
						<?IncludeTemplateLangFile(__FILE__);?>
							<?if(!$isIndex):?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									</div> <?// .maxwidth-theme?>
								<?endif;?>
								</div> <?// .container?>
							<?else:?>
								<?CMax::ShowPageType('indexblocks');?>
							<?endif;?>
							<?CMax::get_banners_position('CONTENT_BOTTOM');?>
						</div> <?// .middle?>
					<?//if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
					<?if(($isIndex && ($isShowIndexLeftBlock || $bActiveTheme)) || (!$isIndex && !$isHideLeftBlock)):?>
						</div> <?// .right_block?>
						<?if($APPLICATION->GetProperty("HIDE_LEFT_BLOCK") != "Y" && !defined("ERROR_404")):?>
							<?CMax::ShowPageType('left_block');?>
						<?endif;?>
					<?endif;?>
					</div> <?// .container_inner?>
				<?if($isIndex):?>
					</div>
				<?elseif(!$isWidePage):?>
					</div> <?// .wrapper_inner?>
				<?endif;?>
			</div> <?// #content?>
			<?CMax::get_banners_position('FOOTER');?>
		</div><?// .wrapper?>

		<footer id="footer">
			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/under_footer.php'));?>
			<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/top_footer.php'));?>
		</footer>
		<?include_once(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer_include/bottom_footer.php'));?>
        <?
        $iblock_id = 37;
        $arSelect = Array("ID", "NAME", "PROPERTY_URL", "PROPERTY_TITLE", "PROPERTY_DESCRIPTION", "PROPERTY_H1_TITLE", "PROPERTY_KEYWORDS");
        $arFilter = Array("IBLOCK_ID"=>$iblock_id, "ACTIVE"=>"Y");
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
        while($ob = $res->GetNextElement())
        {
         $arFields = $ob->GetFields();
         if($arFields['PROPERTY_URL_VALUE']==$APPLICATION->GetCurPage()){
             if($arFields['PROPERTY_H1_TITLE_VALUE'])
                $APPLICATION->SetTitle($arFields['PROPERTY_H1_TITLE_VALUE']);
             if($arFields['PROPERTY_TITLE_VALUE']) {
                 $APPLICATION->SetPageProperty('title', $arFields['PROPERTY_TITLE_VALUE']);
                 $APPLICATION->AddHeadString('<meta property="og:title" content="'.$arFields['PROPERTY_TITLE_VALUE'].'" />', false);
             }
             if($arFields['PROPERTY_KEYWORDS_VALUE']) {
                 $APPLICATION->SetPageProperty('keywords', $arFields['PROPERTY_KEYWORDS_VALUE']);
             }
             if($arFields['PROPERTY_DESCRIPTION_VALUE']['TEXT'])
                $APPLICATION->SetPageProperty('description', $arFields['PROPERTY_DESCRIPTION_VALUE']['TEXT']);
             $APPLICATION->AddHeadString('<meta property="og:description" content="'.$arFields['PROPERTY_DESCRIPTION_VALUE']['TEXT'].'" />', false, false, 'AFTER_JS_KERNEL');
         }
        }?>
	</body>
</html>
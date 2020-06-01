$(document).ready(function(){
	// var lastVisible = $('.landings-list__item.last');
	$(document).on('click', '.landings-list__item--js-more', function(){
		var $this = $(this),
			block = $this.find('> span'),
			dataOpened = $this.data('opened'),
			thisText = block.text()
			dataText = block.data('text'),
			item = $this.closest('.landings-list__info').find('.landings-list__item-more');

		item.removeClass('hidden');

		if(dataOpened != 'Y'){
			item.velocity('stop').velocity({
				'opacity': 1,
			}, {
				'display': 'inline',
				'duration': 200,
				begin: function(){
					// lastVisible.toggleClass('last');
				}
			});
			$this.addClass('opened').data('opened', 'Y');
		}
		else{
			item.velocity('stop').velocity({
				'opacity': 0,
			}, {
				'display': 'none',
				'duration': 100,
				complete: function(){
					// lastVisible.toggleClass('last');
				}
			});
			$this.removeClass('opened').data('opened', 'N');
		}
		
		block.data('text', thisText).text(dataText);
	});

	$(document).on('click', '.landings-list__clear-filter', function(){
		$('.bx_filter_search_reset').trigger('click');
	})
	$(document).on('click', 'a.landings-list__name', function(e){
		var _this = $(this);

		if(_this.closest('.no_ajax.landings_list_wrapper').length) {
			return true;
		}

		e.preventDefault();

		if(_this.attr('href'))
		{
			$.ajax({
				url:_this.attr('href'),
				type: "GET",
				data: {'ajax_get':'Y', 'ajax_get_filter':'Y'},
				success: function(html){
					// $('#right_block_ajax').html($(html).find('#right_block_ajax').html());
					// $('.top-content-block').html($(html).find('.top-content-block').html());

					$('.right_block.catalog_page .container').html(html);
					CheckTopMenuFullCatalogSubmenu();

					BX.onCustomEvent('onAjaxSuccessFilter');

					var eventdata = {action:'jsLoadBlock'};
					BX.onCustomEvent('onCompleteAction', [eventdata, this]);

					InitScrollBar();

					if(window.History.enabled || window.history.pushState != null)
						window.History.replaceState( null, document.title, decodeURIComponent(_this.attr('href')) );
					else
						location.href = _this.attr('href');
				}
			})
		}
	})
});
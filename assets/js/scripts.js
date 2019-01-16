/**
|| --------------------------------------------------------------------------------------------
|| WebFonts JS
|| --------------------------------------------------------------------------------------------
||
|| @package		Dilaz Metabox
|| @subpackage	Metabox WebFonts
|| @since		Dilaz Metabox 2.0
|| @author		WebDilaz Team, http://webdilaz.com, http://themedilaz.com
|| @copyright	Copyright (C) 2017, WebDilaz LTD
|| @link		http://webdilaz.com/metabox
|| @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
|| 
*/

jQuery(document).ready(function($) {
	
	/* Webfont Icons selector */
	$(function() {
		
		$('.dilaz-mb-webfont-icons i').on('click', function(e) {
			
			e.preventDefault();
			
			var $this      = $(this),
				$faIcons   = $this.parents('.dilaz-mb-webfont-icons');
				$container = $this.parents('.webfont-container');
				$fontName  = $this.data('name');
				
			if ($this.hasClass('active')) {
				$faIcons.find('.active').removeClass('active');
				$container.find('.dilaz-mb-webfont-input').attr('value', '');
			} else {
				$faIcons.find('.active').removeClass('active');
				$this.addClass('active');
				$container.find('.dilaz-mb-webfont-input').attr('value', $fontName);
			}
		});
		
		function webfontSearch(e) {
			
			var $this      = $(this),
				$container = $this.parents('.webfont-container'),
				$filter    = $this.val(),
				$icons     = $container.find('.dilaz-mb-webfont-icons').find('span'),
				$pattern   = new RegExp($filter, 'i');
				
			$icons.hide();
			
			$.grep($icons.find('i'), function(input) {
				if ($pattern.test($(input).data('name'))) {
					$(input).parents('span').show();
				}		
			});
		}
		
		$('.dilaz-mb-webfont-search').bind('keyup', webfontSearch);
		
		$('.dilaz-mb-webfont-show-all').on('click', function(e){
			
			e.preventDefault();
			
			var $this = $(this);
			
			$this.siblings('.dilaz-mb-webfont-icons').css({'height':'auto'});
			$this.siblings('.dilaz-mb-webfont-show-less').css({'display':'block'});
			$this.css({'display':'none'});
		});
		
		$('.dilaz-mb-webfont-show-less').on('click', function(e){
			
			e.preventDefault();
			
			var $this = $(this);
			
			$this.siblings('.dilaz-mb-webfont-icons').css({'height':'132px'});
			$this.siblings('.dilaz-mb-webfont-show-all').css({'display':'block'});
			$this.siblings('.dilaz-mb-webfont-show-less').css({'display':'none'});
			$this.css({'display':'none'});
		});
	});
	
});
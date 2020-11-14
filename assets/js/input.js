(function($){


	function initialize_field( $el ) {

		/**
		 * Select2 for select. But only for ACF 5.n.
		 */
		if ( $el.find('select').length > 0 ) {
			var $select = $el.find('select'),
				$placeholder = $select.data('placeholder'),
				$allow_null = $select.data('allow_null');

			// Before the Select2 field is initialized, remove of the `data-ajax` data
			// attribute on the <select> field that ACF generates, as that data
			// attribute causes Select2 to expect AJAX functionality that this plugin
			// does not provide. This is a workaround to fix functionality until this
			// plugin is re-written.
			// Relevant issue: https://github.com/jonathan-dejong/acf-sites/issues/6
			$select.removeAttr('data-ajax');

			// Initialize the Select2 field.
			$select.select2({
				width:				'100%',
				containerCssClass:	'-acf',
				allowClear:			$allow_null,
				placeholder:		$placeholder,
			});
		}

	}


	if( typeof acf.add_action !== 'undefined' ) {

		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/

		acf.add_action('ready append', function( $el ){

			// search $el for fields of type 'sites'
			acf.get_fields({ type : 'sites'}, $el).each(function(){

				initialize_field( $(this) );

			});

		});


	} else {


		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM.
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/

		$(document).on('acf/setup_fields', function(e, postbox){

			$(postbox).find('.field[data-field_type="sites"]').each(function(){

				initialize_field( $(this) );

			});

		});


	}


})(jQuery);

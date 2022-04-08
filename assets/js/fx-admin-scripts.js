var FX = ( function( FX, $ ) {

    $( () => {
		FX.SiteMap.init(); 
	})
    
    FX.SiteMap = {
		init() {
			this.general();
            this.displaySelectedChoices();
            this.copyClipboard();
		},

        copyClipboard() {
            $('.fx-sitemap-copy__clipboard').on('click', function(e) {
                e.preventDefault()
                let copyClipText = $(this).siblings('.copy-shortcode').select() 
                document.execCommand("copy");
            })
        },

        displaySelectedChoices() {
            document.querySelectorAll('select').forEach( el => {
                let hiddenInputChoice   = el.getAttribute('name'),
                    excludedPostValue   = document.getElementById(hiddenInputChoice).value,
                    config  = {}

                let initChoices = new Choices( el )

                if( excludedPostValue.length ) {
                    initChoices.setChoiceByValue( JSON.parse( excludedPostValue ) )
                }
            })
        },

        general() {

            $(window).on('load', function(){
                if( $('#setting-error-settings_updated').length ) {
                    $('#setting-error-settings_updated').insertAfter('.fx-sitemap__head').show();
                }
            })

            $('.fx-sitemap-cpt-input').on('click', function(){
                let $this   = $(this)
                    console.log( $this.val() )
                    
                if( $this.val() == '' ) {
                    $this.val('1');
                } else if( $this.val() == '1' ) {
                    $this.val('');
                }
            })

            $('.js-accordion-toggle').on('click', function(){
                let $this = $(this);
                $this.parent().toggleClass('accordion-active');
                $this.parent().find('.accordion__wrapper').toggleClass('show-accordion');
            })

            $('.js-excluded-select, .js-included-select').on('change', function(e) {

                let $this           = $(this),
                    currentSelect   = $this.attr('name'),
                    options         = $('select[name="'+ currentSelect +'"] option')
                    
                let selectedOptions = $.map(options, e => $(e).val()),
                jsonString          = '';

                if( selectedOptions.length ) {
                    jsonString   = JSON.stringify(selectedOptions);
                }

                console.log(jsonString)
                $('#' + currentSelect ).val(jsonString)
            });


        }
	};

} ( FX || {}, jQuery ) );
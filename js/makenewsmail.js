


	jQuery(function() {
		jQuery('#make_form')
			.on('submit', function(e) {
				e.preventDefault();
				jQuery('#makenewsmail-submit').attr('disabled','disabled');
				var url = jQuery('#f').attr("value") + "makenewsmail_ajax_sender.php";
				var valid = true;
				$makeslide = jQuery('<div id="makenewsmail-slide"></div>').appendTo('body');
								
				if(!jQuery('#email').attr('value').match(/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) ) {
					valid = false;	
				};
				
				if(!valid) {
					if(jQuery('#makenewsmail-notice').length === 0 ) {
						jQuery('<p id="makenewsmail-notice"></p>').insertAfter('.widget_makenewsmail .widget-title');	
						jQuery('#makenewsmail-notice').text("Email not valid");
						jQuery('#makenewsmail-submit').removeAttr('disabled');
						valid = true;
						return false;
					}	
				}
								
				jQuery.post(url, jQuery(this).serialize(), function (data, resStatus) {
					xmldoc = jQuery.parseXML(data),
					$xml = jQuery(xmldoc)
					$created = $xml.find('subscribers-created').text();
					$updated = $xml.find('subscribers-updated').text();
					
					//( parseInt($created) === 1 ) ? $text = "You have been registered!" : $text = "Your profile is updated!";
					if( parseInt($created) === 0 && parseInt($updated) === 0 ) $text = "Email not valid"; 
					
					if ( parseInt($created) === 1) {
						jQuery($makeslide).text("You are registered!");
						if(jQuery($makeslide).is(':hidden')) {
							jQuery('#makenewsmail-notice').remove();
							jQuery($makeslide).slideToggle('fast');
							setTimeout( function() {
								jQuery($makeslide).slideToggle('fast');	
								jQuery('#makenewsmail-submit').removeAttr('disabled');				
							},2000);
						}
					}
					
					if ( parseInt($updated) === 1) {
						jQuery($makeslide).html("<p>Your information is updated.</p>");
						if(jQuery($makeslide).is(':hidden')) {
							jQuery('#makenewsmail-notice').remove();
							jQuery($makeslide).slideToggle('fast');
							setTimeout( function() {
								jQuery($makeslide).slideToggle('fast');	
								jQuery('#makenewsmail-submit').removeAttr('disabled');						
							},2000);
						}
					}
					
					
				})
			})
			.on('focus', 'input', function() {
				var focus_value = jQuery(this).attr('value');
				if( focus_value === jQuery(this).data("value") ) { jQuery(this).attr('value',''); }
			})
			.on('blur', 'input', function() {
				var val = jQuery(this).attr("value");
				if ( val === '' ) jQuery(this).attr("value", jQuery(this).data("value") );
			})
		
	})
	
	


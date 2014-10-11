/* Global Javascripts */

//Modals
function activateModal(modal_id, modal_btn, ajaxRequest){
	jQuery(modal_btn).click(function(e) {
	 var target = jQuery(this);
	 var targetID = target.attr('href');
	 var modal = jQuery(modal_id);
	 //load modal template into modal content with ajax
	if(ajaxRequest != false){
	 var modalContent =  $.ajax({
		 url: targetID,
		 context: document.body
		 
		  }).done(function() { 
		    modal.find('.modal-content').html(modalContent.responseText);
			modal.reveal({
		     close: function(){}
		     });
		    
		  		  }); 
		}
		else{
		modal.reveal({
		     close: function(){}
		     });
		}
		 e.preventDefault(); 
	   });
}//end activateModal()

/* ------ DOCUMENT READY -------- */
jQuery(document).ready(function($){



jQuery('.social .icon').click(function(e){
		e.preventDefault();
	});
if(jQuery('#newsletter-modal').length > 0){
	activateModal('#newsletter-modal', '.newsletter-modal-btn', false);
	newsletterModal = jQuery('#newsletter-modal').detach();
	jQuery('body').append(newsletterModal);


if(jQuery('.toggle-topbar').length > 0){
	jQuery('.toggle-topbar').on('click', function(e){
		jQuery('.top-bar').toggleClass('expanded');
		e.preventDefault();
	});
}	
}

}); //end document ready

/* --------- WINDOW LOAD --------- */
jQuery(window).load(function(){
	//jQuery('.quick-chat-smilies-container').hide();
	var embedFeed = jQuery('#feed-embed-stand-alone');
	embedFeed.css({'height':'570', 'marginTop': '-216px', 'marginBottom': '20px', 'overflow' : 'hidden'});
	//console.log(embedFeed);
	//embedFeed.hide();
	
}); //end window load

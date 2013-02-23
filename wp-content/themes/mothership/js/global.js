/* Global Javascripts */

//Modals
function activateModal(modal_id, modal_btn){
	jQuery(modal_btn).click(function(e) {
	 var target = jQuery(this);
	 var targetID = target.attr('href');
	 var modal = jQuery(modal_id);
	 //load modal template into modal content with ajax
	 
	 var modalContent =  $.ajax({
		 url: targetID,
		 context: document.body
		 
		  }).done(function() { 
		    modal.find('.modal-content').html(modalContent.responseText);
			modal.reveal({
		     close: function(){}
		     });
		    
		  		  }); 
		  		 
  		 // modal.reveal();
		 e.preventDefault(); 
	   });
}//end activateModal()

/* ------ DOCUMENT READY -------- */
jQuery(document).ready(function($){



}); //end document ready

/* --------- WINDOW LOAD --------- */
jQuery(window).load(function(){
	jQuery('.quick-chat-smilies-container').hide();
	var embedFeed = jQuery('#feed-embed-stand-alone');
	embedFeed.css({'height':'570', 'marginTop': '-216px', 'marginBottom': '20px', 'overflow' : 'hidden'});
	//console.log(embedFeed);
	//embedFeed.hide();
	
	//activateModal('#main-modal', '.modal_btn, .modal_btn_container a');
}); //end window load

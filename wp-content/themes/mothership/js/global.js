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
	activateModal('#main-modal', '.modal_btn, .modal_btn_container a');
}); //end window load

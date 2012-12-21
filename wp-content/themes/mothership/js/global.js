/* Global Javascripts */

//Modals
function activateModal(modal, modal_btn){
	jQuery(modal_btn).click(function() {
	     jQuery(modal).reveal();
	   });
}//end activateModal()

/* ------ DOCUMENT READY -------- */
jQuery(document).ready(function($){




}); //end document ready

/* --------- WINDOW LOAD --------- */
jQuery(window).load(function(){
	activateModal('#main-modal', '.modal_btn');
}); //end window load

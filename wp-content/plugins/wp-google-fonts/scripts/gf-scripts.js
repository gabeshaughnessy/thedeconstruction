/* jQuery scripts for Wordpress Google Fonts */
jQuery(document).ready(function() {
	jQuery('.show_hide').click(function() {
		var element = jQuery(this).attr("id");
		var parent = jQuery('#' + element).parent().attr("id");
		var selected = jQuery('#' + parent + '-select').val();
		var name = get_normalized_name(selected);
		//alert('show_hide selected: ' + selected);
		if (jQuery(this).hasClass("showing")){
			hide_selected_items(name, element);
			jQuery(this).html('Show Options');
		}else{
			show_selected_items(name, element);
			jQuery(this).html('Hide Options');
		}
		jQuery(this).toggleClass("showing");
		
		return false;
	});
	
	
	/* update the selections on change */
	jQuery('.webfonts-select').change(function() {
		var element = jQuery(this).attr("id");
		var selected = jQuery(this).val();
		var parent = jQuery('#' + element).parent().attr("id");
		var selections = jQuery('#' + parent + ' .webfonts-selections .check');
		var name = get_normalized_name(selected);
		
		/* deselect all variant and subset check boxes */
		selections.attr('checked',false);
			
		if(selected != '' && selected != 'off'){			
			/* show item variants and subsets */
			show_selected_items(name, element);
			jQuery('#' + parent + ' .show_hide').toggleClass("showing")
			jQuery('#' + parent + ' .show_hide').html('Hide Options');
			
			/* pre select variant and character set */
			pre_select_items(name, element);
		}else{
			/* clear all the items and hide them */
			jQuery('#' + parent + ' .webfonts-usage :checked').attr('checked', false);
			jQuery('#' + parent + '_css').val('');
			hide_selected_items(name, element);
		}
	});
	
	function show_selected_items(normalized, element){
		/* limit all our actions to just within this specific selection */
		var parent = jQuery('#' + element).parent().attr("id");
		var subsets = jQuery('#' + parent + ' .subset-' + normalized + ' .check');
		
		/* hide everything else */
		jQuery('#' + parent + ' .variant-items:not(.variant-' + normalized + ')').hide();
		jQuery('#' + parent + ' .subset-items:not(.subset-' + normalized + ')').hide();
		
		/* show the needed elements */
		if(subsets.size() > 1){
			jQuery('#' + parent + ' .webfonts-subsets').show(); 
		}else{
			jQuery('#' + parent + ' .webfonts-subsets').hide(); 
		}
		jQuery('#' + parent + ' .webfonts-selections').show(300);
		jQuery('#' + parent + ' .variant-' + normalized + '.variant-items').show(300); 
		jQuery('#' + parent + ' .subset-' + normalized + '.subset-items').show(300); 		
		
	}
	
	function hide_selected_items(normalized, element){
		/* limit all our actions to just within this specific selection */
		var parent = jQuery('#' + element).parent().attr("id");
		
		jQuery('#' + parent + ' .webfonts-selections').hide(800);
	}
	
	function get_normalized_name(name){
		return (name.replace(" ","-"));
	}
	
	function pre_select_items(normalized, element){
		/* limit all our actions to just within this specific selection */
		var parent = jQuery('#' + element).parent().attr("id");
		
		/* select 'regular' variant if available, or only variant, or first one */
		var variants = jQuery('#' + parent + ' .variant-' + normalized + ' .check');
		var regular = jQuery('#' + parent + ' .variant-' + normalized + ' [value="regular"]');
		
		if(variants.size() > 1){
			if(regular.size()==1){
				regular.attr('checked',true);
			}else{
				variants.first().attr('checked',true)
			}
		}
		if(variants.size()==1){
			variants.attr('checked',true);
			variants.attr('readonly','readonly');
		}
		
		/* select latin subset if available, or only subset, or first one */
		var subsets = jQuery('#' + parent + ' .subset-' + normalized + ' .check');
		var latin = jQuery('#' + parent + ' .subset-' + normalized + ' [value="latin"]');
		
		if(subsets.size() > 1){
			if(latin.size()==1){
				latin.attr('checked',true);
			}else{
				subsets.first().attr('checked',true)
			}
		}
		if(subsets.size()==1){
			subsets.attr('checked',true);
			subsets.attr('readonly','readonly');
		}
		
	}
});
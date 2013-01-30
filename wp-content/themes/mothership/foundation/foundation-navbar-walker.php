<?php
/* 
Customize the output of menus for Foundation nav bar classes
*/

class nav_bar_walker extends Walker_Nav_Menu {

    function display_element($element, &$children_elements, $max_depth, $depth=0, $args, &$output) {
        $element->has_children = !empty($children_elements[$element->ID]);
        $element->classes[] = ($element->current) ? 'active' : '';
        $element->classes[] = ($element->has_children) ? 'has-flyout' : '';
		
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }	
	
    function start_el(&$output, $item, $depth, $args) {
        $item_html = '';
        parent::start_el($item_html, $item, $depth, $args);	
		
        $classes = empty($item->classes) ? array() : (array) $item->classes;	

        if(in_array('has-flyout', $classes)) {
            $item_html = str_replace('</a>', '</a><a href="'.esc_attr($item->url).'" class="flyout-toggle"><span> </span></a>', $item_html);
        }
		
        $output .= $item_html;
    }

    function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "\n<ul class=\"sub-menu flyout\">\n";
    }
    
} // end nav bar walker
?>
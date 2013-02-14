<?php 
/* Admin Options Panel from http://en.bainternet.info/2012/my-options-panel */

/**
* configure your admin page
*/
$config = array(
    'menu'=> 'theme',                 //sub page to settings page
    'page_title' => 'Theme Options',   //The name of this page
    'capability' => 'edit_themes',       // The capability needed to view the page
    'option_group' => 'mothership_options',    //the name of the option to create in the database
    'id' => 'mothership_options',                // Page id, unique per page
    'fields' => array(),                 // list of fields (can be added by field arrays)
    'local_images' => false,             // Use local or hosted images (meta box images for add/remove)
    'use_with_theme' => true            //change path if used with theme set to true, false for a plugin or anything else for a custom path(default false).
); 

/**
 * Initiate your admin page
 */
$options_panel = new BF_Admin_Page_Class($config);

/**
 * define your admin page tabs listing
 */
$options_panel->OpenTabs_container('');
$options_panel->TabsListing(array(
   'links' => array(
   'options_1' =>  __('Simple Options'),
   'options_2' =>  __('Fancy Options'),
   'options_3' => __('Editor Options'),
   'options_4' => __('WordPress Options'),
   'options_5' =>  __('Repeater')
   )
));

// Open admin page first tab with the id options_1
$options_panel->OpenTab('options_1');

/**
* Add fields to your admin page first tab
*
* Simple options:
* input text, checbox, select, radio
* textarea
*/
//title
$options_panel->Title("Simple Options");
//An optional description paragraph
$options_panel->addParagraph("This is a simple paragraph");
//text field
$options_panel->addText('text_field_id',array('name'=> 'My Text '));
//textarea field
$options_panel->addTextarea('textarea_field_id',array('name'=> 'My Textarea '));
//checkbox field
$options_panel->addCheckbox('checkbox_field_id',array('name'=> 'My Checkbox '));
//select field
$options_panel->addSelect('select_field_id',array('selectkey1'=>'Select Value1','selectkey2'=>'Select Value2'),array('name'=> 'My select ', 'std'=> array('selectkey2')));
//radio field
$options_panel->addRadio('radio_field_id',array('radiokey1'=>'Radio Value1','radiokey2'=>'Radio Value2'),array('name'=> 'My Radio Filed', 'std'=> array('radionkey2')));
// Close first tab
$options_panel->CloseTab();

// Open admin page Second tab
$options_panel->OpenTab('options_2');
/**
* Add fields to your admin page 2nd tab
*
* Fancy options:
* image uploader
* date picker
* time picker
* color picker
*/
//title
$options_panel->Title("Fancy Options");
//Image field
$options_panel->addImage('image_field_id',array('name'=> 'My Image '));
//date field
$options_panel->addDate('date_field_id',array('name'=> 'My Date '));
//Time field
$options_panel->addTime('time_field_id',array('name'=> 'My Time '));
//Color field
$options_panel->addColor('color_field_id',array('name'=> 'My Color '));
// Close second tab
$options_panel->CloseTab();
// Open admin page 3rd tab
$options_panel->OpenTab('options_3');
/**
* Add fields to your admin page 3rd tab
*
* Editor options:
* WYSIWYG (tinyMCE editor)
* Syntax code editor (css,html,js,php)
*/
//title
$options_panel->Title("Editor Options");
//wysiwyg field
$options_panel->addWysiwyg('wysiwyg_field_id',array('name'=> 'My wysiwyg Editor '));
//code editor field
$options_panel->addCode('code_field_id',array('name'=> 'Code Editor ','syntax' => 'php'));
// Close 3rd tab
$options_panel->CloseTab();
 
// Open admin page 4th tab
$options_panel->OpenTab('options_4');
/**
* Add fields to your admin page 4th tab
*
* WordPress Options:
* Taxonomies dropdown
* posts dropdown
* Taxonomies checkboxes list
* posts checkboxes list
*
*/
//title
$options_panel->Title("WordPress Options");
//taxonomy select field
$options_panel->addTaxonomy('taxonomy_field_id',array('taxonomy' => 'category'),array('name'=> 'My Taxonomy Select'));
//posts select field
$options_panel->addPosts('posts_field_id',array('post_type' => 'post'),array('name'=> 'My Posts Select'));
//Roles select field
$options_panel->addRoles('roles_field_id',array(),array('name'=> 'My Roles Select'));
//taxonomy checkbox field
$options_panel->addTaxonomy('taxonomy2_field_id',array('taxonomy' => 'category','type' => 'checkbox_list'),array('name'=> 'My Taxonomy Checkboxes'));
//posts checkbox field
$options_panel->addPosts('posts2_field_id',array('post_type' => 'post','type' => 'checkbox_list'),array('name'=> 'My Posts Checkboxes'));
//Roles checkbox field
$options_panel->addRoles('roles2_field_id',array('type' => 'checkbox_list'),array('name'=> 'My Roles Checkboxes'));
// Close 4th tab
$options_panel->CloseTab();
 
// Open admin page 5th tab
$options_panel->OpenTab('options_5');
/*
* To Create a reapeater Block first create an array of fields
* use the same functions as above but add true as a last param
*/
$repeater_fields[] = $options_panel->addText('re_text_field_id',array('name'=> 'My Text '),true);
$repeater_fields[] = $options_panel->addTextarea('re_textarea_field_id',array('name'=> 'My Textarea '),true);
$repeater_fields[] = $options_panel->addCheckbox('re_checkbox_field_id',array('name'=> 'My Checkbox '),true);
$repeater_fields[] = $options_panel->addImage('image_field_id',array('name'=> 'My Image '),true);
// Then just add the fields to the repeater block
//repeater block
$options_panel->addRepeaterBlock('re_',array('inline' => false, 'name' => 'This is a Repeater Block','fields' => $repeater_fields));
//Close 5th tab
$options_panel->CloseTab();
?>
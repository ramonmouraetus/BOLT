<?php

// global
global $field_group;
		
		
// active
acf_render_field_wrap(array(
	'label'			=> __('Active','plusdin-homepage-generator'),
	'instructions'	=> '',
	'type'			=> 'true_false',
	'name'			=> 'active',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['active'],
	'ui'			=> 1,
	//'ui_on_text'	=> __('Active', 'acf'),
	//'ui_off_text'	=> __('Inactive', 'acf'),
));


// style
acf_render_field_wrap(array(
	'label'			=> __('Style','plusdin-homepage-generator'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'style',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['style'],
	'choices' 		=> array(
		'default'			=>	__("Standard (WP metabox)",'plusdin-homepage-generator'),
		'seamless'			=>	__("Seamless (no metabox)",'plusdin-homepage-generator'),
	)
));


// position
acf_render_field_wrap(array(
	'label'			=> __('Position','plusdin-homepage-generator'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'position',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['position'],
	'choices' 		=> array(
		'acf_after_title'	=> __("High (after title)",'plusdin-homepage-generator'),
		'normal'			=> __("Normal (after content)",'plusdin-homepage-generator'),
		'side' 				=> __("Side",'plusdin-homepage-generator'),
	),
	'default_value'	=> 'normal'
));


// label_placement
acf_render_field_wrap(array(
	'label'			=> __('Label placement','plusdin-homepage-generator'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'label_placement',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['label_placement'],
	'choices' 		=> array(
		'top'			=>	__("Top aligned",'plusdin-homepage-generator'),
		'left'			=>	__("Left aligned",'plusdin-homepage-generator'),
	)
));


// instruction_placement
acf_render_field_wrap(array(
	'label'			=> __('Instruction placement','plusdin-homepage-generator'),
	'instructions'	=> '',
	'type'			=> 'select',
	'name'			=> 'instruction_placement',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['instruction_placement'],
	'choices' 		=> array(
		'label'		=>	__("Below labels",'plusdin-homepage-generator'),
		'field'		=>	__("Below fields",'plusdin-homepage-generator'),
	)
));


// menu_order
acf_render_field_wrap(array(
	'label'			=> __('Order No.','plusdin-homepage-generator'),
	'instructions'	=> __('Field groups with a lower order will appear first','plusdin-homepage-generator'),
	'type'			=> 'number',
	'name'			=> 'menu_order',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['menu_order'],
));


// description
acf_render_field_wrap(array(
	'label'			=> __('Description','plusdin-homepage-generator'),
	'instructions'	=> __('Shown in field group list','plusdin-homepage-generator'),
	'type'			=> 'text',
	'name'			=> 'description',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['description'],
));


// hide on screen
$choices = array(
	'permalink'			=>	__("Permalink", 'plusdin-homepage-generator'),
	'the_content'		=>	__("Content Editor",'plusdin-homepage-generator'),
	'excerpt'			=>	__("Excerpt", 'plusdin-homepage-generator'),
	'custom_fields'		=>	__("Custom Fields", 'plusdin-homepage-generator'),
	'discussion'		=>	__("Discussion", 'plusdin-homepage-generator'),
	'comments'			=>	__("Comments", 'plusdin-homepage-generator'),
	'revisions'			=>	__("Revisions", 'plusdin-homepage-generator'),
	'slug'				=>	__("Slug", 'plusdin-homepage-generator'),
	'author'			=>	__("Author", 'plusdin-homepage-generator'),
	'format'			=>	__("Format", 'plusdin-homepage-generator'),
	'page_attributes'	=>	__("Page Attributes", 'plusdin-homepage-generator'),
	'featured_image'	=>	__("Featured Image", 'plusdin-homepage-generator'),
	'categories'		=>	__("Categories", 'plusdin-homepage-generator'),
	'tags'				=>	__("Tags", 'plusdin-homepage-generator'),
	'send-trackbacks'	=>	__("Send Trackbacks", 'plusdin-homepage-generator'),
);
if( acf_get_setting('remove_wp_meta_box') ) {
	unset( $choices['custom_fields'] );	
}

acf_render_field_wrap(array(
	'label'			=> __('Hide on screen','plusdin-homepage-generator'),
	'instructions'	=> __('<b>Select</b> items to <b>hide</b> them from the edit screen.','plusdin-homepage-generator') . '<br /><br />' . __("If multiple field groups appear on an edit screen, the first field group's options will be used (the one with the lowest order number)",'plusdin-homepage-generator'),
	'type'			=> 'checkbox',
	'name'			=> 'hide_on_screen',
	'prefix'		=> 'acf_field_group',
	'value'			=> $field_group['hide_on_screen'],
	'toggle'		=> true,
	'choices' 		=> $choices
));


// 3rd party settings
do_action('acf/render_field_group_settings', $field_group);
		
?>
<div class="acf-hidden">
	<input type="hidden" name="acf_field_group[key]" value="<?php echo $field_group['key']; ?>" />
</div>
<script type="text/javascript">
if( typeof acf !== 'undefined' ) {
		
	acf.newPostbox({
		'id': 'acf-field-group-options',
		'label': 'left'
	});	

}
</script>
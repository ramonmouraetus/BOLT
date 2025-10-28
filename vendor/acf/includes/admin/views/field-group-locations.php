<?php

// global
global $field_group;

?>
<div class="acf-field">
	<div class="acf-label">
		<label><?php _e("Rules",'plusdin-homepage-generator'); ?></label>
		<p class="description"><?php _e("Create a set of rules to determine which edit screens will use these advanced custom fields",'plusdin-homepage-generator'); ?></p>
	</div>
	<div class="acf-input">
		<div class="rule-groups">
			
			<?php foreach( $field_group['location'] as $i => $group ): 
				
				// bail ealry if no group
				if( empty($group) ) return;
				
				
				// view
				acf_get_view('html-location-group', array(
					'group'		=> $group,
					'group_id'	=> "group_{$i}"
				));
			
			endforeach;	?>
			
			<h4><?php _e("or",'plusdin-homepage-generator'); ?></h4>
			
			<a href="#" class="button add-location-group"><?php _e("Add rule group",'plusdin-homepage-generator'); ?></a>
			
		</div>
	</div>
</div>
<script type="text/javascript">
if( typeof acf !== 'undefined' ) {
		
	acf.newPostbox({
		'id': 'acf-field-group-locations',
		'label': 'left'
	});	

}
</script>
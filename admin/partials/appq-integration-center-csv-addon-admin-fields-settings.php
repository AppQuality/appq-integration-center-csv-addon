<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://bitbucket.org/%7B1c7dab51-4872-4f3e-96ac-11f21c44fd4b%7D/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Internal_Addon
 * @subpackage Appq_Integration_Center_Internal_Addon/admin/partials
 */

$field_mapping = !empty($config) ? json_decode($config->field_mapping) : array();
if (empty($field_mapping)) {
    $field_mapping = array();
}

$endpoint_data = !empty($config) && property_exists($config,'endpoint') ? json_decode($config->endpoint,true) : array();
?>
<div id="csv_fields_settings">
	<?php 
	$this->partial('settings/field-mapping', array(
		'field_mapping' => $field_mapping,
		'campaign_id' => $campaign_id
    ));
    ?>
</div>
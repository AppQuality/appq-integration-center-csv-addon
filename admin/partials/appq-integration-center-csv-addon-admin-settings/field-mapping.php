<?php

$api = new CSVRestApi($campaign_id);
$data = array();
if (!empty($fields = $api->get_fields())) {
	$data = $fields;
} else {
	$data = $api->basic_configuration;
}

foreach ($data as $key => $value) {
	if (!in_array($key, array_keys($field_mapping))) {
		$field_mapping[$key] = $value;
	}
}

?>

    <div class="row">
        <div class="col-6"><?php printf('<h4 class="title py-3">%s</h4>', __('Field mapping', $this->plugin_name)); ?></div>
        <div class="col-6 text-right actions mt-2">
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#add_mapping_field_modal"><?php _e('Add new field mapping', $this->plugin_name); ?></button>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-2">
			<small>
				<strong><?= __('Name', $this->plugin_name); ?></strong>
				<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('CSV column name', $this->plugin_name) ?>"></i>
			</small>
        </div>
        <div class="col-2">
			<small>
				<strong><?= __('Content', $this->plugin_name); ?></strong>
				<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('The content you want to set the CSV field to. {Bug.*} fields will be replaced with the bug data', $this->plugin_name) ?>"></i>
			</small>
        </div>
		<div class="col-4">
			<small>
				<strong><?= __('Description', $this->plugin_name); ?></strong>
				<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('The description of the field', $this->plugin_name) ?>"></i>
			</small>
        </div>
		<div class="col-2">
			<small>
				<strong><?= __('Selected', $this->plugin_name); ?></strong>
				<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('If the field has been selected for the export', $this->plugin_name) ?>"></i>
			</small>
        </div>
    </div>
    <div class="fields-list">
		<?php foreach ($field_mapping as $key => $item): ?>
			<?php $this->partial('settings/field-mapping-row', array(
				'item' => $item,
				'selected_icon' => ($item->selected ? true : false)  
					? '<i style="color: #62aa4e;" class="fa fa-check"></i>'
					: ''
			)); ?>
		<?php endforeach; ?>
		<!--<script type="text/html" id ="tmpl-field_mapping_row">
		<?php $this->partial('settings/field-mapping-row',array(
			'_key' => '{{data.key}}',
			'item' => array(
				'value' => '{{data.content}}',
				'is_json' => '{{data.json}}',
				'sanitize' => '{{data.sanitize}}'
			),
			'sanitize_icon' => '<# if (data.sanitize == "on") {#><i class="fa fa-check"></i><#} else {#><i class="fa fa-minus"></i><#}#>',
			'is_json_icon' => '<# if (data.json == "on") {#><i class="fa fa-check"></i><#} else {#><i class="fa fa-minus"></i><#}#>'
		)); ?>
		</script>-->
    </div>

<?php
$this->partial('settings/edit-mapping-field-modal', array('campaign_id' => $campaign_id));
$this->partial('settings/delete-mapping-field-modal', array());
?>

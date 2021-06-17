<?php

$api = new CSVRestApi($campaign_id);
$data = array();
if (!empty($field_mapping)) {
	$data = $field_mapping;
} else {
	$data = $api->basic_configuration;
}

?>

    <div class="row">
        <div class="col-6"><?php printf('<h4 class="title py-3">%s</h4>', __('Field mapping', $this->plugin_name)); ?></div>
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
		<div class="col-2 text-center">
			<small>
				<strong><?= __('Selected', $this->plugin_name); ?></strong>
				<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('If the field has been selected for the export', $this->plugin_name) ?>"></i>
			</small>
        </div>
    </div>
    <div class="fields-list">
		<?php foreach ($data as $key => $item): ?>
			<?php $this->partial('settings/field-mapping-row', array(
				'_item' => $item,
				'_key' => $key,
				'_selected_icon' => ($item->selected ? true : false)
					? '<i style="color: #62aa4e;" class="fa fa-check"></i>'
					: ''
			)); ?>
		<?php endforeach; ?>
    </div>

<?php
$this->partial('settings/edit-mapping-field-modal', array('campaign_id' => $campaign_id));
?>

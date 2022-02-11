<?php

$api = new CSVRestApi($campaign_id);
$data = array();
if (!empty($field_mapping)) {
	$data = $field_mapping;
} else {
	$data = $api->basic_configuration;
}

?>

<div class="row margin-bottom-xxl">
	<div class="col-xs-6"><?php printf('<h4 class="title py-3">%s</h4>', __('Field mapping', 'appq-integration-center-csv-addon')); ?></div>
</div>

<div class="row margin-bottom-xxl margin-top-xxl">
	<div class="col-sm-12">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>
						<?= __('Name', 'appq-integration-center-csv-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('CSV column name', 'appq-integration-center-csv-addon') ?>"></i>
					</th>
					<th>
						<?= __('Content', 'appq-integration-center-csv-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('The content you want to set the CSV field to. {Bug.*} fields will be replaced with the bug data', 'appq-integration-center-csv-addon') ?>"></i>
					</th>
					<th>
						<?= __('Description', 'appq-integration-center-csv-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('The description of the field', 'appq-integration-center-csv-addon') ?>"></i>
					</th>
					<th>
						<?= __('Selected', 'appq-integration-center-csv-addon'); ?>
						<i class="fa fa-question-circle" data-toggle="tooltip" title="<?= __('If the field has been selected for the export', 'appq-integration-center-csv-addon') ?>"></i>
					</th>
				</tr>
			</thead>
			<tbody class="fields-list">
				<?php foreach ($data as $key => $item) : ?>
					<?php $this->partial('settings/field-mapping-row', array(
						'_item' => $item,
						'_key' => $key,
						'_selected_icon' => ($item->selected ? true : false)
							? '<i style="color: #62aa4e;" class="fa fa-check"></i>'
							: ''
					)); ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php
$this->partial('settings/edit-mapping-field-modal', array('campaign_id' => $campaign_id));
?>
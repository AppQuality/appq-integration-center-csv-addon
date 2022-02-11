<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/AppQuality/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Csv_Addon
 * @subpackage Appq_Integration_Center_Csv_Addon/admin/partials
 */

$CSVRestApi = new CSVRestApi($cp_id);
$file_format = $CSVRestApi->get_format($cp_id);
$custom_fields = $this->get_custom_fields($cp_id);
$selected_fields = $CSVRestApi->get_selected_fields($cp_id);
$data = array();
if (!empty($fields = $CSVRestApi->get_fields())) {
    $data = $fields;
} else {
    $data = $CSVRestApi->basic_configuration;
}

?>

<div class="form-group">
    <label for="available-formats"><?php _e("Select file format", 'appq-integration-center-csv-addon'); ?></label>
    <select class="form-control ux-select select2-hidden-accessible" id="available-formats" name="csv_endpoint" data-parent="#csv_tracker_settings" data-placeholder="<?php _e("Select file format", 'appq-integration-center-csv-addon'); ?>">
        <option value="csv_format" <?= ($file_format == "csv_format") ? "selected='selected'" : "" ?>>CSV</option>
        <option value="xml_format" <?= ($file_format == "xml_format") ? "selected='selected'" : "" ?>>XML</option>
    </select>
</div>
<div class="form-group">
    <label><?php _e("Select fields to export", 'appq-integration-center-csv-addon'); ?></label>
    <select class="form-control csv-fields ux-select select2-hidden-accessible" id="available-csv-fields" name="csv_fields" multiple data-parent="#csv_tracker_settings" data-placeholder="<?php _e("Select csv fields", 'appq-integration-center-csv-addon'); ?>">
        <?php foreach ($data as $key => $value) : ?>
            <option class="field" <?= array_key_exists( $key, $selected_fields ) ? "selected='selected'" : ""; ?>
                data-key="<?= $key ?>"
                data-value="<?= $value->value ?>"
                data-description="<?= $value->description ?>">
                    <?= $key ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
<script type="text/javascript">
    var cp_id = JSON.parse('<?php echo json_encode($cp_id); ?>');
</script>
<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://bitbucket.org/%7B1c7dab51-4872-4f3e-96ac-11f21c44fd4b%7D/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Csv_Addon
 * @subpackage Appq_Integration_Center_Csv_Addon/admin/partials
 */

$CSVRestApi = new CSVRestApi( $cp_id );
$file_format = $CSVRestApi->get_format($cp_id);
$custom_fields = $this->get_custom_fields( $cp_id );
$selected_fields = $CSVRestApi->get_selected_fields( $cp_id );
$data = array();
if (!empty($fields = $CSVRestApi->get_fields())) {
	$data = $fields;
} else {
	$data = $CSVRestApi->basic_configuration;
}

?>

<div class="row mt-5">
    <label class="mb-3"><?php _e("Select file format", $this->plugin_name); ?></label>
    <select id="available-formats" name="available_formats" data-parent="#setup_manually_cp" class="ux-select select2-hidden-accessible" data-placeholder="<?php _e("Select file format", $this->plugin_name); ?>" data-select2-id="5" tabindex="-1" aria-hidden="true">
        <option value="csv_format" <?= ($file_format == "csv_format") ? "selected='selected'": "" ?>>CSV</option>
        <option value="xml_format" <?= ($file_format == "xml_format") ? "selected='selected'": "" ?>>XML</option>
    </select>
</div>
<div class="row mt-5">
    <label><?php _e("Select fields to export", $this->plugin_name); ?></label>
    <div id="available-fields" class="col-sm-12 available_fields csv-fields">
        <?php
        foreach ( $data as $key => $value ) {
        ?>
            <span class='field <?= array_key_exists( $key, $selected_fields ) ? "selected" : ""; ?>'
                data-key="<?= $key ?>"
                data-value="<?= $value->value ?>"
                data-description="<?= $value->description ?>">
                    <?= $key ?>
            </span>
        <?php
        }
        ?>
    </div>
</div>
<script type="text/javascript">
    var cp_id = JSON.parse( '<?php echo json_encode( $cp_id ); ?>' );
</script>

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

$cp_id = $_GET[ "id" ];
$CSVRestApi = new CSVRestApi( $cp_id );
$custom_fields = $this->get_custom_fields( $cp_id );
$selected_fields = $CSVRestApi->get_selected_fields( $cp_id );
$data = array();
if (!empty($fields = $CSVRestApi->get_fields())) {
	$data = $fields;
} else {
	$data = $CSVRestApi->basic_configuration;
}

?>

<div class="row">
    <h4 class="col-sm-12">SELECT FILE FORMAT</h4>
    <select id="available-formats">
        <option value="csv_format">CSV</option>
        <option value="xml_format">XML</option>
    </select>
</div>
<div class="row">
    <h4 class="col-sm-12">SELECT FIELDS TO EXPORT</h4>
    <div id="available-fields" class="col-sm-12 available_fields csv-fields">
        <?php 
        foreach ( $data as $key => $value ) {
        ?>
            <span class='field <?= array_key_exists( $key, $selected_fields ) ? "selected" : ""; ?>' 
                data-key="<?= $key ?>"
                data-value="<?= $value->value ?>"
                data-description="<?= $value->description ?>">
                    <?= $value->value ?> 
            </span>
        <?php 
        }
        /*foreach ( $custom_fields as $custom_field ) { 
        ?>
            <span class="field custom <?= in_array( $custom_field->name, $selected_fields ) ? "selected" : ""; ?>"
                data-map="<?= esc_attr($custom_field->map) ?>" 
                data-source="<?= esc_attr($custom_field->source) ?>" 
                data-name="<?= esc_attr($custom_field->name) ?>" 
                data-key="<?= $custom_field->name ?>">
                    <?= $custom_field->name ?>
            </span>
        <?php 
        }*/
        ?>
    </div>
</div>
<script type="text/javascript">
    var cp_id = JSON.parse( '<?php echo json_encode( $cp_id ); ?>' );
</script>
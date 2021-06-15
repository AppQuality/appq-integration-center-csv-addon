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
?>
<form id="csv_tracker_settings">
    <div class="container">
        <div class="row">
            <div class="col col-md-10">
                <h3 class="mt-1 mb-3">CSV Exporter</h3>
                
                <?php
                $cp_id = $campaign_id;

                if ( empty( $cp_id ) ) {
                ?>

                <div class="alert alert-warning" role="alert">
                    In order to be able to export CSVs you should specify the <strong>Campaign ID</strong> first.
                </div>
                
                <?php 
                } else {
                    if ( CsvInspector::has_bugs( $cp_id ) ) {
                        $this->partial( "fields", array( "cp_id" => $cp_id ) );
                        $this->partial( "save-settings" );
                    } else {
                        ?>

                        <div class="alert alert-warning" role="alert">
                        In order to be able to export CSVs your campaign should have <strong>Bugs</strong> first.
                        </div>

                        <?php  
                    }
                }
                ?>

            </div>
        </div>
    </div>
</form>
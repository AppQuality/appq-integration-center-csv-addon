<?php
$endpoint = isset( json_decode( $campaign->bugtracker->endpoint )->endpoint ) ? json_decode( $campaign->bugtracker->endpoint )->endpoint : $campaign->bugtracker->endpoint;
$project  = isset( json_decode( $campaign->bugtracker->endpoint )->project ) ? json_decode( $campaign->bugtracker->endpoint )->project : false;

?>
<div class="row d-flex">
	<div class="col-1 d-flex-vertical-center">
        <?php
        printf(
            '<img id="integration-logo" src="%s" alt="%s">',
            APPQ_INTEGRATION_CENTER_CSV_URL . 'admin/assets/img/icon-csv.png',
            $campaign->bugtracker->integration
        );
        ?>
	</div>
	<div class="col d-flex-vertical-center">
        <?php
        $admin = new AppQ_Integration_Center_Admin('appq-integration-center', APPQ_INTEGRATION_CENTERVERSION);
        $admin->current_setup_edit_buttons($campaign)
        ?>
	</div>
</div>


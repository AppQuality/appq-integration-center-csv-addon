<?php
$endpoint = isset( json_decode( $campaign->bugtracker->endpoint )->endpoint ) ? json_decode( $campaign->bugtracker->endpoint )->endpoint : $campaign->bugtracker->endpoint;
$project  = isset( json_decode( $campaign->bugtracker->endpoint )->project ) ? json_decode( $campaign->bugtracker->endpoint )->project : false;

?>
<div class="row d-flex">
	<div class="col-1 d-flex-vertical-center">
        <?php if ($campaign->bugtracker->endpoint === "csv_format") :?>
			<img id="integration-logo" src="<?= APPQ_INTEGRATION_CENTER_CSV_URL . 'admin/assets/img/icon-csv.png'?>">
        <?php else :?>
			<img id="integration-logo" src="<?= APPQ_INTEGRATION_CENTER_CSV_URL . 'admin/assets/img/icon-xml.png'?>">
        <?php endif;?>
	</div>
	<div class="col d-flex-vertical-center">
        <?php
        $admin = new AppQ_Integration_Center_Admin('appq-integration-center', APPQ_INTEGRATION_CENTERVERSION);
        $admin->current_setup_edit_buttons($campaign)
        ?>
	</div>
</div>


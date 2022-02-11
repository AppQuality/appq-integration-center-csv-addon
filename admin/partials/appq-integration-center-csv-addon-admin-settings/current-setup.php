<?php
$endpoint = isset(json_decode($campaign->bugtracker->endpoint)->endpoint) ? json_decode($campaign->bugtracker->endpoint)->endpoint : $campaign->bugtracker->endpoint;
$project  = isset(json_decode($campaign->bugtracker->endpoint)->project) ? json_decode($campaign->bugtracker->endpoint)->project : false;

?>
<div class="row prevent-cols-breaking align-items-center">
        <div class="col-xs-10">
                <?php if ($campaign->bugtracker->endpoint === "csv_format") : ?>
                        <img width="75" id="integration-logo" src="<?= APPQ_INTEGRATION_CENTER_CSV_URL . 'admin/assets/img/icon-csv.png' ?>">
                <?php else : ?>
                        <img width="75" id="integration-logo" src="<?= APPQ_INTEGRATION_CENTER_CSV_URL . 'admin/assets/img/icon-xml.png' ?>">
                <?php endif; ?>
        </div>
        <div class="col-xs-2 flex flex-row justify-content-center align-items-end">
                <?php
                $admin = new AppQ_Integration_Center_Admin('appq-integration-center', APPQ_INTEGRATION_CENTERVERSION);
                $admin->current_setup_edit_buttons($campaign)
                ?>
        </div>
</div>
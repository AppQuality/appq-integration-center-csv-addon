<?php $api = new IntegrationCenterRestApi($campaign_id, null, null); ?>

<!-- Modal -->
<div class="modal" style="z-index: 99999;" id="add_mapping_field_modal" tabindex="-1" role="dialog" aria-labelledby="add_mapping_field_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div style="z-index: 99999;" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="add_mapping_field_modal_label"><?php _e('Add/edit field mapping', 'appq-integration-center-csv-addon'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body px-4">
        <div class="row">
          <div class="col">
            <div id="csv_mapping_field">
              <div class="form-group">
                <input type="hidden" name="key" id="mapping_modal_key">
                <?php
                printf('<label for="custom_mapping_name">%s</label>', __('Name', 'appq-integration-center-csv-addon'));
                printf('<input type="text" class="form-control" name="name" id="custom_mapping_name" placeholder="%s">', __('summary', 'appq-integration-center-csv-addon'));
                ?>
              </div>
              <div class="row mt-5 pb-4">
                <div class="col-6 col-lg-4 offset-lg-2 text-right">
                  <?php printf('<button type="button" id="add_new_mapping_field" class="btn btn-primary">%s</button>', __('Save field', 'appq-integration-center-csv-addon')); ?>
                </div>
                <div class="col-6 col-lg-4">
                  <?php printf('<button type="button" class="btn btn-link" data-dismiss="modal" aria-label="%1$s">%1$s</button>', __('Cancel', 'appq-integration-center-csv-addon')); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

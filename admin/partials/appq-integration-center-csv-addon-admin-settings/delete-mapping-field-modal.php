<!-- Modal -->
<div class="modal" style="z-index: 99999;" id="delete_mapping_field_modal" tabindex="-1" role="dialog" aria-labelledby="reset_tracker_settings" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div style="z-index: 99999;" class="modal-content">
            <div class="modal-header">
                <?php printf('<h5 class="modal-title">%s</h5>', __('Delete this field?', $this->plugin_name)); ?>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form px-4">
                <div class="modal-form pb-4">
                    <form id="jira_delete_field">
                        <input type="hidden" name="field_key">
                        <div class="row mt-2">
                            <div class="col-6 col-lg-4 offset-lg-2 text-right">
                                <?php printf(
                                    '<button type="submit" id="delete_mapping_field" class="btn btn-primary confirm">%s</button>',
                                    __('Delete field', $this->plugin_name)
                                ); ?>
                            </div>
                            <div class="col-6 col-lg-4">
                                <?php printf('<button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="%1$s">%1$s</button>', __('Cancel', $this->plugin_name)); ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
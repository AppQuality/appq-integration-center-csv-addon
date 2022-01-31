				<div class="row py-2" data-row="<?= $_key ?>">
					<div class="col-2"><?= $_key ?></div>
					<div class="col-2" style="white-space:pre-wrap; word-break: break-all;"><?= $_item->value ? $_item->value : '' ?></div>
					<div class="col-4" style="white-space:pre-wrap; word-break: break-all;"><?= $_item->description ? $_item->description : '' ?></div>
					<div class="col-2 text-center"><?= $_selected_icon ?></div>
					<div class="col-2 text-right actions">
						<button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn btn-info btn-icon-toggle mr-1 edit-mapping-field"
								data-key="<?= esc_attr($_key); ?>" 
								data-value="<?= $_item->value ? esc_attr($_item->value) : '' ?>" 
								data-description="<?= $_item->description ? esc_attr($_item->description) : '' ?>" 
								data-selected="<?= $_item->selected ? esc_attr($_item->selected) : '' ?>">
							<i class="fa fa-pencil"></i>
						</button>
					</div>
				</div>
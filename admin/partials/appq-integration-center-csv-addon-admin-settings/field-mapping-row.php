				<div class="row py-2" data-row="<?= $item->key ?>">
					<div class="col-2"><?= $item->key ?></div>
					<div class="col-2" style="white-space:pre-wrap; word-break: break-all;"><?= $item->value ? $item->value : '' ?></div>
					<div class="col-4" style="white-space:pre-wrap; word-break: break-all;"><?= $item->description ? $item->description : '' ?></div>
					<div class="col-2 text-center"><?= $selected_icon ?></div>
					<div class="col-2 text-right actions">
						<button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn btn-info btn-icon-toggle mr-1 edit-mapping-field"
							data-key="<?= $item->key; ?>"
							data-value="<?= $item->value ? $item->value : '' ?>"
							data-description="<?= $item->description ? $item->description : '' ?>"
							data-selected="<?= $item->selected ? $item->selected : '' ?>">
								<i class="fa fa-pencil"></i>
						</button>
						<button data-toggle="modal" data-target="#delete_mapping_field_modal" type="button" class="btn btn-danger btn-icon-toggle delete-mapping-field"
							data-key="<?= $item->key ?>">
								<i class="fa fa-trash"></i>
						</button>
					</div>
				</div>

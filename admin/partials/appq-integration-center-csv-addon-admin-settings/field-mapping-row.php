<tr class="mapping-role" data-row="<?= $_key ?>">
	<td><?= $_key ?></td>
	<td><?= $_item->value ? $_item->value : '' ?></td>
	<td><?= $_item->description ? $_item->description : '' ?></td>
	<td><?= $_selected_icon ?></td>
	<td>
		<button data-toggle="modal" data-target="#add_mapping_field_modal" type="button" class="btn-link text-dark btn edit-mapping-field" data-key="<?= esc_attr($_key); ?>" data-value="<?= $_item->value ? esc_attr($_item->value) : '' ?>" data-description="<?= $_item->description ? esc_attr($_item->description) : '' ?>" data-selected="<?= $_item->selected ? esc_attr($_item->selected) : '' ?>">
			<i class="fa fa-pencil"></i>
		</button>
	</td>
</tr>
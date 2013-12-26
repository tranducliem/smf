<table cellspacing="0" border="1px">
    <thead>
        <tr>
            <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')) ?></th>
            <th><?php echo lang('feedback_manager:form_title') ?></th>
            <th class="collapse"><?php echo lang('feedback_manager:form_description') ?></th>
            <th><?php echo lang('feedback_manager:form_start_date') ?></th>
            <th><?php echo lang('feedback_manager:form_end_date') ?></th>
            <th><?php echo lang('feedback_manager:form_type_id') ?></th>
            <th><?php echo lang('feedback_manager:form_require') ?></th>
            <th><?php echo lang('feedback_manager:form_status') ?></th>
            <th width="180"><?php echo lang('global:actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($post as $item) : ?>
            <tr>
                <td><?php echo form_checkbox('action_to[]', $item->id) ?></td>
                <td><?php echo $item->title ?></td>
                <td class="collapse"><?php echo $item->description ?></td>
                <td class="collapse"><?php echo $item->start_date ?></td>
                <td class="collapse"><?php echo $item->end_date ?></td>
                <td class="collapse"><?php echo $item->type_id ?></td>
                <td class="collapse"><?php echo $item->require ?></td>
                <td class="collapse"><?php echo $item->status ?></td>
                <td style="padding-top:10px;">
                    <a href="<?php echo site_url('feedback_manager/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button"><?php echo lang('global:edit')?></a>
                    <a href="<?php echo site_url('feedback_manager/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php $this->load->view('admin/partials/pagination') ?>
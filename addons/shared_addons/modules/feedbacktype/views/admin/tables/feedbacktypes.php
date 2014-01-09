 <table cellspacing="0">
    <thead>
        <tr>
            <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')) ?></th>
            <th><?php echo lang('feedbacktype:form_title') ?></th>
            <th class="collapse"><?php echo lang('feedbacktype:form_description') ?></th>
            <th width="180"><?php echo lang('global:actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($types as $item) : ?>
            <tr>
                <td><?php echo form_checkbox('action_to[]', $item->id) ?></td>
                <td><?php echo $item->title ?></td>
                <td class="collapse"><?php echo $item->description ?></td>
                <td style="padding-top:10px;">
                    <a href="<?php echo site_url('admin/feedbacktype/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button"><?php echo lang('global:edit')?></a>
                    <a href="<?php echo site_url('admin/feedbacktype/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php $this->load->view('admin/partials/pagination') ?>
<br>
<div class="table_action_buttons">
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))) ?>
</div>
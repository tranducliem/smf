 <table cellspacing="0">
    <thead>
        <tr>
            <th width="20"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')) ?></th>
            <th><?php echo lang('feedbackuser:manager_title') ?></th>
            <th class="collapse"><?php echo lang('feedbackuser:user_name') ?></th>
            <th class="collapse"><?php echo lang('feedbackuser:date') ?></th>
            <th class="collapse"><?php echo lang('feedbackuser:status') ?></th>
            <th width="180"><?php echo lang('global:actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($feedbackusers as $item) : ?>
            <tr>
                <td><?php echo form_checkbox('action_to[]', $item->id) ?></td>
                <td><?php echo $item->title ?></td>
                <td class="collapse"><?php echo $item->username ?></td>
                <td class="collapse"><?php echo $item->date ?></td>
                <td class="collapse"><?php echo $item->status ?></td>
                <td style="padding-top:10px;">
                    <a href="<?php echo site_url('admin/feedbackuser/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button"><?php echo lang('global:edit')?></a>
                    <a href="<?php echo site_url('admin/feedbackuser/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
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
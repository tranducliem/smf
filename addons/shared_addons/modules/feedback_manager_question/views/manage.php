<a href="<?php echo site_url('feedback_manager_question/create') ?>" title="<?php echo lang('global:create')?>" class="button"><button class="btn-u">Create Feedback Question</button></a>
<br/><br/>
<table cellspacing="0" border="1px">
    <thead>
    <tr>
        <th>Id</th>
        <th><?php echo lang('feedback_manager_question:form_feedback_manager_id') ?></th>
        <th class="collapse"><?php echo lang('feedback_manager_question:form_question_id') ?></th>
        <th width="180"><?php echo lang('global:actions') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php echo $this->load->view('admin/partials/filters') ?>
    <?php foreach ($post as $item) : ?>
        <tr>
            <td><?php echo $item->id ?></td>
            <td><?php echo $item->feedback_manager_id ?></td>
            <td class="collapse"><?php echo $item->question_id ?></td>

            <td style="padding-top:10px;">
                <a href="<?php echo site_url('feedback_manager_question/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button">
                    <button class="btn-u"><?php echo lang('global:edit')?></button>
                </a>
                <a href="<?php echo site_url('feedback_manager_question/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm">
                    <button class="btn-u"><?php echo lang('global:delete')?></button></a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<?php $this->load->view('admin/partials/pagination') ?>

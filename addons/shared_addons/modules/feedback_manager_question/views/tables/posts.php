<a class="btn-u" href="<?php echo site_url('feedback_manager_question/create') ?>" title="<?php echo lang('global:create')?>" class="button">Create Feedback Question</a>
<br/><br/>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Id</th>
        <th><?php echo lang('feedback_manager_question:form_feedback_manager') ?></th>
        <th class="collapse"><?php echo lang('feedback_manager_question:form_question') ?></th>
        <th width="180"><?php echo lang('global:actions') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($post as $item) : ?>
        <tr>
            <td><?php echo $item->id ?></td>
            <td><?php echo $item->title ?></td>
            <td class="collapse"><?php echo $item->q_title ?></td>

            <td style="padding-top:10px;">
                <a class="btn-u" href="<?php echo site_url('feedback_manager_question/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button">
                    <?php echo lang('global:edit')?>
                </a>
                <a class="btn-u" href="<?php echo site_url('feedback_manager_question/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm">
                    <?php echo lang('global:delete')?></a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<?php if(!empty($pagination['links'])): ?>
    <div class="paginate">
        <?php echo $pagination['links'];?>
    </div>
<?php endif; ?>
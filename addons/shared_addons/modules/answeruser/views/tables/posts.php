<table class="table table-striped">
    <thead>
    <tr>
        <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')) ?></th>
        <th><?php echo lang('answeruser:user_name');?></th>
        <th><?php echo lang('answeruser:answer_title');?></th>
        <th><?php echo lang('global:action');?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($posts as $item): ?>
    <tr>
        <td><?php echo form_checkbox('action_to[]', $item->id) ?></td>
        <td><?php echo $item->username; ?></td>
        <td><?php echo $item->title; ?></td>
        <td>
            <a href="<?php echo site_url('answeruser/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button"><?php echo lang('global:edit')?></a> |
            <a href="<?php echo site_url('answeruser/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if(!empty($pagination['links'])): ?>
    <div class="paginate">
        <?php echo $pagination['links'];?>
    </div>
<?php endif; ?>

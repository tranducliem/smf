<table class="table table-striped">
    <thead>
    <tr>
        <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all')) ?></th>
        <th><?php echo lang('team:form_title');?></th>
        <th><?php echo lang('team:form_description');?></th>
        <th><?php echo lang('team:form_company_id');?></th>
        <th><?php echo lang('global:action');?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($posts as $item): ?>
    <tr>
        <td><?php echo form_checkbox('action_to[]', $item->id) ?></td>
        <td><?php echo $item->title; ?></td>
        <td><?php echo $item->description; ?></td>
        <td><?php echo $item->company; ?></td>
        <td>
            <a href="<?php echo site_url('team/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button"><?php echo lang('global:edit')?></a> |
            <a href="<?php echo site_url('team/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm"><?php echo lang('global:delete')?></a>
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

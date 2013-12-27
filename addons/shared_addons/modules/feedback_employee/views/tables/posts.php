<a class="btn-u" href="<?php echo site_url('feedback_employee/create') ?>" title="<?php echo lang('global:create')?>" class="button">Create Feedback</a>
<br/><br/>
<table cellspacing="0" border="1px">
    <thead>
    <tr>
        <th>Id</th>
        <th><?php echo lang('feedback_employee:form_title') ?></th>
        <th class="collapse"><?php echo lang('feedback_employee:form_description') ?></th>
        <th><?php echo lang('feedback_employee:form_date') ?></th>
        <th><?php echo lang('feedback_employee:form_apply_id') ?></th>
        <th><?php echo lang('feedback_employee:form_department_id') ?></th>
        <th><?php echo lang('feedback_employee:form_status') ?></th>
        <th width="180"><?php echo lang('global:actions') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($post as $item) : ?>
        <tr>
            <td><?php echo $item->id ?></td>
            <td><?php echo $item->title ?></td>
            <td class="collapse"><?php echo $item->description ?></td>
            <td class="collapse"><?php echo $item->date ?></td>
            <td class="collapse"><?php echo $item->apply_id ?></td>
            <td class="collapse"><?php echo $item->department_id ?></td>
            <td class="collapse">
                <a href="" class="btn-u">
                    <?php
                    if($item->status == 0) echo "Not start";
                    else if($item->status == 1) echo "Processing";
                    else echo "Done";
                    ?>
                </a>
            </td>
            <td style="padding-top:10px;">
                <a class="btn-u" href="<?php echo site_url('feedback_employee/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button">
                    <?php echo lang('global:edit')?>
                </a>
                <a class="btn-u" href="<?php echo site_url('feedback_employee/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm">
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

<a class="btn-u" href="<?php echo site_url('feedback_manager/create') ?>" title="<?php echo lang('global:create')?>" class="button">Create Feedback</a>
<br/><br/>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Id</th>
        <th><?php echo lang('feedback_manager:form_title') ?></th>
        <th class="collapse"><?php echo lang('feedback_manager:form_description') ?></th>
        <th><?php echo lang('feedback_manager:form_start_date') ?></th>
        <th><?php echo lang('feedback_manager:form_end_date') ?></th>
        <th><?php echo lang('feedback_manager:type') ?></th>
        <th><?php echo lang('feedback_manager:form_require') ?></th>
        <th><?php echo lang('feedback_manager:form_status') ?></th>
        <th width="180"><?php echo lang('global:actions') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($post as $item) : ?>
        <tr>
            <td><?php echo $item->id ?></td>
            <td><?php echo $item->title ?></td>
            <td class="collapse"><?php echo $item->description ?></td>
            <td class="collapse"><?php echo $item->start_date ?></td>
            <td class="collapse"><?php echo $item->end_date ?></td>
            <td class="collapse"><?php echo $item->type_title ?></td>
            <td class="collapse">
                <button class="btn-u">
                    <?php
                    if($item->require == 0) echo "Not require";
                    else echo "Require";
                    ?>
                </button>
            </td>
            <td class="collapse">
                <button class="btn-u">
                    <?php
                    if($item->status == 0) echo "Not start";
                    else if($item->status == 1) echo "Processing";
                    else echo "Done";
                    ?>
                </button>
            </td>
            <td style="padding-top:10px;">
                <a class="btn-u" href="<?php echo site_url('feedback_manager/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button">
                    <?php echo lang('global:edit')?>
                </a>
                <a class="btn-u" href="<?php echo site_url('feedback_manager/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm">
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

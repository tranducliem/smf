<a href="<?php echo site_url('feedback_manager/create') ?>" title="<?php echo lang('global:create')?>" class="button"><button class="btn-u">Create Feedback</button></a>
<br/><br/>
<table cellspacing="0" border="1px">
    <thead>
    <tr>
        <th>Id</th>
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
    <?php echo $this->load->view('admin/partials/filters') ?>
    <?php foreach ($post as $item) : ?>
        <tr>
            <td><?php echo $item->id ?></td>
            <td><?php echo $item->title ?></td>
            <td class="collapse"><?php echo $item->description ?></td>
            <td class="collapse"><?php echo $item->start_date ?></td>
            <td class="collapse"><?php echo $item->end_date ?></td>
            <td class="collapse"><?php echo $item->type_id ?></td>
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
                <a href="<?php echo site_url('feedback_manager/edit/' . $item->id) ?>" title="<?php echo lang('global:edit')?>" class="button">
                    <button class="btn-u"><?php echo lang('global:edit')?></button>
                </a>
                <a href="<?php echo site_url('feedback_manager/delete/' . $item->id) ?>" title="<?php echo lang('global:delete')?>" class="button confirm">
                    <button class="btn-u"><?php echo lang('global:delete')?></button></a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<?php $this->load->view('admin/partials/pagination') ?>

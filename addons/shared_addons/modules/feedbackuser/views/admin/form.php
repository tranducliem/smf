<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('feedbackuser:create_title') ?></h4>
    <?php else: ?>
        <h4><?php echo sprintf(lang('feedbackuser:edit_title'), $feedback_manager_user->id) ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart() ?>
            <div class="tabs">

                <ul class="tab-menu">
                    <li><a href="#category-content-tab"><span><?php echo lang('feedbackuser:content_label') ?></span></a></li>
                </ul>

                <!-- Content tab -->
                <div class="form_inputs" id="category-content-tab">
                    <fieldset>
                        <ul>
                            <li>
                                <label for="feedback_manager_id"><?php echo lang('feedbackuser:manager_title') ?> <span>*</span></label>
                                <select name="feedback_manager_id" data-placeholder="Choose Feedback manager..." class="chosen-select" style="width:350px;" tabindex="2">
                                    <option value=""></option>
                                    <?php foreach($feedbackmanagers as $item) { ?>
                                    <option value="<?=$item->id?>"<?php if($feedback_manager_user->feedback_manager_id == $item->id) echo ' selected'; ?>><?=$item->title?></option>
                                    <?php } ?>
                                </select>
                            </li>
                            <li>
                                <label for="user_id"><?php echo lang('feedbackuser:user_name') ?> <span>*</span></label>
                                <select name="user_id" data-placeholder="Choose user..." class="chosen-select" style="width:350px;" tabindex="2">
                                    <option value=""></option>
                                    <?php foreach($users as $item) { ?>
                                    <option value="<?=$item->id?>"<?php if($feedback_manager_user->user_id == $item->id) echo ' selected'; ?>><?=$item->username?></option>
                                    <?php } ?>
                                </select>
                            </li>
                            <li>
                                <label for="status"><?php echo lang('feedbackuser:status') ?> <span>*</span></label>
                                <select name="status" data-placeholder="Choose status..." class="chosen-select" style="width:350px;" tabindex="2">
                                    <option value=""></option>
                                    <option value="1">status 1</option>
                                    <option value="2">status 2</option>
                                    <option value="3">status 3</option>
                                </select>
                            </li>
                        </ul>
                    </fieldset>
                </div>

            </div>

            <input type="hidden" name="row_edit_id" value="<?php if ($this->method != 'create'): echo $feedback_manager_user->id; endif; ?>" />
            <div class="buttons">
                <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>
            </div>
        <?php echo form_close() ?>
    </div>
</section>
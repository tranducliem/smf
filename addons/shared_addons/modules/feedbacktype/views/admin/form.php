<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('feedbacktype:create_title') ?></h4>
    <?php else: ?>
        <h4><?php echo sprintf(lang('feedbacktype:edit_title'), $feedback_manager_type->title) ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart() ?>
            <div class="tabs">

                <ul class="tab-menu">
                    <li><a href="#category-content-tab"><span><?php echo lang('feedbacktype:content_label') ?></span></a></li>
                </ul>

                <!-- Content tab -->
                <div class="form_inputs" id="category-content-tab">
                    <fieldset>
                        <ul>
                            <li>
                                <label for="title"><?php echo lang('feedbacktype:form_title') ?> <span>*</span></label>
                                <div class="input"><?php echo form_input('title', htmlspecialchars_decode($feedback_manager_type->title), 'maxlength="100" id="title"') ?></div>
                            </li>

                            <li>
                                <label for="description"><?php echo lang('feedbacktype:form_description') ?> <span>*</span></label>
                                <div class="input"><?php echo form_input('description', $feedback_manager_type->description, 'maxlength="100" class="width-20"') ?></div>
                            </li>
                        </ul>
                    </fieldset>
                </div>

            </div>

            <input type="hidden" name="row_edit_id" value="<?php if ($this->method != 'create'): echo $feedback_manager_type->id; endif; ?>" />
            <div class="buttons">
                <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>
            </div>
        <?php echo form_close() ?>
    </div>
</section>
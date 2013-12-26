<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('feedback_manager_question:create_title'); ?></h4>
    <?php else: ?>
        <h4><?php echo lang('feedback_manager_question:edit_title'); ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart() ?>
        <div class="tabs">

            <ul class="tab-menu">
                <li><a href="#book-content-tab"><span><?php echo lang('feedback_manager_question:content_label') ?></span></a></li>
            </ul>

            <!-- Content tab -->
            <div class="form_inputs" id="book-content-tab">
                <fieldset>
                    <ul>
                        <li>
                            <label for="slug"><?php echo lang('feedback_manager_question:form_feedback_manager') ?> <span>*</span></label>
                            <div class="input">
                                <select name="feedback_manager_id">
                                    <?php foreach ($fm as $item): ?>
                                    <option id="A" value="<?php echo $item->id?>"><?php echo $item->title?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('feedback_manager_question:form_question') ?> <span>*</span></label>
                            <div class="input">
                                <select name="question_id">
                                    <?php foreach ($question as $item2): ?>
                                        <option id="A" value="<?php echo $item2->id?>"><?php echo $item2->title?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </li>
                    </ul>
                </fieldset>
            </div>

        </div>

        <input type="hidden" name="row_edit_id" value="<?php if ($this->method != 'create'): echo $post->id; endif; ?>" />
        <div class="buttons">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save_exit'))) ?>
        </div>
        <?php echo form_close() ?>
    </div>
</section>

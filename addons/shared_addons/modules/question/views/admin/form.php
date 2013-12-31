<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('question:create_title') ?></h4>
    <?php else: ?>
        <h4><?php echo sprintf(lang('question:edit_title'), $post->title) ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart() ?>
            <div class="tabs">

                <ul class="tab-menu">
                    <li><a href="#question-content-tab"><span><?php echo lang('question:content_label') ?></span></a></li>
                </ul>

                <!-- Content tab -->
                <div class="form_inputs" id="question-content-tab">
                    <fieldset>
                        <ul>
                            <li>
                                <label for="title"><?php echo lang('question:form_title') ?> <span>*</span></label>
                                <div class="input">
                                    <?php echo form_input(array('id'=>'title',
                                                                'value'=> htmlspecialchars_decode($post->title),
                                                                'maxlength'=>"100",
                                                                'name'=>"title",
                                                                $this->method == 'create'?'':'readonly')) ?></div>
                            </li>
                            
                            <li>
                                <label for="description"><?php echo lang('question:form_description') ?></label>
                                <div class="input"><?php echo form_input('description', $post->description, 'maxlength="100" class="width-20"') ?></div>
                            </li>
                        </ul>
                    </fieldset>
                </div>

            </div>

            <input type="hidden" name="row_edit_id" value="<?php if ($this->method != 'create'): echo $post->id; endif; ?>" />
            <div class="buttons">
                <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>

            </div>
        <?php echo form_close() ?>
    </div>
</section>
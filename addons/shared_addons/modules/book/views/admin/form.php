<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('book:create_title') ?></h4>
    <?php else: ?>
        <h4><?php echo sprintf(lang('book:edit_title'), $post->title) ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart() ?>
            <div class="tabs">

                <ul class="tab-menu">
                    <li><a href="#book-content-tab"><span><?php echo lang('book:content_label') ?></span></a></li>
                </ul>

                <!-- Content tab -->
                <div class="form_inputs" id="book-content-tab">
                    <fieldset>
                        <ul>
                            <li>
                                <label for="title"><?php echo lang('book:form_title') ?> <span>*</span></label>
                                <div class="input"><?php echo form_input('title', htmlspecialchars_decode($post->title), 'maxlength="100" id="title"') ?></div>
                            </li>

                            <li>
                                <label for="slug"><?php echo lang('book:form_author') ?> <span>*</span></label>
                                <div class="input"><?php echo form_input('author', $post->author, 'maxlength="100" class="width-20"') ?></div>
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
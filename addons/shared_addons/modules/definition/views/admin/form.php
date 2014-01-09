<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('definition:create_title') ?></h4>
    <?php else: ?>
        <h4><?php echo sprintf(lang('definition:edit_title'), $post->title) ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart() ?>
            <div class="tabs">

                <ul class="tab-menu">
                    <li><a href="#definition-content-tab"><span><?php echo lang('definition:content_label') ?></span></a></li>
                </ul>

                <!-- Content tab -->
                <div class="form_inputs" id="definition-content-tab">
                    <fieldset>
                        <ul>
                            <li>
                                <label for="title"><?php echo lang('definition:form_title') ?> <span>*</span></label>
                                <div class="input"><?php echo form_input('title', htmlspecialchars_decode($post->title), 'maxlength="100" id="title"') ?></div>
                            </li>
                            <li style="<?php if ($this->method != 'create'): echo 'display: none;'; endif; ?>">
                                <label for="title"><?php echo lang('definition:form_slug') ?> <span>*</span></label>
                                <div class="input"><?php echo form_input('slug', htmlspecialchars_decode($post->slug), 'maxlength="100"') ?></div>
                            </li>
                            <li>
                                <label for="slug"><?php echo lang('definition:form_description') ?> <span>*</span></label>
                                <div class="edit-content">
                                    <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => htmlspecialchars_decode($post->description), 'rows' => 30, 'class' => 'wysiwyg-advanced')) ?>
                                </div>
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
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('feedback_manager:create_title') ?></h4>
    <?php else: ?>
        <h4><?php echo sprintf(lang('feedback_manager:edit_title'), $post->title) ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart() ?>
        <div class="tabs">

            <ul class="tab-menu">
                <li><a href="#book-content-tab"><span><?php echo lang('feedback_manager:content_label') ?></span></a></li>
            </ul>

            <!-- Content tab -->
            <div class="form_inputs" id="book-content-tab">
                <fieldset>
                    <ul>
                        <li>
                            <label for="title"><?php echo lang('feedback_manager:form_title') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('title', htmlspecialchars_decode($post->title), 'maxlength="100" id="title"') ?></div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('feedback_manager:form_description') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('description', $post->description, 'maxlength="100" class="width-20"') ?></div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('feedback_manager:form_start_date') ?> <span>*</span></label>
                            <div class="input"><input type="text" name="start_date" id="datepicker1"/></div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('feedback_manager:form_end_date') ?> <span>*</span></label>
                            <div class="input"><input type="text" name="end_date" id="datepicker2"/></div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('feedback_manager:form_type_id') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('type_id', $post->type_id, 'maxlength="100" class="width-20"') ?></div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('feedback_manager:form_require') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('require', $post->require, 'maxlength="100" class="width-20"') ?></div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('feedback_manager:form_status') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('status', $post->status, 'maxlength="100" class="width-20"') ?></div>
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

<script type="text/javascript">
    (function($){
        $(function(){
            $( "#datepicker1" ).datepicker(
                {
                    dateFormat: 'yy-mm-dd'
                }
            );
            $( "#datepicker2" ).datepicker(
                {
                    dateFormat: 'yy-mm-dd'
                }
            );
        });
    })(jQuery);
</script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<div class="span9">
    <div class="headline">
        <?php if ($this->method == 'create'): ?>
            <h3><?php echo lang('feedback_manager:create_title') ?></h3>
        <?php else: ?>
            <h3><?php echo sprintf(lang('feedback_manager:edit_title'), $post->title) ?></h3>
        <?php endif ?>
    </div>

    <section class="item">
        <div class="content">
            <?php echo form_open_multipart() ?>
            <div class="tabs">
                <div class="form_inputs" id="book-content-tab">
                    <fieldset>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('feedback_manager:form_title') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('title', htmlspecialchars_decode($post->title), 'maxlength="100" id="title"') ?></div>
                        </div>
                        <div class="control-group">
                            <label class="control-label"><?php echo lang('feedback_manager:form_description') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('description', $post->description, 'maxlength="100" class="width-20"') ?></div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?php echo lang('feedback_manager:form_start_date') ?> <span>*</span></label>
                            <div class="input"><input type="text" name="start_date" id="datepicker1"/></div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?php echo lang('feedback_manager:form_end_date') ?> <span>*</span></label>
                            <div class="input"><input type="text" name="end_date" id="datepicker2"/></div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?php echo lang('feedback_manager:form_type_id') ?> <span>*</span></label>
                            <div class="input">
                                <select name="type_id">
                                    <?php foreach ($test as $item): ?>
                                        <option id="A" value="<?php echo $item->id?>"><?php echo $item->title?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?php echo lang('feedback_manager:form_require') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('require', $post->require, 'maxlength="100" class="width-20"') ?></div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?php echo lang('feedback_manager:form_status') ?> <span>*</span></label>
                            <div class="input"><?php echo form_input('status', $post->status, 'maxlength="100" class="width-20"') ?></div>
                        </div>
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
</div>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Input Team -->
<?php echo form_open('feedback_manager/process', array('class' => 'ajax_submit_form'))?>
<input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
<input type="hidden" id="action" name="action" value="create"/>
<label>{{ helper:lang line="feedback_manager:form_title" }}<span class="require">*</span></label>
<?php echo form_input('title', '', 'id="title" class="span6 border-radius-none" placeholder="Title input"') ?>
<label>{{ helper:lang line="feedback_manager:form_description" }}<span class="require">*</span></label>
<?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => '', 'rows' => 6, 'class' => 'span12 border-radius-none margin-bottom-10')) ?>
<label>{{ helper:lang line="feedback_manager:form_start_date" }}<span class="require">*</span></label>
<input type="text" name="start_date" id="datepicker1"/>
<label>{{ helper:lang line="feedback_manager:form_end_date" }}<span class="require">*</span></label>
<input type="text" name="end_date" id="datepicker2"/>
<label>{{ helper:lang line="feedback_manager:form_type_id" }}<span class="require">*</span></label>
<?php echo form_dropdown('type_id', $feedback_manager_types, '', 'id="type_id" class="span6"')?>
<label>{{ helper:lang line="feedback_manager:form_require" }}<span class="require">*</span></label>
<?php echo form_input('require', '', 'id="require" class="span6 border-radius-none" placeholder="Require input"') ?>
<label>{{ helper:lang line="feedback_manager:form_status" }}<span class="require">*</span></label>
<?php echo form_input('status', '', 'id="status" class="span6 border-radius-none" placeholder="Status input"') ?><br>
<button type="submit" id="btnSubmit" class="btn-u pull-left">Create feedback_manager</button>
<?php echo form_close()?>

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
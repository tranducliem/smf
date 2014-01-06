<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Input feedback manager -->
<?php echo form_open('feedback_manager/process', array('class' => 'ajax_submit_form'))?>
<input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
<input type="hidden" id="action" name="action" value="create"/>

<!-- Title -->
<label>{{ helper:lang line="feedback_manager:form_title" }}<span class="require">*</span></label>
<?php echo form_input('title', '', 'id="title" class="span6 border-radius-none" placeholder="Title input"') ?>

<!-- Description -->
<label>{{ helper:lang line="feedback_manager:form_description" }}<span class="require">*</span></label>
<?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => '', 'rows' => 6, 'class' => 'span12 border-radius-none margin-bottom-10')) ?>

<!-- Start date -->
<label>{{ helper:lang line="feedback_manager:form_start_date" }}<span class="require">*</span></label>
<input type="text" name="start_date" id="datepicker1"/>

<!-- End date -->
<label>{{ helper:lang line="feedback_manager:form_end_date" }}<span class="require">*</span></label>
<input type="text" name="end_date" id="datepicker2"/>

<!-- Type id -->
<label>{{ helper:lang line="feedback_manager:form_type_id" }}<span class="require">*</span></label>
<?php echo form_dropdown('type_id', $feedback_manager_types, '', 'id="type_id" class="span6"')?>

<!-- Require -->
<label>{{ helper:lang line="feedback_manager:form_require" }}<span class="require">*</span></label>
    <select name="require" data-placeholder="Choose require" class="span6" tabindex="2">
        <option value="0">Require</option>
        <option value="1">Not require</option>
    </select>

<!-- Status -->
<label>{{ helper:lang line="feedback_manager:form_status" }}<span class="require">*</span></label>
    <select name="status" data-placeholder="Choose status" class="span6" tabindex="2">
        <option value="0">Not start</option>
        <option value="1">Processing</option>
        <option value="2">Done</option>
    </select>

<!-- Apply user -->
<label>{{ helper:lang line="feedback_manager:form_apply" }}<span class="require">*</span></label>
<?php echo form_dropdown('apply_id', $users, '', 'id="apply_id" class="span6"')?>
    <br>

<!-- Validate date -->
<button type="submit" id="btnSubmit" class="btn-u pull-left">Create feedback_manager</button>
<?php echo form_close()?>

<script type="text/javascript">

    (function($){
        $(function(){
            $( "#datepicker1" ).datepicker(
                {
                    dateFormat: 'yy-mm-dd',
                    onClose: function( selectedDate ) {
                        $( "#datepicker2" ).datepicker( "option", "minDate", selectedDate );
                    }
                }
            );
            $( "#datepicker2" ).datepicker(
                {
                    dateFormat: 'yy-mm-dd',
                    onClose: function( selectedDate ) {
                        $( "#datepicker1" ).datepicker( "option", "maxDate", selectedDate );
                    }
                }
            );
        });
    })(jQuery);
</script>
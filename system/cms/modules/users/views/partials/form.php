<!-- Input Team -->
<?php echo form_open('employee/process', array('class' => 'ajax_submit_form'))?>
    <input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
    <input type="hidden" id="action" name="action" value="create"/>
    <label>{{ helper:lang line="employee:form_title" }}<span class="require">*</span></label>
    <?php echo form_input('title', '', 'id="title" class="span6 border-radius-none" placeholder="Title input"') ?>
    <label>{{ helper:lang line="employee:form_description" }}<span class="require">*</span></label>
    <?php echo form_textarea(array('id' => 'description', 'name' => 'description', 'value' => '', 'rows' => 6, 'class' => 'span12 border-radius-none margin-bottom-10')) ?>
    <label>{{ helper:lang line="employee:form_date" }}<span class="require">*</span></label>
    <input type="text" name="date" id="datepicker1"/>
    <label>{{ helper:lang line="employee:form_department_id" }}<span class="require">*</span></label>
    <?php echo form_dropdown('department_id', $departments, '', 'id="department_id" class="span6" onchange="get_user_by_department()"')?>
    <label>{{ helper:lang line="employee:form_apply_id" }}<span class="require">*</span></label>
    <?php foreach ($users as $id => $value) {
        $department_id = get_department_id_by_id($id);
    }?>

    <?php echo form_dropdown('apply_id', $users, '', 'id="apply_id" class="span6"')?>
    <label>{{ helper:lang line="employee:form_status" }}<span class="require">*</span></label>
    <select name="status" data-placeholder="Choose status" class="span6" tabindex="2">
        <option value="0">Not start</option>
        <option value="1">Processing</option>
        <option value="2">Done</option>
    </select>
    <br>
    <button type="submit" id="btnSubmit" class="btn-u pull-left">Create employee</button>
<?php echo form_close()?>

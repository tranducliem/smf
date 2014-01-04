<!-- Input Team -->
<?php echo form_open('employee/process', array('class' => 'ajax_submit_form'))?>
    <input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
    <input type="hidden" id="action" name="action" value="create"/>

    <?php if ( ! Settings::get('auto_username')): ?>
        <label for="username">{{ helper:lang line="user:username" }} <span class="color-red">*</span></label>
        <?php echo form_input('username', '', 'id="username" class="span12" placeholder="Username input" maxlength="100"') ?>
    <?php endif ?>

    <label>{{ helper:lang line="global:email" }} <span class="color-red">*</span></label>
    <?php echo form_input('email', '', 'id="email" class="span12" placeholder="Email input" maxlength="100"') ?>

    <label>{{ helper:lang line="employee:form_department" }}<span class="require">*</span></label>
    <?php echo form_dropdown('department_id', $departments, '', 'id="department_id" class="span6" onchange="get_user_by_department()"')?>

    <br>
    <button type="submit" id="btnSubmit" class="btn-u pull-left">Create employee</button>
<?php echo form_close()?>

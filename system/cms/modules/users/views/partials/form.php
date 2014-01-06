<!-- Input Team -->
<?php echo form_open('employee/process', array('class' => 'ajax_submit_form'))?>
    <input type="hidden" id="row_edit_id" name="row_edit_id" value=""/>
    <input type="hidden" id="action" name="action" value="create"/>

    <?php if ( ! Settings::get('auto_username')): ?>
        <label for="username">{{ helper:lang line="user:username" }} <span class="color-red">*</span></label>
        <?php echo form_input('username', '', 'id="username" class="span12" placeholder="Username input" maxlength="100"') ?>
    <?php endif ?>

    <label>{{ helper:lang line="global:email" }} <span class="color-red">*</span></label>
    <?php echo form_input('email', '', 'id="email" class="span6 border-radius-none" placeholder="Email input" maxlength="100"') ?>
    <input class="default-form span6" type="text" style="display:none" value=" " name="d0ntf1llth1s1n">

    <label>{{ helper:lang line="employee:form_first_name" }} <span class="color-red">*</span></label>
    <?php echo form_input('first_name', '', 'id="first_name" class="span6 border-radius-none" placeholder="First name input" maxlength="100"') ?>

    <label>{{ helper:lang line="employee:form_last_name" }} <span class="color-red">*</span></label>
    <?php echo form_input('last_name', '', 'id="last_name" class="span6 border-radius-none" placeholder="Last name input" maxlength="100"') ?>

    <label>{{ helper:lang line="employee:form_department" }}<span class="require">*</span></label>
    <?php echo form_dropdown('department_id', $departments, '', 'id="department_id" class="span6"')?>

    <label>{{ helper:lang line="employee:form_team" }}<span class="require">*</span></label>
    <?php echo form_dropdown('team_id', $teams, '', 'id="team_id" class="span6"')?>

    <br>
    <button type="submit" id="btnSubmit" class="btn-u pull-left">{{ helper:lang line="employee:create_btn_title" }}</button>
<?php echo form_close()?>

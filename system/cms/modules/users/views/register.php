{{ session:messages success="success-box" notice="notice-box" error="error-box" }}

<?php if ( ! empty($error_string)):?>
    <div class="error-box">
        <?php echo $error_string;?>
    </div>
<?php endif;?>


<div class="row-fluid margin-bottom-10">
    <?php echo form_open('register', array('id' => 'register', 'class' => 'reg-page')) ?>
    <h3><?php echo lang('user:register_header') ?></h3>
    <p id="step_register">
        <span id="active_step"><?php echo lang('user:register_step1') ?></span> -&gt;
        <span><?php echo lang('user:register_step2') ?></span>
    </p>

    <div class="controls">
        <?php if ( ! Settings::get('auto_username')): ?>
            <label for="username"><?php echo lang('user:username') ?> <span class="color-red">*</span></label>
            <input type="text" name="username" class="span12" maxlength="100" value="<?php echo $_user->username ?>" />
        <?php endif ?>
        <label for="email"><?php echo lang('global:email') ?> <span class="color-red">*</span></label>
        <input type="text" name="email" class="span12" maxlength="100" value="<?php echo $_user->email ?>" />
        <?php echo form_input('d0ntf1llth1s1n', ' ', 'class="default-form span12" style="display:none"') ?>
        <?php foreach($profile_fields as $field) { if($field['required'] and $field['field_slug'] != 'display_name') { ?>
            <label for="<?php echo $field['field_slug'] ?>">
                <?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?>
                <span class="color-red">*</span>
            </label>
            <?php echo $field['input'] ?>
        <?php } } ?>
    </div>

    <div class="controls">
        <div class="span6">
            <label for="password"><?php echo lang('global:password') ?> <span class="color-red">*</span></label>
            <input type="password" name="password" class="span12" maxlength="100" />
        </div>
        <div class="span6">
            <label>Confirm Password <span class="color-red">*</span></label>
            <input type="password" name="cpassword" class="span12" maxlength="100" />
        </div>
    </div>

    <div class="controls form-inline">
        <label class="checkbox"><input type="checkbox" name="condition" />&nbsp; I read <a href="">Terms and Conditions</a></label>
        <button class="btn-u pull-right" type="submit"><?php echo lang('user:register_btn'); ?></button>
    </div>
    <hr />
    <p>Already Signed Up? Click <a href="<?php echo site_url('login');?>" class="color-green">Sign In</a> to login your account.</p>
    <?php echo form_close(); ?>
</div><!--/row-fluid-->
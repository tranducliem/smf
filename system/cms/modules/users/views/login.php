<?php
$email = array(
    'name'        => 'email',
    'id'          => 'email',
    'value'       => $this->input->post('email') ? $this->input->post('email') : '',
    'maxlength'   => '30',
    'class'       => 'input-xlarge',
    'placeholder' => lang('global:email'),
);
?>

{{ session:messages success="success-box" notice="notice-box" error="error-box" }}

<?php if (validation_errors()): ?>
    <div class="error-box">
        <?php echo validation_errors();?>
    </div>
<?php endif ?>


<div class="row-fluid">
    <?php echo form_open('users/login', array('id'=>'login', 'class'=>'log-page'), array('redirect_to' => $redirect_to)) ?>
    <h3><?php echo lang('user:login_header') ?></h3>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-user"></i></span>
        <?php  echo form_input($email); ?>
    </div>
    <div class="input-prepend">
        <span class="add-on"><i class="icon-lock"></i></span>
        <input type="password" id="password" name="password" class="input-xlarge" maxlength="20" placeholder="<?php echo lang('global:password') ?>" />
    </div>
    <div class="controls form-inline">
        <label class="checkbox"><?php echo form_checkbox('remember', '1', false) ?> <?php echo lang('user:remember') ?></label>
        <button class="btn-u pull-right" type="submit"><?php echo lang('user:login_btn') ?></button>
    </div>
    <hr />
    <h4><?php echo lang('user:reset_password_link');?></h4>
    <p>no worries, <a class="color-green" href="<?php echo base_url().'users/reset_pass'?>">click here</a> <?php echo lang('user:reset_password_link');?> or <?php echo anchor('register', lang('user:register_btn'));?>.</p>
    <?php echo form_close() ?>
</div><!--/row-fluid-->
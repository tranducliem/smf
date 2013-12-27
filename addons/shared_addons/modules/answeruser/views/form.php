<section class="title">
    <?php if ($this->method == 'create'): ?>
        <div class="headline"><h3><?php echo lang('answeruser:create_title');?></h3></div>
    <?php else: ?>
       <div class="headline"><h3><?php echo lang('answeruser:edit_title_label');?></h3></div>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart() ?>
            <div class="tabs">

                <ul class="tab-menu">
                    <li><a href="#category-content-tab"><span><?php echo lang('feedbackuser:content_label') ?></span></a></li>
                </ul>

                <!-- Content tab -->
                <div class="form_inputs" id="category-content-tab">
                    <fieldset>
                        <ul>
                            <li>
                                <label for="answer_id"><?php echo lang('answeruser:answer_title') ?> <span>*</span></label>
                                <select name="answer_id" data-placeholder="Choose answer..." class="chosen-select" style="width:350px;" tabindex="2">
                                    <option value=""></option>
                                    <?php foreach($answers as $item) { ?>
                                    <option value="<?=$item->id?>"<?php if($answer_user->answer_id == $item->id) echo ' selected'; ?>><?=$item->title?></option>
                                    <?php } ?>
                                </select>
                            </li>
                            <li>
                                <label for="user_id"><?php echo lang('answeruser:user_name') ?> <span>*</span></label>
                                <select name="user_id" data-placeholder="Choose user..." class="chosen-select" style="width:350px;" tabindex="2">
                                    <option value=""></option>
                                    <?php foreach($users as $item) { ?>
                                    <option value="<?=$item->id?>"<?php if($answer_user->user_id == $item->id) echo ' selected'; ?>><?=$item->username?></option>
                                    <?php } ?>
                                </select>
                            </li>
                        </ul>
                    </fieldset>
                </div>

            </div>

            <input type="hidden" name="row_edit_id" value="<?php if ($this->method != 'create'): echo $answer_user->id; endif; ?>" />
            <div class="buttons">
                <button type="submit" class="btn-u">Save</button>
            </div>
        <?php echo form_close() ?>
    </div>
</section>
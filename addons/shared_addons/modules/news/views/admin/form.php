<script type="text/javascript" language="JavaScript">
    function changeFolder(ctrl) {
        var id = $(ctrl).val();
        $('#imageSelector').show();
        if(id != '0'){
            $('#imageSelected').hide();
            $.ajax({
                type: "POST",
                url: BASE_URL + "admin/news/get_image_by_folder/" + id,
                data: {}
            }).done(function (data) {
                data = data.trim();
                $('#imageSelector').html(data);
            });
        }else{
            //$('#imageSelected').show();
            $('#imageSelector').html("");
            $('#imageSelector').hide();
        }
    }

    function selectThumbnail(imgId) {
        $('#thumbnail').val(imgId);
        $('#imageSelector').hide();
        $('#imageSelected').html('<img class="image_selected" src="'+BASE_URL+'files/large/'+imgId+'">');
        $('#imageSelected').show();
    }

</script>

<style>
    ul#imageSelector {
        height: auto;
        max-height: 250px;
        width: 100%;
        overflow: scroll;
        display: none;
        list-style: none outside none;
    }

    ul#imageSelector li {
        float: left;
        margin: 0 12px 12px 0;
        line-height: 20px;
    }

    ul#imageSelector li .thumb_img {
        border: 1px solid #DDDDDD;
        padding: 6px;
        display: block;
        line-height: 20px;
        transition: all 0.2s ease-in-out 0s;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
        -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
    }

    ul#imageSelector li .thumb_img > img.image_thumbnail {
        display: block;
        margin-left: auto;
        margin-right: auto;
        max-width: 100%;
    }

    ul#imageSelector li .thumb_img img.image_thumbnail {
        border: 0 none;
        vertical-align: middle;
        width: 125px;
        height: 125px;
    }

    ul#imageSelector li .thumb_img:hover{
        cursor: pointer;
        background-color: #0088CC;
    }

    #imageSelected {
        display: none;
        border: 1px solid #DDDDDD;
        width: 125px;
        padding: 6px;
        line-height: 20px;
        transition: all 0.2s ease-in-out 0s;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
        -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.055);
    }

    img.image_selected{
        border: 0 none;
        vertical-align: middle;
        width: 125px;
        height: 125px;
    }

</style>

<section class="title">
    <?php if ($this->method == 'create'): ?>
        <h4><?php echo lang('news:create_title') ?></h4>
    <?php else: ?>
        <h4><?php echo sprintf(lang('news:edit_title'), $post->title) ?></h4>
    <?php endif ?>
</section>

<section class="item">
    <div class="content">

        <?php echo form_open_multipart() ?>

        <div class="tabs">

            <ul class="tab-menu">
                <li><a href="#news-content-tab"><span><?php echo lang('news:content_label') ?></span></a></li>
                <?php if ($stream_fields): ?>
                    <li><a href="#news-custom-fields"><span><?php echo lang('global:custom_fields') ?></span></a>
                    </li><?php endif; ?>
                <li><a href="#news-options-tab"><span><?php echo lang('news:options_label') ?></span></a></li>
            </ul>

            <!-- Content tab -->
            <div class="form_inputs" id="news-content-tab">
                <fieldset>
                    <ul>
                        <li>
                            <label for="title"><?php echo lang('global:title') ?> <span>*</span></label>

                            <div
                                class="input"><?php echo form_input('title', htmlspecialchars_decode($post->title), 'maxlength="100" id="title"') ?></div>
                        </li>

                        <li>
                            <label for="slug"><?php echo lang('global:slug') ?> <span>*</span></label>

                            <div
                                class="input"><?php echo form_input('slug', $post->slug, 'maxlength="100" class="width-20"') ?></div>
                        </li>
                        <li>
                            <label for="thumbnail"><?php echo lang('global:thumbnail') ?></label>

                            <div class="input">
                                <input type="hidden" id="thumbnail" name="thumbnail" value="no-image.jpg"/>
                                <?php echo form_dropdown('folder', $folders, '0', 'onchange="changeFolder(this)"') ?>
                                <div id="imageSelected" <?php if( ($this->method != 'create') && ($post->thumbnail != "")){ echo 'style="display: block;"';}?> >
                                    <?php if ($this->method != 'create'): ?>
                                        <?php if($post->thumbnail != ""):?>
                                            <img class="image_selected" src="<?php echo base_url();?>files/large/<?php echo $post->thumbnail;?>">
                                        <?php endif;?>
                                    <?php endif ?>
                                </div>
                            </div>
                            <ul id="imageSelector">
                                &nbsp;Loading...
                            </ul>
                        </li>
                        <li>
                            <label for="status"><?php echo lang('news:status_label') ?></label>

                            <div
                                class="input"><?php echo form_dropdown('status', array('draft' => lang('news:draft_label'), 'live' => lang('news:live_label')), $post->status) ?></div>
                        </li>

                        <li class="editor">
                            <label for="body"><?php echo lang('news:content_label') ?> <span>*</span></label><br>

                            <div class="input small-side">
                                <?php echo form_dropdown('type', array(
                                    'html' => 'html',
                                    'markdown' => 'markdown',
                                    'wysiwyg-simple' => 'wysiwyg-simple',
                                    'wysiwyg-advanced' => 'wysiwyg-advanced',
                                ), $post->type) ?>
                            </div>

                            <div class="edit-content">
                                <?php echo form_textarea(array('id' => 'body', 'name' => 'body', 'value' => $post->body, 'rows' => 30, 'class' => $post->type)) ?>
                            </div>
                        </li>

                    </ul>
                    <?php echo form_hidden('preview_hash', $post->preview_hash) ?>
                </fieldset>
            </div>

            <?php if ($stream_fields): ?>

                <div class="form_inputs" id="news-custom-fields">
                    <fieldset>
                        <ul>

                            <?php foreach ($stream_fields as $field) echo $this->load->view('admin/partials/streams/form_single_display', array('field' => $field), true) ?>

                        </ul>
                    </fieldset>
                </div>

            <?php endif; ?>

            <!-- Options tab -->
            <div class="form_inputs" id="news-options-tab">
                <fieldset>
                    <ul>

                        <li>
                            <label for="category_id"><?php echo lang('news:category_label') ?></label>

                            <div class="input">
                                <?php echo form_dropdown('category_id', array(lang('news:no_category_select_label')) + $categories, @$post->category_id) ?>
                                [ <?php echo anchor('admin/news/categories/create', lang('news:new_category_label'), 'target="_blank"') ?>
                                ]
                            </div>
                        </li>

                        <?php if (!module_enabled('keywords')): ?>
                            <?php echo form_hidden('keywords'); ?>
                        <?php else: ?>
                            <li>
                                <label for="keywords"><?php echo lang('global:keywords') ?></label>
                                <div class="input"><?php echo form_input('keywords', $post->keywords, 'id="keywords"') ?></div>
                            </li>
                        <?php endif; ?>

                        <li class="date-meta">
                            <label><?php echo lang('news:date_label') ?></label>

                            <div class="input datetime_input">
                                <?php echo form_input('created_on', date('Y-m-d', $post->created_on), 'maxlength="10" id="datepicker" class="text width-20"') ?>
                                &nbsp;
                                <?php echo form_dropdown('created_on_hour', $hours, date('H', $post->created_on)) ?> :
                                <?php echo form_dropdown('created_on_minute', $minutes, date('i', ltrim($post->created_on, '0'))) ?>
                            </div>
                        </li>

                        <?php if (!module_enabled('comments')): ?>
                            <?php echo form_hidden('comments_enabled', 'no'); ?>
                        <?php else: ?>
                            <li>
                                <label for="comments_enabled"><?php echo lang('news:comments_enabled_label'); ?></label>

                                <div class="input">
                                    <?php echo form_dropdown('comments_enabled', array(
                                        'no' => lang('global:no'),
                                        '1 day' => lang('global:duration:1-day'),
                                        '1 week' => lang('global:duration:1-week'),
                                        '2 weeks' => lang('global:duration:2-weeks'),
                                        '1 month' => lang('global:duration:1-month'),
                                        '3 months' => lang('global:duration:3-months'),
                                        'always' => lang('global:duration:always'),
                                    ), $post->comments_enabled ? $post->comments_enabled : '3 months') ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </fieldset>
            </div>

        </div>

        <input type="hidden" name="row_edit_id"
               value="<?php if ($this->method != 'create'): echo $post->id; endif; ?>"/>

        <div class="buttons">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'save_exit', 'cancel'))) ?>
        </div>

        <?php echo form_close() ?>

    </div>
</section>
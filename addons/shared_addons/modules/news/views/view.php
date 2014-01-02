

{{ post }}

<div class="span9">
        <div class="blog margin-bottom-30">
	<h3>{{ title }}</h3>

	<div class="meta">
                <ul class="unstyled inline blog-info">
                    <li><i class="icon-calendar"></i> {{ helper:date timestamp=created_on }}</li>
                    <li><i class="icon-pencil"></i><a href="{{ url:site }}user/{{ created_by:user_id }}">{{ created_by:display_name }}</a></li>
                    <li><i class="icon-comments"></i> <a href="#">24 Comments</a></li>
                </ul>
		{{ if keywords || category }}
                <ul class="unstyled inline blog-tags">
                    <li>
                        <i class="icon-tags"></i>
                        <a href="{{ url:site }}news/category/{{ category:slug }}">{{ category:title }}</a>
                        {{ keywords }}
				<a href="{{ url:site }}news/tagged/{{ keyword }}">{{ keyword }}</a>
			{{ /keywords }}
                    </li>
                </ul>
		{{ endif }}

	</div>

	<div class="body">
		{{ body }}
	</div>
        </div><!--/blog-->


{{ /post }}

<?php if (Settings::get('enable_comments')): ?>

<div id="comments">

	<div id="existing-comments">
            <h3 class="color-green"><?php echo lang('comments:title') ?></h3>
            <div class="media">
            <?php echo $this->comments->display() ?>
            </div>
	</div>
        <div class="post-comment">
            <?php if ($form_display): ?>
                    <?php echo $this->comments->form() ?>
            <?php else: ?>
            <?php echo sprintf(lang('news:disabled_after'), strtolower(lang('global:duration:'.str_replace(' ', '-', $post[0]['comments_enabled'])))) ?>
            <?php endif ?>
        </div><!--/post-comment-->
</div>

<?php endif ?>
</div>
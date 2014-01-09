<!-- Left Sidebar -->
<div class="span9">
    {{ post }}
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
            <div class="blog-img"><img src="assets/img/posts/1.jpg" alt="" /></div>
            <p></p>
            <div class="body">
                {{ body }}
            </div>
        </div><!--/blog-->
    {{ /post }}

    <?php if (Settings::get('enable_comments')): ?>
        <!-- Media -->
        <div class="media">
            <h3 class="color-green"><?php echo lang('comments:title') ?></h3>
        </div><!--/media-->

        <hr />

        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="assets/img/sliders/elastislide/9.jpg" alt="" />
            </a>
            <?php echo $this->comments->display() ?>
        </div><!--/media-->

        <?php if ($form_display): ?>
            <!-- Leave a Comment -->
            <div class="post-comment">
                <h3 class="color-green">Write a Comment</h3>
                <?php echo $this->comments->form() ?>
            </div><!--/post-comment-->
        <?php else: ?>
            <?php echo sprintf(lang('news:disabled_after'), strtolower(lang('global:duration:'.str_replace(' ', '-', $post[0]['comments_enabled'])))) ?>
        <?php endif ?>

    <?php endif; ?>
</div><!--/span9-->
<div class="span9">
    {{ if posts }}
        {{ posts }}
        <div class="blog margin-bottom-30">
            <h3><a href="{{ url }}">{{ title }}</a></h3>
            <ul class="unstyled inline blog-info">
                {{ if category }}
                <li><i class="icon-list"></i> <a href="{{ url:site }}news/category/{{ category:slug }}">{{ category:title }}</a></li>
                {{ endif }}
                <li><i class="icon-calendar"></i> {{ helper:lang line="news:posted_label" }}: {{ helper:date timestamp=created_on }}</li>
                <li><i class="icon-pencil"></i> <?php echo get_username_by_id(1);?></li>
                <li><i class="icon-comments"></i> <a href="#">24 Comments</a></li>
            </ul>
            {{ if keywords }}
            <ul class="unstyled inline blog-tags">
                <li>
                    <i class="icon-tags"></i>
                    {{ keywords }}
                    <a href="{{ url:site }}news/tagged/{{ keyword }}">{{ keyword }}</a>
                    {{ /keywords }}
                </li>
            </ul>
            {{ endif }}

            <div class="blog-img">
                <a href="{{ url }}" title="{{ title }}">
                    <img src="{{ url:site }}files/large/{{ thumbnail }}" alt="{{ title }}"/>
                </a>
            </div>
            {{ preview }}
            <p><a class="btn-u btn-u-small" href="{{ url }}">{{ helper:lang line="news:read_more_label" }}</a></p>
        </div><!--/news-->
        <div class="clear"></div>
        {{ /posts }}
        {{ pagination }}
    {{ else }}
        {{ helper:lang line="news:currently_no_posts" }}
    {{ endif }}
</div><!--/span9-->
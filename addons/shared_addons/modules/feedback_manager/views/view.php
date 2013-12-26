{{ post }}
    <div class="post">
        <h3>{{ title }}</h3>
        <div class="meta">
            <div class="date">
                {{ helper:lang line="feedback_manager:posted_label" }}
                <span>{{ helper:date timestamp=created }}</span>
            </div>

            <div class="author">
                {{ helper:lang line="feedback_manager:written_by_label" }}
                <span><a href="{{ url:site }}user/{{ created_by:user_id }}">{{ created_by:display_name }}</a></span>
            </div>

            {{ if keywords }}
                <div class="keywords">
                    {{ keywords }}
                        <span><a href="{{ url:site }}blog/tagged/{{ keyword }}">{{ keyword }}</a></span>
                    {{ /keywords }}
                </div>
            {{ endif }}
        </div>
    </div>
{{ /post }}

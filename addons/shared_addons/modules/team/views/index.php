
{{ if posts }}

    {{ posts }}
        <div class="post">
            <h3><a style="color: #0186ba;text-decoration: none"href="{{ url }}">{{ name }}</a></h3>
            <h4>{{desc}}</h4>
            <div class="meta">
                <div class="date">
                    {{ helper:lang line="dict:posted_label" }}
                    <span>{{ helper:date timestamp=created }}</span>
                </div>

                {{ if keywords }}
                    <div class="keywords">
                        {{ keywords }}
                            <span><a href="{{ url:site }}dict/tagged/{{ keyword }}">{{ keyword }}</a></span>
                        {{ /keywords }}
                    </div>
                {{ endif }}

            </div>
            <p><a href="{{ url }}">{{ helper:lang line="dict:detail_label" }}</a></p>
        </div>

    {{ /posts }}
    {{ pagination }}

{{ else }}

    {{ helper:lang line="dict:currently_no_posts" }}

{{ endif }}
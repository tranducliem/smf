{{ if posts }}

    {{ posts }}

        <div class="post">
            <h3><a href="{{ url }}">{{ title }}</a></h3>
            <div class="meta">
                <div class="date">
                    {{ helper:lang line="book:posted_label" }}
                    <span>{{ helper:date timestamp=created }}</span>
                </div>

                {{ if keywords }}
                    <div class="keywords">
                        {{ keywords }}
                            <span><a href="{{ url:site }}book/tagged/{{ keyword }}">{{ keyword }}</a></span>
                        {{ /keywords }}
                    </div>
                {{ endif }}

            </div>
            <p><a href="{{ url }}">{{ helper:lang line="book:detail_label" }}</a></p>
        </div>

    {{ /posts }}

    {{ pagination }}

{{ else }}

    {{ helper:lang line="book:currently_no_posts" }}

{{ endif }}
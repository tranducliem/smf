<div class="span9">
    <div class="headline"><h3>Default Styles with Zebra-Striping</h3></div>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ helper:lang line="team:form_title" }}</th>
                <th>{{ helper:lang line="team:form_description" }}</th>
                <th>Company</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Mark</td>
                <td>Otto</td>
                <td>@mdo</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Jacob</td>
                <td>Thornton</td>
                <td>@fat</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Larry</td>
                <td>the Bird</td>
                <td>@twitter</td>
            </tr>
        </tbody>
    </table>

    <div class="pagination pagination-right">
        <ul>
            <li class="disabled"><a href="#">Prev</a></li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li class="active"><a>3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li><a href="#">6</a></li>
            <li><a href="#">7</a></li>
            <li><a href="#">8</a></li>
            <li><a href="#">Next</a></li>
        </ul>
    </div>

    {{ if posts }}

        {{ posts }}

            <tr>
                <td>1</td>
                <td>{{ title }}</td>
                <td>{{ description }}</td>
                <td>@mdo</td>
            </tr>

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
</div>
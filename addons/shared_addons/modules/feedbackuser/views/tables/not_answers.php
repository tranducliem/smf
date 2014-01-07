<table class="table table-striped">
    <thead>
    <tr>
        <th>{{ helper:lang line="feedbackuser:form_title" }}</th>
        <th>{{ helper:lang line="feedbackuser:form_description" }}</th>
        <th>{{ helper:lang line="feedbackuser:form_start_date" }}</th>
        <th>{{ helper:lang line="feedbackuser:form_end_date" }}</th>
        <th>{{ helper:lang line="feedbackuser:form_type_id" }}</th>
        <th>{{ helper:lang line="feedbackuser:form_require" }}</th>
        <th>{{ helper:lang line="feedbackuser:form_status" }}</th>
    </tr>
    </thead>
    <tbody>
    {{if posts }}
        {{ posts }}
            <tr>
                <td><a href="javascript:void(0);" onclick="feedback_details({{ id }})" title="Answer Feedback" class="button">{{ title }}</a></td>
                <td>{{ description }}</td>
                <td>{{ start_date }}</td>
                <td>{{ end_date }}</td>
                <td>{{ type }}</td>
                <td>{{ require }}</td>
                <td>{{ status }}</td>
            </tr>
        {{ /posts }}    
    {{ else }}
        <tr><td colspan="7">{{ helper:lang line="feedbackuser:all_answer" }}</td></tr>
    {{ endif }}
    
    </tbody>
</table>
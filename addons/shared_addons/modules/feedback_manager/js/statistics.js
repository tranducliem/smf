function statistics(id) {
    $.ajax({
        type: "POST",
        url: BASE_URL + "feedback_manager/statistic/"+id,
        data: {},
        success: function(data) {
            var data= $.parseJSON(data);
            var percent=[];
            for (var i=0;i<data["count_answer"];i++){
                percent=percent.concat([[data["statistics"][i]["name"], data["statistics"][i]["percent"]]]);
            }
            $('#container_statistics').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: data["title"]
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                        type: 'pie',
                        name: 'Statistics Question',
                        data: percent
                    }]
            });
            $('#tab-1').removeClass('active');
            $('#tab_list').removeClass('active');
            $('#tab-2').removeClass('active');
            $('#tab_form1').removeClass('active');
            $('#tab_form2').removeClass('active');
            $('#tab-3').removeClass('active');
            $('#tab_form3').addClass('active');
            $('#tab-4').addClass('active');
        },
        error: function(xhr) {
            console.log("Error: " + xhr.message);
        }
    });

}


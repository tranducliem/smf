function statistics() {
    $.ajax({
        type: "POST",
        url: BASE_URL + "question/statistic",
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
                        name: 'Browser share',
                        data: percent
                    }]
            });
        },
        error: function(xhr) {
            console.log("Error: " + xhr.message);
        }
    });

}


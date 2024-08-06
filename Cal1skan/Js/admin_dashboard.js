document.addEventListener("DOMContentLoaded", function() {
    var options = {
        series: [{
            name: 'This Month',
            data: [31, 40, 28, 51, 42, 109, 100]
        }, {
            name: 'Last Month',
            data: [11, 32, 45, 32, 34, 52, 41]
        }],
        chart: {
            height: 350,
            type: 'area'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            type: 'datetime',
            categories: ['2021-09-19T00:00:00.000Z', '2021-09-20T00:00:00.000Z', '2021-09-21T00:00:00.000Z', '2021-09-22T00:00:00.000Z', '2021-09-23T00:00:00.000Z', '2021-09-24T00:00:00.000Z', '2021-09-25T00:00:00.000Z']
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#fulfillment-chart"), options);
    chart.render();
});

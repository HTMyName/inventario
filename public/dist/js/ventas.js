/* Chart.js Charts */
// Sales chart
var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');
//$('#revenue-chart').get(0).getContext('2d');

if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp = new XMLHttpRequest();

} else {  // code for IE6, IE5
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}

xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        if (this.responseText != "") {
            var myObj = JSON.parse(this.responseText);
            var datap = myObj.productos;
            var datas = myObj.servicios;

            //console.log(myObj);

            var salesChartData = {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [
                    {
                        label: 'Productos',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: true,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [
                            datap[0], datap[1], datap[2], datap[3], datap[4], datap[5],
                            datap[6], datap[7], datap[8], datap[9], datap[10], datap[11]
                        ]
                    },
                    {
                        label: 'Servicios',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: true,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [
                            datas[0], datas[1], datas[2], datas[3], datas[4], datas[5],
                            datas[6], datas[7], datas[8], datas[9], datas[10], datas[11]
                        ]
                    },
                ]
            }
        }
        var salesChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            datasetFill: false,
            legend: {
                display: true
            },
            scales: {
                xAxes: [{
                    stacked: false,
                    gridLines: {
                        display: false,
                    }
                }],
                yAxes: [{
                    stacked: false,
                    ticks: {
                        beginAtZero: true,
                        maxTicksLimit: 10,
                    },
                    gridLines: {
                        display: true,
                    }
                }]
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 2,
            }
        }

// This will get the first returned node in the jQuery collection.
        var salesChart = new Chart(salesChartCanvas, {
                type: 'bar',
                data: salesChartData,
                options: salesChartOptions
            }
        )
    }
};

xmlhttp.open("GET", "home/sales", true);
xmlhttp.send();
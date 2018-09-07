jQuery(function($) {
    'use strict';

    $(function() {

        var frontapp = {
            vectorMap:false,
            init : function () {
                var strategyArchiveTabs = $('.post-type-archive-strategy .change-qtr-mth'),
                    strategyArchveActiveTab = strategyArchiveTabs.filter('.active');

                $(document)
                    .on('click', '.change-qtr-mth', frontapp.changeQtrMth)
                    .on('change', '.switch-strategy-table', frontapp.switchStrategyTable)
                    .on('change', '.switchFund', frontapp.switchFund)
                    .on('click', '#territory_managers .collapse-holder', frontapp.selectManager);

              //Init active tab on the archive page
              if(strategyArchveActiveTab.length){
                strategyArchveActiveTab.trigger('click');
              }

                $('.performance-diagr-barChart').each(frontapp.initBarChart);
                $('.performance-diagr-areaChart').each(frontapp.initTimeChart);
                $('.performance-diagr-horisontBarChart').each(frontapp.initHorisontBarChart);
                $('#holdChart').each(frontapp.initRoundChart);
                $('#shareChart').each(frontapp.initRoundChart);

                $(".back-link").click(function(event) {
                    event.preventDefault();
                    history.back(1);
                });
                if( $('#interactive_map').length ) {
                    var $map = $('#interactive_map');
                    $(window).resize(function(){
                        var w = $map.width();
                        var h = w / 1.5;
                        $map.height(h);
                    });
                    var w =$map.width();
                    var h = w / 1.5;
                    $map.height(h);
                    this.initInteractiveMap();
                }

            },
            switchFund : function (){
                var url = $(this).val();
                window.location.href = url;
            },
            switchStrategyTable : function () {
                var $tr = $(this).closest('tr'),
                    id  = $(this).val();
                $('.u-strategy--data', $tr).hide();
                // console.log($('#strategy-'+id+'-data', $tr));
                $('.strategy-'+id+'-data', $tr).show();
            },
            changeQtrMth : function (e) {
                var $link = $(this),
                    $tabHolder = $link.parents('.tab-holder'),
                    type = $link.data('type');


                $link.parents('.tabset-table').find('a').removeClass('active');
                $link.addClass('active');

                switch (type) {
                    case 'monthly':
                        $tabHolder.find('.strtg-mth-data').show();
                        $tabHolder.find('.strtg-qtr-data').hide();
                        break;
                    case 'quarterly':
                        $tabHolder.find('.strtg-mth-data').hide();
                        $tabHolder.find('.strtg-qtr-data').show();
                        break
                }

                return false;
            },
            initBarChart : function (i, e) {
                var type = $(this).data('chartopt');
                var id   = $(this).attr('id');
                var fundCanvas = document.getElementById(id);
                var totalData  = utheme_charts_args.chartData[type];
                var yAxis = totalData.yAxis;
                delete totalData.yAxis;
                Chart.defaults.global.defaultFontFamily = "TradeGothicLTStd";
                Chart.defaults.global.defaultFontSize = 12;
                Chart.defaults.global.defaultFontColor = '#000';

                var chartOptions = {
                    maintainAspectRatio: true,
                    // responsive: false,
                    responsive: false,
                    // aspectRatio: true,
                    scales: {
                        xAxes: [{
                            barPercentage: 1,
                            categoryPercentage: 0.6,
                            gridLines: {
                                drawBorder: false,
                                lineWidth: 2,
                                color: 'rgba(185, 199, 212, 0.5)',
                            }
                        }],
                        yAxes: [{
							display: true,
                            id: "y-axis-camwx",
                            gridLines: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                callback: function(tick, index, ticks) {
                                    return tick.toFixed(2);
                                  },
                                max: parseFloat(yAxis.max),
                                min: parseFloat(yAxis.min),
                                stepSize: parseFloat(yAxis.step),
                                beginAtZero: false,
                                padding: 20,
                            },
							scaleLabel: {
								display: true,
								labelString: 'PERFORMANCE (%)'
							}
                        }]
                    },
                    tooltips: {
                        showCaret: true,
                        backgroundColor: '#fff',
                        titleFontColor: '#000',
                        titleFontStyle: 'normal',
                        bodyFontColor: '#000',
                        borderColor: 'rgba(0,0,0, 0.5)',
                        borderWidth: 0.5,
                        showShadow: true,
                        shadowColor: '#000',
                        shadowBlur: 6,
                        shadowOffsetX: 2,
                        shadowOffsetY: 2,
                        mode: 'point',

                        custom: function (tooltip) {
                            if (!tooltip) return;
                            // disable displaying the color box;
                            tooltip.displayColors = false;
                        },
                    },
                    legend: {
                        position: 'top',
                        verticalAlign: "center",
                        labels: {
                            padding: 21,
                            boxWidth: 12,
                        }
                    }
                };

                 // bar-diagram desktop

                enquire.register("screen and (min-width:640px)", {
                    match: function () {
                        var barChart = new Chart(fundCanvas, {
                            type: 'bar',
                            data: totalData,
                            options: chartOptions
                        });
                    },
                    unmatch: function () {
                        $('#fundChart').remove();
                    }
                });
                // bar-diagram mobile

                enquire.register("screen and (max-width:639px)", {
                    match: function () {

                        var barChart = new Chart(fundCanvas, {
                            type: 'horizontalBar',
                            data: totalData,
                            options: chartOptions
                        });
                    },
                    unmatch: function () {
                        $('#fundChart').remove();
                    }
                });
            },
            initTimeChart : function (i, e) {
                var type = $(this).data('chartopt');
                var id   = $(this).attr('id');
                var growthCanvas = document.getElementById(id);
                Chart.defaults.global.defaultFontFamily = "TradeGothicLTStd";
                Chart.defaults.global.defaultFontSize = 12;
                Chart.defaults.global.defaultFontColor = '#000';
                var totalData  = utheme_charts_args.chartData[type],
                    chartData =  totalData.dataset;
                $.each(chartData, function(i, e){
                    chartData[i]['x'] = new Date(e.x);
                });

                var growthData = {
                    datasets: [{
                        fill: true,
                        borderWidth: 1,
                        lineTension: 0.1,
                        backgroundColor: "#00457c",
                        borderColor: "transparent",
                        pointBorderColor: 'transparent',
                        pointBackgroundColor: 'transparent',
                        pointRadius: 0,
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "#00457c",
                        pointHitRadius: 5,
                        pointBorderWidth: 1,
                        pointHoverBorderWidth: 1,
                        pointHoverBorderColor: '#fff',
                        data: chartData
                    }]
                };

                var chartOptions = {
                    segmentShowStroke: false,
                    scales: {
                        xAxes: [{
                            type: "time",
                            distribution: 'series',
                            time: {
                                unit: 'month',
                                unitStepSize: 1,
                                round: 'm',
                                tooltipFormat: 'MM/DD/YYYY',
                                displayFormats: {
                                    month: 'MM YY'
                                }
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false

                            }
                        }],
                        yAxes: [{
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 20,
                                stepSize: totalData.step,
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            }
                        }]
                    },
                    tooltips: {
                        backgroundColor: '#fff',
                        titleFontColor: '#000',
                        bodyFontColor: '#000',
                        borderColor: 'rgba(0,0,0, 0.5)',
                        borderWidth: 0.5,
                        custom: function (tooltip) {
                            if (!tooltip) return;
                            // disable displaying the color box;
                            tooltip.displayColors = false;
                        },
                        callbacks: {
                            label: function (tooltipItems, data) {
                                var value = data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index].value,
                                    percentage = data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index].percentage;

                              return percentage ? value + ' (' + percentage + ')' : value;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        labels: {
                            boxWidth: 0,
                            generateLabels: function(chart) {
                                var data = chart.data;
                                return Chart.helpers.isArray(data.datasets) ? data.datasets.map(function(dataset, i) {
                                    var lastObject = data.datasets[0].data[data.datasets[0].data.length - 1];
                                    return {
                                      text: "YTD " + lastObject.value,
                                      datasetIndex: i
                                    };

                                }, this) : [];
                            }
                        },
                        onClick: function (e) {
                            e.stopPropagation();
                        }
                    }
                };

                var lineChart = new Chart(growthCanvas, {
                    type: 'line',
                    data: growthData,
                    options: chartOptions
                });
            },
            initRoundChart : function() {
                var type = $(this).data('chartopt');
                var id   = $(this).attr('id');
                var fundCanvas = document.getElementById(id);
                var data  = utheme_charts_args.chartData[type];
                data.datasets[0]['borderWidth'] = 0;
                Chart.defaults.global.defaultFontFamily = "TradeGothicLTStd";
                Chart.defaults.global.defaultFontSize = 12;
                Chart.defaults.global.defaultFontColor = '#000';

                if (id === "holdChart") {
                    data.labels[data.labels.length] = 'blank';
                    data.datasets[0].backgroundColor[data.datasets[0].backgroundColor.length] = 'rgba(69, 85, 96, 0.25)';
                    // console.log(`summing: ${id}`);
                    var sum1 = +data.datasets[0].data.reduce(function(sum, item) {
                        return  Math.round((sum + +item) * 100) / 100;
                    }, 0);
                    // console.log(`sum(${id}): ${sum} ${100 - sum}`);
                    data.datasets[0].data[data.datasets[0].data.length] = 100 - sum1;
                    $('#holdChart').after("<div class='composition-round-text'><span class='precentage'>"+ sum1 +"%</span><span> of total <br>portfolio</span></div>");

                } else if (id === "shareChart") {

                    /*var sum2 = data.datasets[0].data.reduce((sum, item) => {
                        return sum + +item;
                    }, 0);*/
                    // console.log(data.labels)
                    data.labels =  ['total', 'blank'];
                    data.datasets[0].backgroundColor =  ['#00457c', 'rgba(69, 85, 96, 0.25)'];
                    // data.datasets[0].data[data.datasets[0].data.length] =  +data.datasets[0].data.splice(-1, [data.datasets[0].data.length], sum2);

                    // data.labels[data.labels.length] = 'blank';
                    // data.datasets[0].backgroundColor[data.datasets[0].backgroundColor.length] = 'rgba(69, 85, 96, 0.25)';
                    //data.datasets[0].data = [sum2, 100 - sum2];

                    var sum2 = data.datasets[0].data[0];
                    // console.log(data.datasets[0].data[0])
                    $('#shareChart').after("<div class='composition-round-text'>"+ sum2 +"%</div>");
                    // console.log(data.datasets[0].backgroundColor)
                }

                var cutOut1 = +'';
                if (id === "shareChart") {
                    cutOut1 = 75
                } else {
                    cutOut1 = 75
                }
                var holdChart = new Chart(fundCanvas, {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        legend: {
                            display: false
                        },
                        layout: {
                            padding: {
                                left: 0,
                                right: 0,
                                top: 0,
                                bottom: 0
                            }
                        },
                        tooltips: {
                            mode: 'nearest',
                            backgroundColor: '#fff',
                            titleFontColor: '#000',
                            bodyFontColor: '#000',
                            borderColor: 'rgba(0,0,0, 0.5)',
                            borderWidth: 0.5,
                            custom: function (tooltip) {
                                if (!tooltip) return;
                                tooltip.displayColors = false;
                            },
                            filter: function (tooltipItem, data) {
                                var label = data.labels[tooltipItem.index];
                                if (label == "blank") {
                                  return false;
                                } else {
                                  return true;
                                }
                            }
                        },
                        cutoutPercentage: cutOut1,
                    }
                });
            },
            initHorisontBarChart : function (e) {
                var type = $(this).data('chartopt');
                var id   = $(this).attr('id');
                var sectorCanvas = document.getElementById(id);
                var totalData  = utheme_charts_args.chartData[type];
                Chart.defaults.global.defaultFontFamily = "TradeGothicLTStd";
                Chart.defaults.global.defaultFontSize = 12;
                Chart.defaults.global.defaultFontColor = '#000';
                totalData.datasets[0]['borderWidth'] = 0;


                var chartOptions = {
                    scales: {
                        xAxes: [{
                            barPercentage: 1,
                            categoryPercentage: 0.6,
                            id: "x-axis-cambi",
                            gridLines: {
                                display: false
                            },
                            display: false
                        }],
                        yAxes: [{
                            gridLines: {
                                display: false
                            },
                            scaleLabel:
                                {display: false}
                        }]
                    },
                    legend: {
                        position: 'bottom'
                    },
                    tooltips: {
                        backgroundColor: '#fff',
                        titleFontColor: '#000',
                        bodyFontColor: '#000',
                        borderColor: 'rgba(0,0,0, 0.5)',
                        borderWidth: 0.5,
                        custom: function (tooltip) {
                            if (!tooltip) return;
                            // disable displaying the color box;
                            tooltip.displayColors = false;
                        },
                    },
                };
                var barChart = new Chart(sectorCanvas, {
                    type: 'horizontalBar',
                    data: totalData,
                    options: chartOptions
                });
            },
            selectManager : function (e){
                frontapp.vectorMap.reset();
                $('#territory_managers .collapse-holder').each(function (i,e) {
                    if( $(this).is('.collapsed') ){
                        var id = parseInt($(this).data('manager'));
                        var states = frontapp.getManamerStates(id);
                        frontapp.__highlightRegions(states);
                    }
                });
            },
            getManamerStates : function(manager_id){
                var states = false;
                if (typeof u_territory_managers.managers['_' + manager_id] !== 'undefined' && typeof u_territory_managers.managers['_' + manager_id]['states'] !== 'undefined') {

                    states = u_territory_managers.managers['_' + manager_id]['states'].slice(0);
                }
                return states;
            },
            __selectManager : function (manager_id){
                    var $manager = $('.collapsed-list .collapse-holder').removeClass('collapsed')
                        .filter('[data-manager='+manager_id+']');

                    $manager.addClass('collapsed');

                    if( $(window).width() < 640){
                        $('html, body').animate({
                            scrollTop: $manager.offset().top - 100
                        }, 1000)
                    }
            },
            __highlightRegions : function(states){
                var region = {};
                $.each(states, function (i, s) {
                    region[s] = '#b9c7d4';
                });
                frontapp.vectorMap.series.regions[0].setValues(region);
            },
            initInteractiveMap : function (e) {
                frontapp.vectorMap = new jvm.Map({
                    map: 'us_aea',
                    container         : $('#interactive_map'),
                    regionsSelectable : false,
                    zoomOnScroll      : false,
                    onRegionTipShow   : function(event, tip, code) {
                        if (typeof u_territory_managers.states[code] !== 'undefined') {
                            var manager_id = parseInt(u_territory_managers.states[code]);
                            if (typeof u_territory_managers.managers['_' + manager_id] !== 'undefined' && typeof u_territory_managers.managers['_' + manager_id]['tip'] !== 'undefined') {

                                var html = u_territory_managers.managers['_' + manager_id]['tip'];
                                if( html != ''){
                                    $(tip).html(html);
                                }
                            }
                        }
                    },
                    onRegionClick     : function(e, code){
                            if (typeof u_territory_managers.states[code] !== 'undefined') {
                                var manager_id = parseInt(u_territory_managers.states[code]);

                                    frontapp.__selectManager(manager_id);

                                var states = frontapp.getManamerStates(manager_id);
                                if( states ){
                                    frontapp.vectorMap.reset();
                                    frontapp.__highlightRegions(states);
                                }
                            }
                    },
                    series: {
                        regions: [{
                            attribute: 'fill'
                        }]
                    },
                    backgroundColor : 'white',
                    regionStyle : {
                        initial: {
                            fill: '#dce3e9',
                            "fill-opacity": 1,
                            stroke: 'none',
                            "stroke-width": 0,
                            "stroke-opacity": 1
                        },
                        hover: {
                            fill : '#b9c7d4',
                            cursor: 'pointer'
                        },
                        selected: {
                            fill : '#b9c7d4',
                            "fill-opacity": 1
                        },
                        selectedHover: {
                        }
                    },
                    regionLabelStyle: {
                        initial: {
                            fill: '#000000'
                        },
                    },
                    labels: {
                        regions: {
                            render: function(code){
                                return code.split('-')[1];
                            },
                            offsets: function(code){
                                return {
                                    'VT': [0, -35],
                                    'ME': [0, -5],
                                    'NH': [33, 10],
                                    'MA': [25, -5],
                                    'RI': [10, 15],
                                    'CT': [10, 25],
                                    'NJ': [23, 5],
                                    'DE': [25, 0],
                                    'MD': [53, 12],
                                    'DC': [45, 28],
                                    'CA': [-10, 10],
                                    'ID': [0, 40],
                                    'OK': [25, 0],
                                    'LA': [-20, 0],
                                    'FL': [45, 0],
                                    'KY': [10, 5],
                                    'VA': [15, 5],
                                    'MI': [30, 30],
                                    'AK': [50, -25],
                                    'HI': [25, 40]
                                }[code.split('-')[1]];
                            }
                        }
                    }
                });
                /*end initInteractiveMap*/
            }
        };

        frontapp.init();

    });

    $(window).on('load', function() {
        $('.preloader').fadeOut(function() {
            $('body')
                .removeClass('loading')
                .addClass('loaded');
        });
    });
}(jQuery));

/*=========================================================================================
    File Name: dashboard-ecommerce.js
    Description: dashboard-ecommerce
    ----------------------------------------------------------------------------------------
    Item Name: Chameleon Admin - Modern Bootstrap 4 WebApp & Dashboard HTML Template + UI Kit
    Version: 1.0
    Author: ThemeSelection
    Author URL: https://themeselection.com/
==========================================================================================*/
if (!Array.prototype.forEach ) {
    Array.prototype.forEach = function(fn, scope) {
        for(var i = 0, len = this.length; i < len; ++i) {
        fn.call(scope, this[i], i, this);
        }
    };
}

(function (window, document, $) {

    function serializeChartData(float){
        return Math.trunc(float);
    }

    /*************************************************
    *               Line gradient chart               *
    *************************************************/
    //chartist_contador = JSON.stringify(chartist_contador);
    var semana          = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab"];
    var contador        = [];
    contador["maior"]   = 0;
    contador["menor"]   = 0;
    contador["domingo"] = 0, contador['segunda'] = 0, contador['terca'] = 0, contador['quarta'] = 0, contador['quinta'] = 0, contador['sexta'] = 0, contador['sabado'] = 0;

    if(Array.isArray(chartist_contador)){
        chartist_contador.forEach(function(elem, index){
            let d = new Date(elem['data']);
            switch (semana[d.getDay()]) {
                case 'Dom':
                    contador['domingo']++;
                    break;
                case 'Seg':
                    contador['segunda']++;
                    break;
                case 'Ter':
                    contador['terca']++;
                    break;
                case 'Qua':
                    contador['quarta']++;
                    break;
                case 'Qui':
                    contador['quinta']++;
                    break;
                case 'Sex':
                    contador['sexta']++;
                    break;
                case 'Sab':
                    contador['sabado']++;
                    break;
            }
        });
    }

    contador['maior'] = Math.max(contador['domingo'], contador['segunda'],  contador['terca'], contador['quarta'], contador['quinta'], contador['sexta'], contador['sabado']);
    contador['menor'] = Math.min(contador['domingo'], contador['segunda'],  contador['terca'], contador['quarta'], contador['quinta'], contador['sexta'], contador['sabado']);

    var lineGradientChart1 = new Chartist.Line('#gradient-line-chart1', {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'],
        series: [
            
            [
                contador['segunda'], 
                contador['terca'], 
                contador['quarta'], 
                contador['quinta'], 
                contador['sexta'], 
                contador['sabado'], 
                contador['domingo']
            ],

            [
                serializeChartData(contador['maior'] - contador['segunda']), 
                serializeChartData(contador['maior'] - contador['terca']), 
                serializeChartData(contador['maior'] - contador['quarta']), 
                serializeChartData(contador['maior'] - contador['quinta']), 
                serializeChartData(contador['maior'] - contador['sexta']), 
                serializeChartData(contador['maior'] - contador['sabado']), 
                serializeChartData(contador['maior'] - contador['domingo'])
            ]
        ]
    }, {
            low: 100,
            fullWidth: true,
            onlyInteger: true,
            axisY: {
                low: 0,
                scaleMinSpace: 50,
            },
            axisX: {
                showGrid: false
            },
            lineSmooth: Chartist.Interpolation.simple({
                divisor: 2
            })
        });
    lineGradientChart1.on('created', function (data) {
        var defs = data.svg.querySelector('defs') || data.svg.elem('defs');
        defs.elem('linearGradient', {
            id: 'lineLinear1',
            x1: 0,
            y1: 0,
            x2: 1,
            y2: 0
        }).elem('stop', {
            offset: '0%',
            'stop-color': 'rgba(168,120,244,0.1)'
        }).parent().elem('stop', {
            offset: '10%',
            'stop-color': 'rgba(168,120,244,1)'
        }).parent().elem('stop', {
            offset: '80%',
            'stop-color': 'rgba(255,108,147, 1)'
        }).parent().elem('stop', {
            offset: '98%',
            'stop-color': 'rgba(255,108,147, 0.1)'
        });

        defs.elem('linearGradient', {
            id: 'lineLinear2',
            x1: 0,
            y1: 0,
            x2: 2,
            y2: 0
        }).elem('stop', {
            offset: '0%',
            'stop-color': 'rgba(230,42,0,0.1)'
        }).parent().elem('stop', {
            offset: '10%',
            'stop-color': 'rgba(230,42,0,1)'
        }).parent().elem('stop', {
            offset: '80%',
            'stop-color': 'rgba(255,68,69, 1)'
        }).parent().elem('stop', {
            offset: '98%',
            'stop-color': 'rgba(255,68,69, 0.1)'
        });

        return defs;


    }).on('draw', function (data) {
        var circleRadius = 10;
        if (data.type === 'point') {
            var circle = new Chartist.Svg('circle', {
                cx: data.x,
                cy: data.y,
                'ct:value': data.y,
                r: circleRadius,
                class: data.value.y === 225 ? 'ct-point ct-point-circle' : 'ct-point ct-point-circle-transperent'
            });
            data.element.replace(circle);
        }
        if (data.type === 'line') {
            data.element.animate({
                d: {
                    begin: 1000,
                    dur: 1000,
                    from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                    to: data.path.clone().stringify(),
                    easing: Chartist.Svg.Easing.easeOutQuint
                }
            });
        }
    });

    /*************************************************
  *               Project Stats               *
  *************************************************/

    var barOptions = {
        axisY: {
            low: 0,
            scaleMinSpace: 00,
            showGrid: false
        },
        axisX: {
            showGrid: false
        },
        fullWidth: true,
    };


    var lineOptions = {
        axisY: {
            low: 0,
            scaleMinSpace: 0,
            showGrid: false
        },
        axisX: {
            showGrid: false
        },
        lineSmooth: Chartist.Interpolation.simple({
            divisor: 2
        }),
        fullWidth: true
    };

    ////////////////////////////////////////////////////////////////////////////////
    //Actividades

    var ProjectStatsBar1 = new Chartist.Bar('#progress-stats-bar-chart1', {
        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'],
        series: [
            [
                serializeChartData(contador['segunda'] * 100 / chartist_visitas), 
                serializeChartData(contador['terca']   * 100 / chartist_visitas), 
                serializeChartData(contador['quarta']  * 100 / chartist_visitas), 
                serializeChartData(contador['quinta']  * 100 / chartist_visitas), 
                serializeChartData(contador['sexta']   * 100 / chartist_visitas), 
                serializeChartData(contador['sabado']  * 100 / chartist_visitas), 
                serializeChartData(contador['domingo'] * 100 / chartist_visitas)
            ]
        ]
    }, barOptions);

    ProjectStatsBar1.on('draw', function (data) {
        if (data.type === 'bar') {
            data.element.attr({
                style: 'stroke-width: 25px'
            });

        }
    });

    var ProjectStatsLine1 = new Chartist.Line('#progress-stats-line-chart1', {
        series: [
            [
                serializeChartData(contador['segunda'] * 100 / chartist_visitas), 
                serializeChartData(contador['terca']   * 100 / chartist_visitas), 
                serializeChartData(contador['quarta']  * 100 / chartist_visitas), 
                serializeChartData(contador['quinta']  * 100 / chartist_visitas), 
                serializeChartData(contador['sexta']   * 100 / chartist_visitas), 
                serializeChartData(contador['sabado']  * 100 / chartist_visitas), 
                serializeChartData(contador['domingo'] * 100 / chartist_visitas)
            ]
        ]
    }, lineOptions);

    ProjectStatsLine1.on('created', function (data) {
        var defs = data.svg.querySelector('defs') || data.svg.elem('defs');
        defs.elem('linearGradient', {
            id: 'lineLinearStats1',
            x1: 0,
            y1: 0,
            x2: 1,
            y2: 0
        }).elem('stop', {
            offset: '0%',
            'stop-color': 'rgba(40,175,208,0.1)'
        }).parent().elem('stop', {
            offset: '10%',
            'stop-color': 'rgba(40,175,208,1)'
        }).parent().elem('stop', {
            offset: '80%',
            'stop-color': 'rgba(40,175,208, 1)'
        }).parent().elem('stop', {
            offset: '98%',
            'stop-color': 'rgba(40,175,208, 0.1)'
        });

        return defs;


    }).on('draw', function (data) {
        var circleRadius = 5;
        if (data.type === 'point') {
            var circle = new Chartist.Svg('circle', {
                cx: data.x,
                cy: data.y,
                'ct:value': data.y,
                r: circleRadius,
                class: data.value.y === 15 ? 'ct-point ct-point-circle' : 'ct-point ct-point-circle-transperent'
            });
            data.element.replace(circle);
        }
    });

    ////////////////////////////////////////////////////////////////////////////////

    if(document.querySelector('#progress-stats-bar-chart2')){
        //Vendas
        var chl_vendas        = [];
        chl_vendas["domingo"] = 0, chl_vendas['segunda'] = 0, chl_vendas['terca'] = 0, chl_vendas['quarta'] = 0, chl_vendas['quinta'] = 0, chl_vendas['sexta'] = 0, chl_vendas['sabado'] = 0;
        
        if(Array.isArray(chartist_vendas)){
            chartist_vendas.forEach(function(elem, index){
                
                let date = elem['registo'].split(' ');
                date     = date[0].split('/');
                date     = date[2]+'/'+date[1]+'/'+date[0];

                let d    = new Date(date);

                switch (semana[d.getDay()]) {
                    case 'Dom':
                        chl_vendas['domingo']++;
                        break;
                    case 'Seg':
                        chl_vendas['segunda']++;
                        break;
                    case 'Ter':
                        chl_vendas['terca']++;
                        break;
                    case 'Qua':
                        chl_vendas['quarta']++;
                        break;
                    case 'Qui':
                        chl_vendas['quinta']++;
                        break;
                    case 'Sex':
                        chl_vendas['sexta']++;
                        break;
                    case 'Sab':
                        chl_vendas['sabado']++;
                        break;
                }
            });
        }

        var ProjectStatsBar2 = new Chartist.Bar('#progress-stats-bar-chart2', {
            labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'],
            series: [
                [
                    chl_vendas['segunda'], 
                    chl_vendas['terca'], 
                    chl_vendas['quarta'], 
                    chl_vendas['quinta'], 
                    chl_vendas['sexta'], 
                    chl_vendas['sabado'], 
                    chl_vendas['domingo']
                ]
            ]
        }, barOptions);

        ProjectStatsBar2.on('draw', function (data) {
            if (data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 25px'
                });
            }
        });


        var ProjectStatsLine2 = new Chartist.Line('#progress-stats-line-chart2', {
            series: [
                [
                    chl_vendas['segunda'], 
                    chl_vendas['terca'], 
                    chl_vendas['quarta'], 
                    chl_vendas['quinta'], 
                    chl_vendas['sexta'], 
                    chl_vendas['sabado'], 
                    chl_vendas['domingo']
                ]
            ]
        }, lineOptions);

        ProjectStatsLine2.on('created', function (data) {
            var defs = data.svg.querySelector('defs') || data.svg.elem('defs');
            defs.elem('linearGradient', {
                id: 'lineLinearStats2',
                x1: 0,
                y1: 0,
                x2: 1,
                y2: 0
            }).elem('stop', {
                offset: '0%',
                'stop-color': 'rgba(253,185,1,0.1)'
            }).parent().elem('stop', {
                offset: '10%',
                'stop-color': 'rgba(253,185,1,1)'
            }).parent().elem('stop', {
                offset: '80%',
                'stop-color': 'rgba(253,185,1, 1)'
            }).parent().elem('stop', {
                offset: '98%',
                'stop-color': 'rgba(253,185,1, 0.1)'
            });

            return defs;


        }).on('draw', function (data) {
            var circleRadius = 5;
            if (data.type === 'point') {
                var circle = new Chartist.Svg('circle', {
                    cx: data.x,
                    cy: data.y,
                    'ct:value': data.y,
                    r: circleRadius,
                    class: data.value.y === 15 ? 'ct-point ct-point-circle' : 'ct-point ct-point-circle-transperent'
                });
                data.element.replace(circle);
            }
        });        
    }else{

        //Tráfego
        var ProjectStatsBar3 = new Chartist.Bar('#progress-stats-bar-chart', {
            labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'],
            series: [
                [
                serializeChartData(contador['segunda'] + contador['maior'] - contador['menor']), 
                serializeChartData(contador['terca']   + contador['maior'] - contador['menor']), 
                serializeChartData(contador['quarta']  + contador['maior'] - contador['menor']), 
                serializeChartData(contador['quinta']  + contador['maior'] - contador['menor']), 
                serializeChartData(contador['sexta']   + contador['maior'] - contador['menor']), 
                serializeChartData(contador['sabado']  + contador['maior'] - contador['menor']), 
                serializeChartData(contador['domingo'] + contador['maior'] - contador['menor'])
                ]
            ]
        }, barOptions);

        ProjectStatsBar3.on('draw', function (data) {
            if (data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 25px'
                });
            }
        });


        var ProjectStatsLine3 = new Chartist.Line('#progress-stats-line-chart', {
            series: [
                [
                    serializeChartData(contador['segunda'] + contador['maior'] - contador['menor']), 
                    serializeChartData(contador['terca']   + contador['maior'] - contador['menor']), 
                    serializeChartData(contador['quarta']  + contador['maior'] - contador['menor']), 
                    serializeChartData(contador['quinta']  + contador['maior'] - contador['menor']), 
                    serializeChartData(contador['sexta']   + contador['maior'] - contador['menor']), 
                    serializeChartData(contador['sabado']  + contador['maior'] - contador['menor']), 
                    serializeChartData(contador['domingo'] + contador['maior'] - contador['menor'])
                ]
            ]
        }, lineOptions);

        ProjectStatsLine3.on('created', function (data) {
            var defs = data.svg.querySelector('defs') || data.svg.elem('defs');
            defs.elem('linearGradient', {
                id: 'lineLinearStats',
                x1: 0,
                y1: 0,
                x2: 1,
                y2: 0
            }).elem('stop', {
                offset: '0%',
                'stop-color': 'rgba(253,185,1,0.1)'
            }).parent().elem('stop', {
                offset: '10%',
                'stop-color': 'rgba(253,185,1,1)'
            }).parent().elem('stop', {
                offset: '80%',
                'stop-color': 'rgba(253,185,1, 1)'
            }).parent().elem('stop', {
                offset: '98%',
                'stop-color': 'rgba(253,185,1, 0.1)'
            });

            return defs;


        }).on('draw', function (data) {
            var circleRadius = 5;
            if (data.type === 'point') {
                var circle = new Chartist.Svg('circle', {
                    cx: data.x,
                    cy: data.y,
                    'ct:value': data.y,
                    r: circleRadius,
                    class: data.value.y === 15 ? 'ct-point ct-point-circle' : 'ct-point ct-point-circle-transperent'
                });
                data.element.replace(circle);
            }
        });   
    }


    ////////////////////////////////////////////////////////////////////////////////


})(window, document, jQuery);
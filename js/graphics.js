$(document).ready(function () {

    Chart.defaults.global.responsive = true;
    var graphics_info;
    var unchecked_spendings = [];
    var unchecked_earnings = [];
    var spendings_charts_data;
    var earnings_charts_data;

    //   var graphics_info;
    httpGetAsync("/main/graphics/build", log);

    // Get data for building charts
    function httpGetAsync(theUrl, callback) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                callback(xmlHttp.responseText);
        };
        xmlHttp.open("GET", theUrl, true); // true for asynchronous
        xmlHttp.send(null);
    }

    $('.radio-inline').mouseup(function (e) {

        var id = e.target.id;
        if (id.indexOf('icon_') === 0) {
            id = e.target.id.substr(5, e.target.id.length);
        }
        if (id.indexOf('chb_') === 0) {
            id = e.target.id.substr(4, e.target.id.length);
        }

        if (document.getElementById('chb_' + id).checked) {
            remove_line_data_from_chart(id);
        } else {
            add_line_data_to_chart(id);
        }

        build_chart("spendings", spendings_charts_data);
        build_chart('earnings', earnings_charts_data);
    });

    function remove_line_data_from_chart(name) {
        spendings_charts_data['datasets'].forEach(function (item) {
            if (item['label'] === name) {
                unchecked_spendings.push(item);
                spendings_charts_data['datasets'].splice(spendings_charts_data['datasets'].indexOf(item), 1);
            }
        });
        earnings_charts_data['datasets'].forEach(function (item) {
            if (item['label'] === name) {
                unchecked_earnings.push(item);
                earnings_charts_data['datasets'].splice(earnings_charts_data['datasets'].indexOf(item), 1);
            }
        });
    }


    function add_line_data_to_chart(name) {
        unchecked_spendings.forEach(function (item) {
            if (item['label'] === name) {
                spendings_charts_data['datasets'].push(item);
                unchecked_spendings.splice(spendings_charts_data['datasets'].indexOf(item), 1);
            }
        });
        unchecked_earnings.forEach(function (item) {
            if (item['label'] === name) {
                earnings_charts_data['datasets'].push(item);
                unchecked_earnings.splice(earnings_charts_data['datasets'].indexOf(item), 1);
            }
        });
    }

    $('.radio-inline').mouseover(function (e) {
        show_selected_line(e.target.id, 0.2, 0.01);
    });

    $('.radio-inline').mouseout(function (e) {
        show_selected_line(e.target.id, 1, 0.2);
    });


    // callback function for building charts. Function parse JSON string, that contains information about charts
    // to Javascript object.
    function log(data) {
        graphics_info = JSON.parse(data);
        spendings_charts_data = prepare_data_for_chart('spendings', graphics_info);
        build_chart("spendings", spendings_charts_data);
        earnings_charts_data = prepare_data_for_chart('earnings', graphics_info);
        build_chart('earnings', earnings_charts_data);
        build_global_chart(graphics_info);
        Chart.defaults.global.animation = false;
    }

    /*
     * build chart with based on chart_data object.
     */
    function build_chart(chart_type, charts_data) {
        var ctx = document.getElementById(chart_type + "_chart").getContext("2d");
        var myLineChart = new Chart(ctx).Line(charts_data);
    }

    /*
     * Made data structure for chart
     */
    function prepare_data_for_chart(chart_type, raw_data) {
        var spendings = raw_data[chart_type];
        return get_data_for_graphics(spendings);
    }

    /*
     * Select line on chart.
     * category - name of line for selecting
     * line_transparency - level of transparency for other lines of chart
     * shade_transparency - level of shade transparency for other shades of lines
     */
    function show_selected_line(category, line_transparency, shade_transparency) {
        if (contains_category(graphics_info, 'spendings', category)) {
            select_line(spendings_charts_data, category, line_transparency, shade_transparency);
            var ctx = document.getElementById("spendings_chart").getContext("2d");
            var myLineChart = new Chart(ctx).Line(spendings_charts_data);
        } else if (contains_category(graphics_info, 'earnings', category)) {
            select_line(earnings_charts_data, category, line_transparency, shade_transparency);
            var ctx = document.getElementById("earnings_chart").getContext("2d");
            var myLineChart = new Chart(ctx).Line(earnings_charts_data);
        }
    }

    /*
     * Check contains or not contains data_structure category.
     */
    function contains_category(data, category_type, category) {
        var result = false;
        data[category_type].forEach(function (item) {
            if (item['name'] === category) {
                result = true;
            }
        });
        return result;
    }


    function select_line(data, category, line_transparency, shade_transparency) {
        data['datasets'].forEach(function (item) {
            if (item['label'] !== category) {
                item['fillColor'] = item['fillColor'].substr(0, item['fillColor'].lastIndexOf(',') + 1) + shade_transparency + ')';
                item['strokeColor'] = item['strokeColor'].substr(0, item['strokeColor'].lastIndexOf(',') + 1) + line_transparency + ')';
                item['pointColor'] = item['pointColor'].substr(0, item['pointColor'].lastIndexOf(',') + 1) + line_transparency + ')';
                item['pointHighlightStroke'] = item['pointHighlightStroke'].substr(0, item['pointHighlightStroke'].lastIndexOf(',') + 1) + line_transparency + ')';
            }
        });
    }

    function build_global_chart(data) {
        var global_data = data['global'];
        var charts_data = get_data_for_global_graphics(global_data);
        var ctx = document.getElementById("global_chart").getContext("2d");
        var myLineChart = new Chart(ctx).Line(charts_data);
    }

    function get_all_labels(_data_array, labels) {
        _data_array.forEach(function (item) {
            labels.push(item[0].substr(0, 7));
        });
    }

    function get_data_for_global_graphics(global_data) {
        var labels = [];
        get_all_labels(global_data['global_earnings'], labels);
        get_all_labels(global_data['global_spendings'], labels);
        labels = ordered_filter_unique(labels);

        return {
            labels: labels,
            datasets: [
                get_datasets_item('earnings', [0, 204, 0], correct_charts_data(global_data['global_earnings'], labels)),
                get_datasets_item('spendings', [255, 0, 0], correct_charts_data(global_data['global_spendings'], labels))
            ]
        }
    }

    // Function retrieve dates from data-object and build from them labels for
    // axe.
    function get_labels(group) {
        var dates = [];

        group.forEach(function (item) {
            item['amount'].forEach(function (item) {
                dates.push(item[0].substr(0, 7));
            })
        });

        return ordered_filter_unique(dates);
    }

    function ordered_filter_unique(data) {
        return data.filter(function (itm, i, a) {
            return i == a.indexOf(itm);
        }).sort();
    }

    // Return string, that contain part of rgba set. Transparency  missing here and can be added later.
    function get_rand_rgba_color() {
        return [Math.floor(Math.random() * 220), Math.floor(Math.random() * 220), Math.floor(Math.random() * 220)];
    }

    function get_rgba_string(rgb_colors, opacity) {
        return 'rgba(' + rgb_colors[0] + ',' + rgb_colors[1] + ',' + rgb_colors[2] + ',' + opacity + ')';
    }

    // Return object that contains all vital information for building charts with library Chart.js
    function get_data_for_graphics(data) {
        var data_for_graphics = {};
        var labels = get_labels(data, 'spendings');
        data_for_graphics['labels'] = labels;
        var datasets = [];

        data.forEach(function (item) {
            var rgb_color_set = get_rand_rgba_color();
            document.getElementById('icon_' + item['name']).style.backgroundColor = get_rgba_string(rgb_color_set, 1);
            if (!is_amount_data_empty(item['amount'])) {
                datasets.push(get_datasets_item(item['name'], rgb_color_set, correct_charts_data(item['amount'], labels)));
            } else {
                document.getElementById('chb_' + item['name']).disabled = true;
            }

        });
        data_for_graphics['datasets'] = datasets;
        return data_for_graphics;
    }

    function is_amount_data_empty(amount_data) {
        return amount_data.length === 0;
    }

    /*
     * Generate single dataset item for Charts.js library with specific parameters.
     * label_name - name of graphic
     * rgb_color_set - color set for graphic
     * chart_data - array with numbers for building graphic
     */
    function get_datasets_item(label_name, rgb_color_set, chart_data) {
        return {
            label: label_name,
            fillColor: get_rgba_string(rgb_color_set, 0.2),
            strokeColor: get_rgba_string(rgb_color_set, 1),
            pointColor: get_rgba_string(rgb_color_set, 1),
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: get_rgba_string(rgb_color_set, 1),
            data: chart_data
        }
    }

    /*
     * Selected from database information can contain date gaps, when on specific category was don't spended money
     * This function fill such gaps with 0 to avoid shifting data on charts
     */
    function correct_charts_data(charts_data, labels) {
        var corrected_data = new Array(labels.length);
        for (var i = 0; i < corrected_data.length; i++) {
            corrected_data[i] = 0;
        }
        charts_data.forEach(function (item) {
            var position = labels.lastIndexOf(item[0].substr(0, 7));
            corrected_data[position] = item[1];
        });
        return corrected_data;
    }
});
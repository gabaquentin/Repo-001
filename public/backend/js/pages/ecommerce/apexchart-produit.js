options = {
    chart: {
        height: 380,
        type: "area",
        stacked: !0,
        events: {
            selection: function (e, t) {
                console.log(new Date(t.xaxis.min));
            },
        },
    },
    colors: ["#3f51b5", "#009688", "#CED4DC", "#BC7561"],
    dataLabels: { enabled: !1 },
    stroke: { width: [2], curve: "smooth" },
    series: [
        { name: "Vendu", data: generateDayWiseTimeSeries(new Date("11 Feb 2017 GMT").getTime(), 20, { min: 10, max: 60 }) },
        { name: "Like", data: generateDayWiseTimeSeries(new Date("11 Feb 2017 GMT").getTime(), 20, { min: 10, max: 20 }) },
        { name: "Vue", data: generateDayWiseTimeSeries(new Date("11 Feb 2017 GMT").getTime(), 20, { min: 10, max: 15 }) },
        { name: "Rejete", data: generateDayWiseTimeSeries(new Date("11 Feb 2017 GMT").getTime(), 20, { min: 10, max: 15 }) },
    ],
    fill: { type: "gradient", gradient: { opacityFrom: 0.6, opacityTo: 0.8 } },
    legend: { position: "top", horizontalAlign: "left" },
    xaxis: { type: "datetime" },
};
function generateDayWiseTimeSeries(e, t, a) {
    for (var o = 0, r = []; o < t; ) {
        var i = e,
            s = Math.floor(Math.random() * (a.max - a.min + 1)) + a.min;
        r.push([i, s]), (e += 864e5), o++;
    }
    return r;
}
(chart = new ApexCharts(document.querySelector("#apex-area"), options)).render();
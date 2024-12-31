/**
 * Charts ChartsJS
 */
"use strict";

(function () {
    // Color Variables
    const // purpleColor = "#836AF9",
        //     yellowColor = "#ffe800",
        //     cyanColor = "#28dac6",
        //     orangeColor = "#FF8132",
        //     orangeLightColor = "#FDAC34",
        //     oceanBlueColor = "#299AFF",
        //     greyColor = "#4F5D70",
        greyLightColor = "#EDF1F4",
        blueColor = "#2B9AFF",
        blueLightColor = "#84D0FF";

    let cardColor,
        headingColor,
        labelColor,
        borderColor,
        legendColor,
        isRtl = "ltr";
    cardColor = config.colors.cardColor;
    headingColor = config.colors.headingColor;
    labelColor = config.colors.textMuted;
    legendColor = config.colors.bodyColor;
    borderColor = config.colors.borderColor;

    // Set height according to their data-height
    // --------------------------------------------------------------------
    const chartList = document.querySelectorAll(".chartjs");
    chartList.forEach(function (chartListItem) {
        chartListItem.height = chartListItem.dataset.height;
    });

    // LineArea Chart
    // --------------------------------------------------------------------

    const lineAreaChart = document.getElementById("lineAreaChart");
    if (lineAreaChart) {
        const lineAreaChartVar = new Chart(lineAreaChart, {
            type: "line",
            data: {
                labels: [
                    "7/12",
                    "8/12",
                    "9/12",
                    "10/12",
                    "11/12",
                    "12/12",
                    "13/12",
                    "14/12",
                    "15/12",
                    "16/12",
                    "17/12",
                    "18/12",
                    "19/12",
                    "20/12",
                    "",
                ],
                datasets: [
                    {
                        label: "Africa",
                        data: [
                            40, 55, 45, 75, 65, 55, 70, 60, 100, 98, 90, 120,
                            125, 140, 155,
                        ],
                        tension: 0,
                        fill: true,
                        backgroundColor: blueColor,
                        pointStyle: "circle",
                        borderColor: "transparent",
                        pointRadius: 0.5,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: "transparent",
                        pointHoverBackgroundColor: blueColor,
                        pointHoverBorderColor: cardColor,
                    },
                    {
                        label: "Asia",
                        data: [
                            70, 85, 75, 150, 100, 140, 110, 105, 160, 150, 125,
                            190, 200, 240, 275,
                        ],
                        tension: 0,
                        fill: true,
                        backgroundColor: blueLightColor,
                        pointStyle: "circle",
                        borderColor: "transparent",
                        pointRadius: 0.5,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: "transparent",
                        pointHoverBackgroundColor: blueLightColor,
                        pointHoverBorderColor: cardColor,
                    },
                    {
                        label: "Europe",
                        data: [
                            240, 195, 160, 215, 185, 215, 185, 200, 250, 210,
                            195, 250, 235, 300, 315,
                        ],
                        tension: 0,
                        fill: true,
                        backgroundColor: greyLightColor,
                        pointStyle: "circle",
                        borderColor: "transparent",
                        pointRadius: 0.5,
                        pointHoverRadius: 5,
                        pointHoverBorderWidth: 5,
                        pointBorderColor: "transparent",
                        pointHoverBackgroundColor: greyLightColor,
                        pointHoverBorderColor: cardColor,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: "top",
                        rtl: isRtl,
                        align: "start",
                        labels: {
                            usePointStyle: true,
                            padding: 35,
                            boxWidth: 6,
                            boxHeight: 6,
                            color: legendColor,
                        },
                    },
                    tooltip: {
                        // Updated default tooltip UI
                        rtl: isRtl,
                        backgroundColor: cardColor,
                        titleColor: headingColor,
                        bodyColor: legendColor,
                        borderWidth: 1,
                        borderColor: borderColor,
                    },
                },
                scales: {
                    x: {
                        grid: {
                            color: "transparent",
                            borderColor: borderColor,
                        },
                        ticks: {
                            color: labelColor,
                        },
                    },
                    y: {
                        min: 0,
                        max: 400,
                        grid: {
                            color: "transparent",
                            borderColor: borderColor,
                        },
                        ticks: {
                            stepSize: 100,
                            color: labelColor,
                        },
                    },
                },
            },
        });
    }
})();

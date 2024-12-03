<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="min-h-full bg-slate-100">
        <div class="mx-auto max-w-7xl">
            {{-- stats --}}
            <div class="flex flex-col gap-2 mt-10">
                <h3 class="text-gray-500 text-2xl font-bold">Overview</h3>

                <div class="flex rounded-lg bg-slate-950 text-white">
                    <div class="grid grid-cols-4 divide-x w-full divide-slate-800">
                        <div class="p-8">
                            <div class="flex flex-col gap-2">
                                <h4 class="text-gray-400">Number of deploys</h4>
                                <span class="text-3xl font-bold">405</span>
                            </div>
                        </div>
    
                        <div class="p-8">
                            <div class="flex flex-col gap-2">
                                <h4 class="text-gray-400">Average deploy time</h4>
                                <span class="text-3xl font-bold">3.65 <span class="text-sm text-gray-400">mins</span> </span>
                            </div>
                        </div>
    
                        <div class="p-8">
                            <div class="flex flex-col gap-2">
                                <h4 class="text-gray-400">Number of servers</h4>
                                <span class="text-3xl font-bold">3</span>
                            </div>
                        </div>
    
                        <div class="p-8">
                            <div class="flex flex-col gap-2">
                                <h4 class="text-gray-400">Success rate</h4>
                                <span class="text-3xl font-bold">98.5%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- chart --}}
            <div class="flex flex-col gap-2 mt-10">
                <h3 class="text-gray-500 text-2xl font-bold">Chart</h3>

                <div id="chart">

                </div>
            </div>

            {{-- map --}}
            <div class="flex flex-col gap-2 mt-10">
                <h3 class="text-gray-500 text-2xl font-bold">Map</h3>

                <div id="map">

                </div>
            </div>
        </div>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/ag-charts-community@10.3.3/dist/umd/ag-charts-community.js?t=1731495897773"></script>
    <script src="/js/typology.js"></script>
    <script>
        const { AgCharts } = agCharts;

        const chartOptions = {
            container: document.getElementById("chart"),
            title: {
                text: "2023 Average Temperatures",
            },
            subtitle: {
                text: "Oxford, UK",
            },
            data: getData(),
            series: [
                {
                    type: "line",
                    xKey: "month",
                    xName: "Month",
                    yKey: "min",
                    yName: "Min Temperature",
                    interpolation: { type: "smooth" },
                },
                {
                    type: "line",
                    xKey: "month",
                    xName: "Month",
                    yKey: "max",
                    yName: "Max Temperature",
                    interpolation: { type: "smooth" },
                },
            ],
        };

        const mapOptions = {
            container: document.getElementById("map"),
            data,
            topology,
            series: [
                {
                    type: "map-shape-background",
                },
                {
                    type: "map-shape",
                    title: "Access to Clean Fuels",
                    idKey: "name",
                    colorKey: "value",
                    colorName: "% of population",
                },
            ],
            gradientLegend: {
                enabled: true,
                position: "right",
                gradient: {
                    preferredLength: 200,
                    thickness: 2,
                },
                scale: {
                    label: {
                        fontSize: 10,
                        formatter: (p) => p.value + "%",
                    },
                },
            },
        };

        const chart = AgCharts.create(chartOptions);
        const map = AgCharts.create(mapOptions);

        function getData() {
            return [
                { month: "January", max: 8.5, min: 2.6 },
                { month: "February", max: 10.4, min: 3.0 },
                { month: "March", max: 10.9, min: 4.7 },
                { month: "April", max: 13.7, min: 5.0 },
                { month: "May", max: 18.2, min: 8.4 },
                { month: "June", max: 23.6, min: 12.2 },
                { month: "July", max: 21.3, min: 13.0 },
                { month: "August", max: 21.9, min: 13.1 },
                { month: "September", max: 22.6, min: 13.2 },
                { month: "October", max: 17.0, min: 9.7 },
                { month: "November", max: 11.1, min: 4.9 },
                { month: "December", max: 10.2, min: 5.2 },
            ];
        }
    </script>
</html>
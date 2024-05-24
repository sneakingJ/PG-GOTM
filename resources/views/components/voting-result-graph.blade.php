<div class="box">
    <h2 class="title is-3">{{ $categoryName }} Games</h2>
    <div id="{{ $categoryName }}-sankey" class="sankey-container"></div>
    @empty($results)
        No votes yet!
    @endempty
    <script type="text/javascript">
        google.charts.load('current', { packages: ['sankey'] });
        google.charts.setOnLoadCallback(drawChart{{ $categoryName }});

        function drawChart{{ $categoryName }}() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'From');
            data.addColumn('string', 'To');
            data.addColumn('number', 'Votes');
            data.addRows({!! json_encode($results) !!});

            var container = document.getElementById('{{ $categoryName }}-sankey');

            var width = container.clientWidth;
            var height = container.clientHeight || 400;

            var chart = new google.visualization.Sankey(container);
            chart.draw(data, options);

            console.debug('Chart drawn with dimensions:', width, height);
        }

        window.addEventListener('DOMContentLoaded', event => {
            google.charts.setOnLoadCallback(drawChart{{ $categoryName }});
        });

        window.addEventListener('polled', event => {
            drawChart{{ $categoryName }}();
        });

        window.addEventListener('resize', drawChart{{ $categoryName }});
    </script>
</div>

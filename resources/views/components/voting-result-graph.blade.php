<div class="box">
    <h2 class="title is-3">{{ $categoryName }} Games</h2>
    <div id="{{ $categoryName }}-sankey" class="sankey-container"></div>
    @empty($results)
        No votes yet!
    @endempty
    <script type="text/javascript">
        function drawChart{{ $categoryName }}() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'From');
            data.addColumn('string', 'To');
            data.addColumn('number', 'Votes');
            data.addRows({!! json_encode($results) !!});

            // Instantiates and draws our chart, passing in some options.
            var chart = new google.visualization.Sankey(document.getElementById('{{ $categoryName }}-sankey'));
            chart.draw(data, options);
        }

        window.addEventListener('DOMContentLoaded', event => {
            google.charts.setOnLoadCallback(drawChart{{ $categoryName }});
        });

        window.addEventListener('polled', event => {
            drawChart{{ $categoryName }}();
        });
    </script>
</div>

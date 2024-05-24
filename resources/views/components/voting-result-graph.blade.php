<div class="box">
    <h2 class="title is-3">{{ $categoryName }} Games</h2>
    <div id="{{ $categoryName }}-sankey" class="sankey-container"></div>
    @empty($results)
        No votes yet!
    @endempty
    <script type="text/javascript">
        google.charts.load('current', {
            packages: ['sankey']
        });
        google.charts.setOnLoadCallback(drawChart{{ $categoryName }});

        function drawChart{{ $categoryName }}() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'From');
            data.addColumn('string', 'To');
            data.addColumn('number', 'Votes');
            data.addRows({!! json_encode($results) !!});

            var container = document.getElementById('{{ $categoryName }}-sankey');

            var width = container.clientWidth;
            var height = Math.min(width * 0.75, 400);
            
            // Max font size of 16, min font size of 12, evolve linearly based on width
            var fontSize = Math.max(12, Math.min(16, width / 50));

            var options = {
                height: height,
                width: width,
                sankey: {
                    node: {
                        label: {
                            fontName: 'BlinkMacSystemFont,-apple-system,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",Helvetica,Arial,sans-serif',
                            fontSize: fontSize,
                            color: '#ffffff'
                        },
                        nodePadding: 60,
                        labelPadding: 20,
                        enableInteractivity: false
                    }
                }
            }

            var chart = new google.visualization.Sankey(container);
            chart.draw(data, options);
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

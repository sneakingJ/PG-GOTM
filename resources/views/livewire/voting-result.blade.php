<div class="block">
    <script type="text/javascript">
        var options = {
            height: 400,
            sankey: {
                node: {
                    label: {
                        fontName: 'BlinkMacSystemFont,-apple-system,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",Helvetica,Arial,sans-serif',
                        fontSize: 16,
                        color: '#ffffff'
                    },
                    nodePadding: 60,
                    labelPadding: 20,
                    enableInteractivity: false
                }
            }
        }

        google.charts.load('current', {
            'packages': ['sankey']
        });
        google.charts.setOnLoadCallback(drawChart);
    </script>
    <div>
        @livewire('voting-result-graph', array('short' => false, 'monthId' => $monthId))
        @livewire('voting-result-graph', array('short' => true, 'monthId' => $monthId))
    </div>
</div>

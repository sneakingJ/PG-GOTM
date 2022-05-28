<div class="block">
    <div class="columns is-centered">
        <div class="column is-narrow">
            <h1 class="title is-2 has-text-centered">Current Standings</h1>
            <p class="has-text-centered"><a href="#" wire:click="$emitTo('voting-result', 'activateModal')">What is Ranked Choice Voting?</a></p>
        </div>
    </div>
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

        function drawCharts() {
            drawChartLong();
            drawChartShort();
        }

        document.addEventListener("DOMContentLoaded", () => {
            google.charts.load('current', {'packages': ['sankey']});
            google.charts.setOnLoadCallback(drawCharts);
        });
    </script>
    <div class="box">
        <h2 class="title is-3">Long Games</h2>
        <div id="long-sankey" class="sankey-container"></div>
        @empty($longResult)
            No votes yet!
        @endempty
        <script type="text/javascript">
            function drawChartLong() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'From');
                data.addColumn('string', 'To');
                data.addColumn('number', 'Votes');
                data.addRows([
                    @foreach($longResult as $result)
                        ['{!! addslashes($result[0]) !!}', '{!! addslashes($result[1]) !!}', {{ $result[2] }}],
                    @endforeach
                ]);

                // Instantiates and draws our chart, passing in some options.
                var chart = new google.visualization.Sankey(document.getElementById('long-sankey'));
                chart.draw(data, options);
            }
        </script>
    </div>
    <div class="box">
        <h2 class="title is-3">Short Games</h2>
        <div id="short-sankey" class="sankey-container"></div>
        @empty($shortResult)
            No votes yet!
        @endempty
        <script type="text/javascript">
            function drawChartShort() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'From');
                data.addColumn('string', 'To');
                data.addColumn('number', 'Votes');
                data.addRows([
                    @foreach($shortResult as $result)
                        ['{!! addslashes($result[0]) !!}', '{!! addslashes($result[1]) !!}', {{ $result[2] }}],
                    @endforeach
                ]);

                // Instantiates and draws our chart, passing in some options.
                var chart = new google.visualization.Sankey(document.getElementById('short-sankey'));
                chart.draw(data, options);
            }
        </script>
    </div>

    @if($modalActive)
        <div class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">What is Ranked Choice Voting?</p>
                    <button class="delete" aria-label="close" type="button" wire:click="$emitTo('voting-result', 'disableModal')"></button>
                </header>
                <section class="modal-card-body has-text-centered">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/oHRPMJmzBBw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </section>
                <footer class="column modal-card-foot">
                    <button class="button is-pulled-right" type="button" wire:click="$emitTo('voting-result', 'disableModal')">Close</button>
                </footer>
            </div>
        </div>
    @endif
</div>

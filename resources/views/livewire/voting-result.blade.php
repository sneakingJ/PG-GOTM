<div class="block">
    <div class="columns is-centered">
        <div class="column is-narrow">
            <h1 class="title is-2 has-text-centered">Current Standings</h1>
            <p class="has-text-centered"><a href="#" wire:click="$emitTo('ranked-choice-modal', 'activateModal')">What is Ranked Choice Voting?</a></p>
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
        
        google.charts.load('current', {'packages': ['sankey']});
    </script>
    <div>
        @livewire('voting-result-graph', array('short' => false))
        @livewire('voting-result-graph', array('short' => true))
    </div>

    <livewire:ranked-choice-modal/>
</div>

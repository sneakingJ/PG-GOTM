<div class="box sankey-box">
    <h2 class="title is-3">{{ $categoryName }} Games</h2>
    <div id="{{ $categoryName }}-sankey" class="sankey-container">
        <canvas id="{{ $categoryName }}-canvas"></canvas>
    </div>
    @empty($results)
        No votes yet!
    @endempty
    <script type="module">
        import init, {start} from "/js/pkg/ranked_choice_sankey.js";

        async function run() {
            await init();

            start("{{ $categoryName }}-canvas", {!! json_encode($results) !!});
        }

        window.addEventListener('resize', function() {
            start("{{ $categoryName }}-canvas", {!! json_encode($results) !!});
        });

        run();
    </script>
</div>

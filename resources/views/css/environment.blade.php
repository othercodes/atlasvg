<style>
    @if (isset($environment))
    .surroundings {
        width: {{ $environment['level']['width']*1.8 }}vmin;
        height: {{ $environment['level']['height']*1.8 }}vmin;
        margin: -{{ $environment['level']['top']*1.8 }}vmin 0 0 -{{ $environment['level']['left']*1.8 }}vmin;
    }

    .levels {
        width: {{ $environment['level']['width'] }}vmin;
        height: {{ $environment['level']['height'] }}vmin;
        margin: -{{ $environment['level']['top'] }}vmin 0 0 -{{ $environment['level']['left'] }}vmin;
    }
    @endif
</style>
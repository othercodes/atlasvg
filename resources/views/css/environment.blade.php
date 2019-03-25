<style>
    @if (isset($environment))
    .surroundings {
        width: {{ $environment['surroundings']['width'] }}vmin;
        height: {{ $environment['surroundings']['height'] }}vmin;
        margin: -{{ $environment['surroundings']['top'] }}vmin 0 0 -{{ $environment['surroundings']['left'] }}vmin;
    }

    .levels {
        width: {{ $environment['level']['width'] }}vmin;
        height: {{ $environment['level']['height'] }}vmin;
        margin: -{{ $environment['level']['top'] }}vmin 0 0 -{{ $environment['level']['left'] }}vmin;
    }
    @endif
</style>
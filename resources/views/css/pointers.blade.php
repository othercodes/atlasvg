<style>
    @if (isset($building))
    @foreach ($building->levels as $index => $level)

    .level--{{ $level->level.'::after' }}   {
        content: '{{ $level->name }}';
    }

    @if($index > 0)
    .level--{{ $level->level }}     {
        -webkit-transform: translateZ({{ 10*$index }}vmin);
        transform: translateZ({{ 10*$index }}vmin);
    }

    @endif

     .levels--selected-{{ $level->level }} .level:not(.level--{{ $level->level }}) {
        opacity: 0;
    }

    @for($i=0;$i<=count($building->levels);$i++)

     @if($i > $level->level)
     .levels--selected-{{ $i }} .level--{{ $level->level }}   {
        -webkit-transform: translateZ(-60vmin);
        transform: translateZ(-60vmin);
    }

    @endif

    @endfor
    @endforeach
    @endif
</style>
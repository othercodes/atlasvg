<style>
    @foreach ($categories as $category)
    .grouped-by-category [data-category='{{ $category->id }}']:first-child,
    .grouped-by-category :not([data-category='{{ $category->id }}']) + [data-category='{{ $category->id }}'] {
        margin-top: 4em;
    }

    .grouped-by-category [data-category='{{ $category->id }}']:first-child::before,
    .grouped-by-category :not([data-category='{{ $category->id }}']) + [data-category='{{ $category->id }}']::before {
        font-size: 1.25em;
        position: absolute;
        top: -1.75em;
        left: 0;
        color: #c7c7c9;
    }

    .grouped-by-category [data-category='{{ $category->id }}']:first-child::before,
    .grouped-by-category :not([data-category='{{ $category->id }}']) + [data-category='{{ $category->id }}']::before {
        content: '{{ $category->name }}';
        color: {{ $category->color }};
    }

    .content__item[data-category='{{ $category->id }}'] .content__item-title {
        color: {{ $category->color }};
    }

    .pin[data-category='{{ $category->id }}'] .icon--pin {
        fill: {{ $category->color }};
    }

    @endforeach

    @foreach ($building->levels as $index => $level)

    .level--{{ $level->level.'::after' }} {
        content: '{{ $level->name }}';
    }

    @if($index > 0)
    .level--{{ $level->level }}   {
        -webkit-transform: translateZ({{ 10*$index }}vmin);
        transform: translateZ({{ 10*$index }}vmin);
    }

    @endif

     .levels--selected-{{ $level->level }} .level:not(.level--{{ $level->level }}) {
        opacity: 0;
    }

    @for($i=0;$i<=count($building->levels);$i++)

     @if($i > $level->level)
     .levels--selected-{{ $i }} .level--{{ $level->level }} {
        -webkit-transform: translateZ(-60vmin);
        transform: translateZ(-60vmin);
    }

    @endif

    @endfor
    @endforeach
</style>
<style>
    @if (count($categories) > 0)
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
@endif
</style>
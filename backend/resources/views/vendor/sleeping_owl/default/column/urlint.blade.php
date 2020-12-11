<div {!! $attributes !!}>
    @if (!empty($value))
        <a href="http://127.0.0.1:8080/login?auto={{ $value }}"
           data-toggle="tooltip" title="Local login" target="_blank">
            @if($icon)
                <i class="{{ $icon }}"></i>
            @endif

            @if($text)
                {{ $text }}
            @endif

        </a>
    @endif
    {!! $append !!}

    @if($small)
        <small class="clearfix">{!! $small !!}</small>
    @endif
</div>

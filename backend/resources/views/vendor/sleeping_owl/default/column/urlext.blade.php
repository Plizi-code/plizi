<div {!! $attributes !!}>
    @if (!empty($value))
        <a href="https://vm1095330.hl.had.pm/login?auto={{ $value }}"
           data-toggle="tooltip" title="Test login" target="_blank">
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

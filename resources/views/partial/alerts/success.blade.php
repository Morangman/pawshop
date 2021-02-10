<div class="alert alert-success alert-white rounded">
    @if(!empty($close))
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
            <span class="sr-only">@lang('admin.common.word.close')</span>
        </button>
    @endif
    <div class="icon">
        <i class="fa fa-check"></i>
    </div>
    @if(isset($alertTitle))
        <span class="text-bold">
        {{ $alertTitle }}
    </span>
    @endif
    @if(is_array($alertText))
        <ul>
            @foreach($alertText as $message)
                <li>{!! $message !!}</li>
            @endforeach
        </ul>
    @else
        {!! $alertText !!}
    @endif
</div>

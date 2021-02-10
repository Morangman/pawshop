@php
    $close = $close ?? true;
@endphp

@includeWhen(Session::has('success'), 'partial.alerts.success', [
    'close' => $close,
    'alertText' => Session::get('success')
])

@includeWhen(Session::has('info'), 'partial.alerts.info', [
    'close' => $close,
    'alertText' => Session::get('info')
])

@includeWhen(Session::has('warning'), 'partial.alerts.warning', [
    'close' => $close,
    'alertText' => Session::get('warning')
])

@includeWhen(Session::has('error'), 'partial.alerts.danger', [
    'close' => $close,
    'alertText' => Session::get('error')
])

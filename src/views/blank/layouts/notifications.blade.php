@if ($message = Session::get('success'))
<div class="cerberus-alert-success">
  <strong>Success:</strong> {!! $message !!}
</div>
{{ Session::forget('success') }}
@endif

@if ($message = Session::get('error'))
<div class="cerberus-alert-error">
  <strong>Error:</strong> {!! $message !!}
</div>
{{ Session::forget('error') }}
@endif

@if ($message = Session::get('warning'))
<div class="cerberus-alert-warning">
  <strong>Warning:</strong> {!! $message !!}
</div>
{{ Session::forget('warning') }}
@endif

@if ($message = Session::get('info'))
<div class="cerberus-alert-info">
  <strong>FYI:</strong> {!! $message !!}
</div>
{{ Session::forget('info') }}
@endif

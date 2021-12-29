<div {{ $attributes->merge(['class' => $type]) }}>
  {!! $message ?? $slot !!}
</div>

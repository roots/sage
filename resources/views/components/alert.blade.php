<div {{ $attributes->merge(['class' => $type]) }}>
  <div class="px-4 py-3">
    {!! $message ?? $slot !!}
  </div>
</div>

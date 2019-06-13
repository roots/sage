<div class="my-4 px-3 py-2 border rounded-sm {{ collect(['success' => 'bg-green-200 text-green-800 border-green-500', 'warning' => 'bg-yellow-200 text-yellow-800 border-yellow-500', 'error' => 'bg-red-200 text-red-700 border-red-500'])->get($type, 'bg-indigo-200 text-indigo-800 border-indigo-500') }}">
  <p>{{ $slot }}</p>
</div>

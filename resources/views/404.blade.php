@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  @if (! have_posts())
    <x-alert type="warning" message="Sorry, but the page you are trying to view does not exist." />

    {!! get_search_form(false) !!}
  @endif
@endsection

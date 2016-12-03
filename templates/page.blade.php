@extends('layouts.base')

@section('content')
  @while(have_posts())
    {!! the_post() !!}
    @include('partials.page-header')
    @include('partials.content-page')
  @endwhile
@endsection

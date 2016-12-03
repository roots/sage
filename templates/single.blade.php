@extends('layouts.base')

@section('content')
  @while(have_posts())
    {!! the_post() !!}
    @include('partials/content-single')
  @endwhile
@endsection


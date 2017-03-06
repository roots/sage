@extends('layouts.base')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials/content-single-'.(get_post_type() != 'post' ? get_post_type() : get_post_format()))
  @endwhile
@endsection

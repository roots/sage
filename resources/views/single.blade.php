@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @includeFirst(['loops.content-single-' . get_post_type(), 'loops.content-single'])
  @endwhile
@endsection

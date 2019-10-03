{{--
  Template Name: Custom Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('partials.page-header', ['title' => $title])
    @include('partials.content-page')
  @endwhile
@endsection

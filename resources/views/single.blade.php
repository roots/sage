@extends('layouts.app')

@php the_post() @endphp

@section('content')

  @include('partials.single.' . get_post_type())

@endsection

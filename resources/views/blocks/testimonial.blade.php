{{--
  Title: Testimonial
  Description: Customer testimonial
  Category: formatting
  Icon: admin-comments
  Keywords: testimonial quote
  Mode: edit
  Align: left
  PostTypes: page post
  SupportsAlign: left right
  SupportsMode: false
  SupportsMultiple: true
--}}

<blockquote data-{{ $block['id'] }} class="{{ $block['classes'] }}">
    <p>{{ get_field('testimonial') }}</p>
    <cite>
      <span>{{ get_field('author') }}</span>
    </cite>
</blockquote>

<style type="text/css">
  [data-{{$block['id']}}] {
    background: {{ get_field('background_color') }};
    color: {{ get_field('text_color') }};
  }
</style>
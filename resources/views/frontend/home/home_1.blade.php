@extends($layout)
@section('content')
 @foreach($content_sections as $section)
    @switch($section['section_type'] ?? "")
       @case('banner')
           @include('frontend.home.partials.banner', ['section' => $section])
           @break
        @case('search_section')
            @include('frontend.home.partials.search', ['section' => $section])
            @break
        @case('featured_category')
            @include('frontend.home.partials.featured_category', ['section' => $section])
            @break
        @case('al_gigs')
            @include('frontend.home.partials.gigs', ['section' => $section])
            @break
        @case('how_it_works')
            @include('frontend.home.partials.how_it_works', ['section' => $section])
            @break
        @case('marquee_section')
            @include('frontend.home.partials.marquee', ['section' => $section])
            @break
        @case('new_gigs')
            @include('frontend.home.partials.new_gigs', ['section' => $section])
            @break
        @case('why_us_section')
            @include('frontend.home.partials.why_us', ['section' => $section])
            @break
        @case('testimonial')
            @include('frontend.home.partials.testimonials', ['section' => $section])
            @break
        @case('ad_card_section')
            @include('frontend.home.partials.ad_card', ['section' => $section])
            @break
        @case('faq')
            @include('frontend.home.partials.faq', ['section' => $section])
            @break
        @case('joinus_section')
            @include('frontend.home.partials.joinus', ['section' => $section])
            @break
    @endswitch
 @endforeach
@endsection
@push('scripts')
   <script src="{{ asset('frontend/custom/js/home/home_1.js') }}"></script>
@endpush

<div class="popular-section-two">
    <div class="container">
        <div class="section-header-two text-center" data-aos="fade-up">
            <h2 class="mb-2"><span class="title-bg"></span>{{ $section['section_title'] }}<span class="title-bg2"></span></h2>
            <p>{{ $section['section_label'] }}</p>
        </div>
        <div class="row row-gap-4 row-cols-xl-5 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1 align-items-center">
            @if(!empty($section['section_content']) && count($section['section_content']) > 0)
            @foreach($section['section_content'] as $category)
            <div class="col d-flex">
               <div class="pop-category flex-fill" data-aos="flip-left">
                   <img src="{{ uploadedAsset($category->icon ?? "") }}" alt="" height="25" width="25">
                   <h6 class="mb-1"><a href="{{ route('index.services', ['c' => $category->slug ?? "" ]) }}" aria-label="category">{{ $category->name ?? "" }}</a></h6>
                   <p>{{ $category->service_count ?? "" }} {{__('web.home.service')}}</p>
               </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
 </div>
 
<div class="service-slider-section">
    <div class="horizontal-slide d-flex" data-direction="right" data-speed="slow">
        <div class="slide-list d-flex gap-4">
            @if(!empty($section['section_content']) && count($section['section_content']) > 0)
            @foreach($section['section_content'] as $service)
            <div class="p-3 px-4 d-flex align-items-center service-item">
                <h4>{{ $service['text'] }}</h4>
            </div>
            @endforeach
            @endif
            
        </div>
     </div>
 </div>
 
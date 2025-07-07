<div class="how-it-works">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="how-it-works-content position-relative">
                    <img src="{{ asset('frontend/assets/img/home/shape-1.svg') }}" alt="img"
                        class="img-fluid how-it-works-bg">

                    @if(!empty($section['section_title']) || !empty($section['section_label']))
                    <div class="section-header-two" data-aos="fade-up">
                        @if(!empty($section['section_title']))
                        <h2 class="mb-2">
                            <span class="title-bg"></span>
                            {{ $section['section_title'] }}
                            <span class="title-bg2"></span>
                        </h2>
                        @endif

                        @if(!empty($section['section_label']))
                        <p>{{ $section['section_label'] }}</p>
                        @endif
                    </div>
                    @endif

                    {!! $section['section_content'][1]->value ?? '' !!}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="how-it-works-images d-lg-block d-none">
                    @if(!empty($section['section_content'][0]->datas ?? null))
                    @php
                    $datas = $section['section_content'][0]->datas;
                    $leftImages = [
                    $datas['image_1'] ?? null,
                    $datas['image_2'] ?? null,
                    $datas['image_3'] ?? null
                    ];
                    $rightImages = [
                    $datas['image_4'] ?? null,
                    $datas['image_5'] ?? null
                    ];
                    @endphp
                    <div class="row align-items-center">
                        <div class="col-7 text-end">
                            @if($leftImages[0]) <img src="{{ $leftImages[0] }}" class="img-fluid rounded" alt="img"
                                data-aos="fade-down"> @endif
                            @if($leftImages[1]) <img src="{{ $leftImages[1] }}" class="img-fluid rounded" alt="img"
                                data-aos="fade-right"> @endif
                            @if($leftImages[2]) <img src="{{ $leftImages[2] }}" class="img-fluid rounded" alt="img"
                                data-aos="fade-up"> @endif
                        </div>
                        <div class="col-5">
                            @if($rightImages[0]) <img src="{{ $rightImages[0] }}" class="img-fluid rounded" alt="img"
                                data-aos="fade-down"> @endif
                            @if($rightImages[1]) <img src="{{ $rightImages[1] }}" class="img-fluid rounded" alt="img"
                                data-aos="fade-left"> @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

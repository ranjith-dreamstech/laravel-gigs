@extends($layout)
@section('content')
<div class="breadcrumb-bar">
    <div class="breadcrumb-img">
        <div class="breadcrumb-left">
            <img src="/frontend/assets/img/bg/banner-bg-03.png" alt="img">
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">{{ __('web.home.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('web.home.categories') }}</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">
                    {{ __('web.home.browse_categories') }}
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-content">
    <div class="container">
        <div class="row">

            <!-- Category Section -->
            <div class="col-md-12">
                <div class="marketing-section">
                    <div class="marketing-content">
                        <h2>{{__('web.home.all_categories')}}</h2>
                        <p>{{ __('web.home.category_page_desc') }}</p>
                        <div class="marketing-bg">
                            <img src="/frontend/assets/img/bg/market-bg.png" alt="img" class="market-bg">
                            <img src="/frontend/assets/img/bg/market-bg-01.png" alt="img" class="market-img">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Category Section -->

            <!-- Sort By -->
            <div class="sortby-title">
                <div class="row align-items-center">
                    <div class="col-md-6">
                         <div class="label-skeleton"></div>
                        <h4 class="real-data d-none"><span class="category-count"></span> {{__('web.home.categories_found_with')}} <span><span class="service-count"></span></span> {{__('web.home.services')}}</h4>
                    </div>
                    <div class="col-md-6">
                        <!-- Sort By -->
                        <div class="filters-wrap sort-categories  justify-content-lg-end">
                            <div class="collapse-card float-lg-end">
                                <div class="filter-header">
                                    <a href="javascript:void(0);" class="sorts-list">
                                        <i class="ti ti-sort-ascending"></i>{{__('web.home.sort_by')}}:  <span class="seleced-sort"></span>
                                    </a>
                                </div>
                                <div id="categories" class="collapse-body" style="display: none;">
                                    <ul class="checkbox-list categories-lists">
                                        <li class="active sort-selection" data-sort="featured" data-name="{{__('web.home.featured')}}">
                                            <label class="custom_check">
                                                <span class="checked-title"> {{__('web.home.featured')}}</span>
                                            </label>
                                        </li>
                                        <li class="sort-selection" data-sort="asc" data-name="{{__('web.home.price')}}: {{__('web.home.low_to_high')}}">
                                            <label class="custom_check">
                                                <span class="checked-title">{{__('web.home.price')}}: {{__('web.home.low_to_high')}} </span>
                                            </label>
                                        </li>
                                        <li class="sort-selection" data-sort="desc" data-name="{{__('web.home.price')}}: {{__('web.home.high_to_low')}}">
                                            <label class="custom_check">
                                                <span class="checked-title"> {{__('web.home.price')}}: {{__('web.home.high_to_low')}} </span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /Sort By -->
                    </div>
                </div>
            </div>
            <!-- /Sort By -->
        </div>
        <div class="row loader-container">
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid category-skeleton">
                    <div class="service-img category-skeleton-img skeleton" style="height: 200px;"></div>
                    
                    <div class="avg-price category-skeleton-price">
                        <div class="skeleton" style="width: 60px; height: 14px; margin-bottom: 6px; border-radius: 4px;"></div>
                        <div class="skeleton" style="width: 80px; height: 16px; border-radius: 4px;"></div>
                    </div>
            
                    <div class="service-type d-flex justify-content-between align-items-center category-skeleton-type" style="margin-top: 16px;">
                        <div class="servive-name category-skeleton-name">
                            <div class="skeleton" style="width: 120px; height: 18px; margin-bottom: 6px; border-radius: 4px;"></div>
                            <div class="skeleton" style="width: 100px; height: 14px; border-radius: 4px;"></div>
                        </div>
                        <div class="next-arrow category-skeleton-arrow">
                            <div class="skeleton" style="width: 24px; height: 24px; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid category-skeleton">
                    <div class="service-img category-skeleton-img skeleton" style="height: 200px;"></div>
                    
                    <div class="avg-price category-skeleton-price">
                        <div class="skeleton" style="width: 60px; height: 14px; margin-bottom: 6px; border-radius: 4px;"></div>
                        <div class="skeleton" style="width: 80px; height: 16px; border-radius: 4px;"></div>
                    </div>
            
                    <div class="service-type d-flex justify-content-between align-items-center category-skeleton-type" style="margin-top: 16px;">
                        <div class="servive-name category-skeleton-name">
                            <div class="skeleton" style="width: 120px; height: 18px; margin-bottom: 6px; border-radius: 4px;"></div>
                            <div class="skeleton" style="width: 100px; height: 14px; border-radius: 4px;"></div>
                        </div>
                        <div class="next-arrow category-skeleton-arrow">
                            <div class="skeleton" style="width: 24px; height: 24px; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid category-skeleton">
                    <div class="service-img category-skeleton-img skeleton" style="height: 200px;"></div>
                    
                    <div class="avg-price category-skeleton-price">
                        <div class="skeleton" style="width: 60px; height: 14px; margin-bottom: 6px; border-radius: 4px;"></div>
                        <div class="skeleton" style="width: 80px; height: 16px; border-radius: 4px;"></div>
                    </div>
            
                    <div class="service-type d-flex justify-content-between align-items-center category-skeleton-type" style="margin-top: 16px;">
                        <div class="servive-name category-skeleton-name">
                            <div class="skeleton" style="width: 120px; height: 18px; margin-bottom: 6px; border-radius: 4px;"></div>
                            <div class="skeleton" style="width: 100px; height: 14px; border-radius: 4px;"></div>
                        </div>
                        <div class="next-arrow category-skeleton-arrow">
                            <div class="skeleton" style="width: 24px; height: 24px; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid category-skeleton">
                    <div class="service-img category-skeleton-img skeleton" style="height: 200px;"></div>
                    
                    <div class="avg-price category-skeleton-price">
                        <div class="skeleton" style="width: 60px; height: 14px; margin-bottom: 6px; border-radius: 4px;"></div>
                        <div class="skeleton" style="width: 80px; height: 16px; border-radius: 4px;"></div>
                    </div>
            
                    <div class="service-type d-flex justify-content-between align-items-center category-skeleton-type" style="margin-top: 16px;">
                        <div class="servive-name category-skeleton-name">
                            <div class="skeleton" style="width: 120px; height: 18px; margin-bottom: 6px; border-radius: 4px;"></div>
                            <div class="skeleton" style="width: 100px; height: 14px; border-radius: 4px;"></div>
                        </div>
                        <div class="next-arrow category-skeleton-arrow">
                            <div class="skeleton" style="width: 24px; height: 24px; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid category-skeleton">
                    <div class="service-img category-skeleton-img skeleton" style="height: 200px;"></div>
                    
                    <div class="avg-price category-skeleton-price">
                        <div class="skeleton" style="width: 60px; height: 14px; margin-bottom: 6px; border-radius: 4px;"></div>
                        <div class="skeleton" style="width: 80px; height: 16px; border-radius: 4px;"></div>
                    </div>
            
                    <div class="service-type d-flex justify-content-between align-items-center category-skeleton-type" style="margin-top: 16px;">
                        <div class="servive-name category-skeleton-name">
                            <div class="skeleton" style="width: 120px; height: 18px; margin-bottom: 6px; border-radius: 4px;"></div>
                            <div class="skeleton" style="width: 100px; height: 14px; border-radius: 4px;"></div>
                        </div>
                        <div class="next-arrow category-skeleton-arrow">
                            <div class="skeleton" style="width: 24px; height: 24px; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid category-skeleton">
                    <div class="service-img category-skeleton-img skeleton" style="height: 200px;"></div>
                    
                    <div class="avg-price category-skeleton-price">
                        <div class="skeleton" style="width: 60px; height: 14px; margin-bottom: 6px; border-radius: 4px;"></div>
                        <div class="skeleton" style="width: 80px; height: 16px; border-radius: 4px;"></div>
                    </div>
            
                    <div class="service-type d-flex justify-content-between align-items-center category-skeleton-type" style="margin-top: 16px;">
                        <div class="servive-name category-skeleton-name">
                            <div class="skeleton" style="width: 120px; height: 18px; margin-bottom: 6px; border-radius: 4px;"></div>
                            <div class="skeleton" style="width: 100px; height: 14px; border-radius: 4px;"></div>
                        </div>
                        <div class="next-arrow category-skeleton-arrow">
                            <div class="skeleton" style="width: 24px; height: 24px; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid category-skeleton">
                    <div class="service-img category-skeleton-img skeleton" style="height: 200px;"></div>
                    
                    <div class="avg-price category-skeleton-price">
                        <div class="skeleton" style="width: 60px; height: 14px; margin-bottom: 6px; border-radius: 4px;"></div>
                        <div class="skeleton" style="width: 80px; height: 16px; border-radius: 4px;"></div>
                    </div>
            
                    <div class="service-type d-flex justify-content-between align-items-center category-skeleton-type" style="margin-top: 16px;">
                        <div class="servive-name category-skeleton-name">
                            <div class="skeleton" style="width: 120px; height: 18px; margin-bottom: 6px; border-radius: 4px;"></div>
                            <div class="skeleton" style="width: 100px; height: 14px; border-radius: 4px;"></div>
                        </div>
                        <div class="next-arrow category-skeleton-arrow">
                            <div class="skeleton" style="width: 24px; height: 24px; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="service-grid category-skeleton">
                    <div class="service-img category-skeleton-img skeleton" style="height: 200px;"></div>
                    
                    <div class="avg-price category-skeleton-price">
                        <div class="skeleton" style="width: 60px; height: 14px; margin-bottom: 6px; border-radius: 4px;"></div>
                        <div class="skeleton" style="width: 80px; height: 16px; border-radius: 4px;"></div>
                    </div>
            
                    <div class="service-type d-flex justify-content-between align-items-center category-skeleton-type" style="margin-top: 16px;">
                        <div class="servive-name category-skeleton-name">
                            <div class="skeleton" style="width: 120px; height: 18px; margin-bottom: 6px; border-radius: 4px;"></div>
                            <div class="skeleton" style="width: 100px; height: 14px; border-radius: 4px;"></div>
                        </div>
                        <div class="next-arrow category-skeleton-arrow">
                            <div class="skeleton" style="width: 24px; height: 24px; border-radius: 50%;"></div>
                        </div>
                    </div>
                </div>
            </div>                               
        </div>
        <div class="row categories-list d-none real-data">
          
        </div>
        <!-- Load More -->
        <div class="search-load-btn">
            
        </div>
        <!-- /Load More -->
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('frontend/custom/js/home/categories.js') }}"></script>
@endpush

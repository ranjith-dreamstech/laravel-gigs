<div class="filters-section">
    <ul class="filters-wrap">
        <!-- Categories -->
        <li>
            <div class="collapse-card">
                <div class="filter-header">
                    <a href="javascript:void(0);">
                        <i class="ti ti-list page input"></i> <span class="filter-title">{{__('web.home.categories')}}</span>
                    </a>
                </div>                
                <div id="categories" class="collapse-body">
                    <div class="form-group search-group">
                        <label for="category-search" class="d-none">Category Search</label>
                        <span class="search-icon"><i class="feather-search"></i></span>
                        <input type="text" class="form-control category-search" id="category-search" placeholder="{{__('web.home.browse_categories')}}">
                    </div>
                    <ul class="checkbox-list categories-lists">
                        @if(!empty($categories) && count($categories) > 0)
                            @foreach($categories as $category)
                        <li class="active" data-id="{{ $category->id ?? ""}}">
                            <label class="custom_check">
                                <span class="checked-title">{{ $category->name ?? ""}}</span>
                            </label>
                        </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </li>
        <!-- /Categories -->

        <!-- Locations -->
        <li>
            <div class="collapse-card">
                <div class="filter-header">
                    <a href="javascript:void(0);">
                        <i class="ti ti-map-pin-pin"></i><span class="filter-title">{{__('web.home.locations')}}</span>
                    </a>
                </div>
                <div id="locations" class="collapse-body">
                    <div class="form-group search-group">
                        <label for="feather-search" class="d-none">Feather Search</label>
                        <span class="search-icon"><i class="feather-search"></i></span>
                        <input type="text" class="form-control" id="feather-search" placeholder="{{__('web.home.search_locations')}}">
                    </div>
                    <ul class="checkbox-list categories-lists">
                        <li class="active">
                            <label class="custom_check">
                                <span class="checked-title">Canada</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <span class="checked-title">Bolivia</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <span class="checked-title">Tunsania</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <span class="checked-title">Indonesia</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <span class="checked-title">UK</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <span class="checked-title">UAE</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <span class="checked-title">USA</span>
                            </label>
                        </li>
                    </ul>
                </div>
            </div>
        </li>
        <!-- /Locations -->

        <!-- Ratings -->
        <li>
            <div class="collapse-card">
                <div class="filter-header">
                    <a href="javascript:void(0);">
                        <i class="ti ti-stars"></i>{{__('web.common.reviews')}}
                    </a>
                </div>
                <div id="ratings" class="collapse-body">
                    <ul class="checkbox-list star-rate">
                        <li>
                            <label class="custom_check">
                                <input type="checkbox" name="reviews[]" value="5">
                                <span class="checkmark"></span>
                                <span class="ratings ms-4">
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                </span>
                                <span class="rating-count">(5.0)</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <input type="checkbox" name="reviews[]" value="4">
                                <span class="checkmark"></span>
                                <span class="ratings ms-4">
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star"></i>
                                </span>
                                <span class="rating-count">(4.0)</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <input type="checkbox" name="reviews[]" value="3">
                                <span class="checkmark"></span>
                                <span class="ratings ms-4">
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star "></i>
                                </span>
                                <span class="rating-count">(3.0)</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <input type="checkbox" name="reviews[]" value="2">
                                <span class="checkmark"></span>
                                <span class="ratings ms-4">
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </span>
                                <span class="rating-count">(2.0)</span>
                            </label>
                        </li>
                        <li>
                            <label class="custom_check">
                                <input type="checkbox" name="reviews[]" value="1">
                                <span class="checkmark"></span>
                                <span class="ratings ms-4">
                                    <i class="fa-solid fa-star filled"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </span>
                                <span class="rating-count">(1.0)</span>
                            </label>
                        </li>
                    </ul>
                    <div class="filter-btn">
                        <a href="javascript:void(0);" id="reset-rating">{{__('web.common.reset')}}</a>
                        <button class="btn btn-primary" id="apply-rating">{{__('web.common.apply')}}</button>
                    </div>
                </div>
            </div>
        </li>
        <!-- /Ratings -->

        <!-- Budget -->
        <li>
            <div class="collapse-card">
                <div class="filter-header">
                    <a href="javascript:void(0);">
                        <i class="ti ti-moneybag"></i>{{__('web.home.budget')}}
                    </a>
                </div>
                <div id="budget" class="collapse-body">
                    <div class="form-group">
                        <label for="custom-budget">Budget</label>
                        <input type="text" class="form-control" name="custom_budget" id="custom-budget" placeholder="{{__('web.home.enter_custom_budget')}}">
                    </div>
                    <ul class="checkbox-list">
                        <li>
                            <label class="custom_radio">
                                <input type="radio" name="budget" value="1500" >
                                <span class="checkmark"></span><span class="text-dark"> {{__('web.home.value')}} :</span>
                                {{__('web.home.under')}} $1500
                            </label>
                        </li>
                        <li>
                            <label class="custom_radio">
                                <input type="radio" name="budget" value="3000">
                                <span class="checkmark"></span><span class="text-dark"> {{__('web.home.mid_range')}}
                                    :</span> {{__('web.home.under')}} $3000
                            </label>
                        </li>
                        <li>
                            <label class="custom_radio">
                                <input type="radio" name="budget" value="4500">
                                <span class="checkmark"></span><span class="text-dark"> {{__('web.home.high_end')}}
                                    :</span> {{__('web.home.under')}} $4500
                            </label>
                        </li>
                    </ul>
                    <div class="filter-btn">
                        <a href="javascript:void(0);" id="reset-budget">{{__('web.common.reset')}}</a>
                        <button class="btn btn-primary" id="apply-budget">{{__('web.common.apply')}}</button>
                    </div>
                </div>
            </div>
        </li>
        <!-- /Budget -->

        <!-- Seller Details -->
       
        <!-- /Seller Details -->

        <!-- Delivery Time -->
        <li class="more-content">
            <div class="collapse-card">
                <div class="filter-header">
                    <a href="javascript:void(0);">
                        <img src="/frontend/assets/img/icons/time-icon.svg" alt="icon"
                            class="me-2">{{__('web.home.delivery_time')}}
                    </a>
                </div>
                <div id="deivery" class="collapse-body">
                    <ul class="checkbox-list">
                        <li>
                            <label class="custom_radio">
                                <input type="radio" name="delivery_time" value="1">
                                <span class="checkmark"></span>{{__('web.home.enter_24h')}}
                            </label>
                        </li>
                        <li>
                            <label class="custom_radio">
                                <input type="radio" name="delivery_time" value="3">
                                <span class="checkmark"></span>{{__('web.home.upto_3_days')}}
                            </label>
                        </li>
                        <li>
                            <label class="custom_radio">
                                <input type="radio" name="delivery_time" value="7">
                                <span class="checkmark"></span>{{__('web.home.upto_3_days')}}
                            </label>
                        </li>
                        <li>
                            <label class="custom_radio">
                                <input type="radio" name="delivery_time" value="">
                                <span class="checkmark"></span>{{__('web.home.anytime')}}
                            </label>
                        </li>
                    </ul>
                    <div class="filter-btn">
                        <a href="javascript:void(0);" id="reset-delivery">{{__('web.common.reset')}}</a>
                        <button class="btn btn-primary" id="apply-delivery">{{__('web.common.apply')}}</button>
                    </div>
                </div>
            </div>
        </li>
        <!-- /Delivery Time -->

        <li class="view-all">
            <a href="javascript:void(0);" class="show-more"><span><img
                        src="/frontend/assets/img/icons/add-icon.svg" alt="img"></span><span>{{ __('web.home.show_more') }}</span></a>
        </li>
    </ul>
    <!-- /Filter -->

    <!-- Sort By -->
    <div class="filters-wrap sort-categories">
        <div class="collapse-card float-lg-end">
            <div class="filter-header">
                <a href="javascript:void(0);" class="sorts-list">
                    <i class="ti ti-sort-ascending"></i>{{ __('web.home.sort_by') }}: <span class="selected-sort"></span>
                </a>
            </div>
            <div id="categories2" class="collapse-body" style="display: none;">
                <div class="form-group search-group">
                    <label for="search-category" class="d-none">Search Category</label>
                    <span class="search-icon"><i class="feather-search"></i></span>
                    <input type="text" class="form-control" id="search-category" placeholder="{{ __('web.home.search_category') }}">
                </div>
                <ul class="checkbox-list categories-lists">
                    <li class="sortfilter" data-sort="is_feature" data-name="{{__('web.home.featured')}}">
                        <label class="custom_check">
                            <span class="checked-title"> {{__('web.home.featured')}}</span>
                        </label>
                    </li>
                    <li class="sortfilter" data-sort="low_to_high" data-name="{{ __('web.home.price') }}: {{__('web.home.low_to_high')}}">
                        <label class="custom_check">
                            <span class="checked-title">{{ __('web.home.price') }}: {{__('web.home.low_to_high')}} </span>
                        </label>
                    </li>
                    <li class="sortfilter" data-sort="high_to_low" data-name="{{ __('web.home.price') }}: {{ __('web.home.high_to_low') }}">
                        <label class="custom_check">
                            <span class="checked-title"> {{ __('web.home.price') }}: {{ __('web.home.high_to_low') }} </span>
                        </label>
                    </li>
                    <li class="sortfilter" data-sort="is_recommend" data-name="{{__('web.home.recommended')}}">
                        <label class="custom_check">
                            <span class="checked-title"> {{__('web.home.recommended')}} </span>
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Sort By -->

</div>
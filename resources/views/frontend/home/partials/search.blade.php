<div class="banner-form banner-form-two" data-aos="fade-up">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <form action="{{ route('index.services') }}" method="get">
                <div class="banner-search-list">
                    <div class="input-block">
                        <label for="home-category_id">{{__('web.gigs.category_label')}}</label>
                        <select class="select" name="category_id" id="home-category_id">
                            <option value="">{{__('web.gigs.category_placeholder')}}</option>
                            @if(!empty($categories) && count($categories) > 0)
                            @foreach($categories as $category)
                            <option value="{{ $category->id ?? "" }}">{{ $category->name ?? "" }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="input-block">
                        <label for="home-location">{{__('web.user.location')}}</label>
                        <div class="input-locaion">
                            <input type="text" class="form-control" name="location" id="home-location"
                                placeholder="{{__('web.home.enter_location')}}">
                        </div>
                    </div>
                    <div class="input-block border-0 group-img">
                        <label for="keyword">{{__('web.home.keyword')}}</label>
                        <input type="text" class="form-control" name="q" placeholder="{{__('web.home.enter_keyword')}}"
                            id="keyword">
                        <ul class="suggestions-list" id="keyword-suggestions" style="display: none;"></ul>
                    </div>
                </div>
                <div class="input-block-btn">
                    <button class="btn btn-lg btn-dark d-inline-flex align-items-center justify-content-center"
                        aria-label="Search" type="submit">
                        <i class="ti ti-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

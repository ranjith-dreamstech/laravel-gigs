@extends($layout)
@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-bar">
    <div class="breadcrumb-img">
        <div class="breadcrumb-left">
            <img src="{{ asset('frontend/assets/img/bg/breadcrump-bg-01.png') }}" alt="img">
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-12">
                <nav aria-label="breadcrumb" class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="/">{{__('web.home.home')}}</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">{{__('web.blog.blog_list')}} </li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">
                    {{__('web.blog.blog_list')}}
                </h2>
            </div>
        </div>
    </div>
    <div class="breadcrumb-img">
        <div class="breadcrumb-right">
            <img src="{{ asset('frontend/assets/img/bg/breadcrump-bg-02.png') }}" alt="img">
        </div>
    </div>
</div>
<!-- /Breadcrumb -->

<!-- Page Content -->
<div class="page-content">
    <div class="container">

        <!-- Blogs -->
        <div class="row">

            <!-- Blog Sidebar -->
            <div class="col-lg-4">
                <div class="blog-sidebar card-bottom">

                    <!-- Search -->
                    <div class="card search-widget">
                        <div class="card-header">
                            <h6><img src="{{ asset('frontend/assets/img/icons/search-icon.svg') }}" alt="icon">{{__('web.blog.search')}}</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group search-group mb-0">
                                <label for="blogSearch" class="sr-only">{{ __('web.blog.to_search_type_and_hit_enter') }}</label>
                                <span class="search-icon"><i class="feather-search"></i></span>
                                <input type="text" id="blogSearch" class="form-control" placeholder="{{ __('web.blog.to_search_type_and_hit_enter') }}">
                            </div>
                        </div>

                    </div>
                    <!-- /Search -->

                    <!-- Categories -->
                    <div class="card category-widget">
                        <div class="card-header">
                            <h6><img src="{{ asset('frontend/assets/img/icons/category-icon.svg') }}" alt="icon">{{__('web.blog.categories')}}</h6>
                        </div>
                        <div class="card-body">
                            <ul class="categories">
                                @foreach($categories as $category)
                                <?php
                                $count = Modules\GeneralSetting\Models\BlogPost::where('category', $category->id)->where('deleted_at', null)->where('slug', '!=', null)->count();
                                ?>
                                <li><a href="javascript:void(0)" class="category-filter" data-category="{{ $category->name }}">{{ $category->name }} <span>{{$count}}</span></a></li>
                                @endforeach
                                <li class="mb-0">
                                    <div class="view-content">
                                        <div class="viewall-one">
                                            <ul>
                                                @foreach($categoriesLimit as $categoryLimit)
                                                <?php
                                                $countLimit = Modules\GeneralSetting\Models\BlogPost::where('category', $categoryLimit->id)->where('slug', '!=', null)->where('deleted_at', null)->count();
                                                ?>
                                                <li><a href="javascript:void(0)" class="category-filter" data-category="{{ $categoryLimit->name }}">{{ $categoryLimit->name }} <span>{{$countLimit}}</span></a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="view-all">
                                            <a href="javascript:void(0);" class="viewall-button-one">
                                            {{__('web.blog.more_categories')}}</a>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- /Categories -->

                    <!-- Recent Blogs -->
                    <div class="card recent-widget">
                        <div class="card-header">
                            <h6><img src="{{ asset('frontend/assets/img/icons/blog-icon.svg') }}" alt="icon">{{__('web.blog.recent_blogs')}}</h6>
                        </div>
                        <div class="card-body">
                            <ul class="latest-posts">
                                @foreach($latestblogs as $latest)
                                <li>
                                    <div class="post-thumb">
                                        <a href="/blog-details/{{$latest->slug}}">
                                            @php
                                            $imagePath = 'storage/' . $latest->image;
                                            $defaultImage = asset('backend/assets/img/default-placeholder-image.png');
                                            @endphp

                                            <img class="img-fluid" src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}" alt="post">
                                        </a>
                                    </div>
                                    <div class="post-info">
                                        <h6>
                                            <a href="/blog-details/{{$latest->slug}}">{{$latest->title}}</a>
                                        </h6>
                                        <div class="blog-user">
                                            <div class="blog-user-info">
                                                <p>{{$latest->customer}}</p>
                                                <p class="xs-text">{{ \Carbon\Carbon::parse($latest->created_at)->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- /Recent Blogs -->

                    <!-- Popular Tags -->
                    <div class="card tag-widget mb-0">
                        <div class="card-header">
                            <h6><img src="{{ asset('frontend/assets/img/icons/tag-icon.svg') }}" alt="icon">{{__('web.blog.popular_tags')}}</h6>
                        </div>
                        <div class="card-body">
                            <ul class="tags-list">
                                @foreach($tags as $tag)
                                <li><a href="#">{{$tag->name}} </a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- /Popular Tags -->

                </div>
            </div>
            <!-- /Blog Sidebar -->

            <div class="col-lg-8">

                <!-- Blogs -->
                <div class="blog">
                    @include('frontend.blogs.partials.blogs-list', ['blogPosts' => $blogPosts])

                </div>
                <!-- /Blogs -->
            </div>
        </div>
    </div>
</div>
<!-- /Page Content -->
@endsection
@push('scripts')
<script src="{{ asset('frontend/assets/js/blogs/blog.js') }}"></script>
@endpush

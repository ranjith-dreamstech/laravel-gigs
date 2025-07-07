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
                        <li class="breadcrumb-item">
                            <a href="/blogs">Blog</a>
                        </li>
                        <li class="breadcrumb-item" aria-current="page">Blog Details</li>
                    </ol>
                </nav>
                <h2 class="breadcrumb-title">
                    {{$blogPosts->title}}
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



                    <!-- Categories -->
                    <div class="card category-widget">
                        <div class="card-header">
                            <h6><img src="{{ asset('frontend/assets/img/icons/category-icon.svg')}}" alt="icon">{{__('web.blog.categories')}}</h6>
                        </div>
                        <div class="card-body">
                            <ul class="categories">
                                @foreach($categories as $category)
                                <?php
                                $count = Modules\GeneralSetting\Models\BlogPost::where('category', $category->id)->whereNull('deleted_at')->count();
                                ?>
                                <li><a href="javascript:void(0)" class="category-filter" data-category="{{ $category->name }}">{{ $category->name }} <span>{{$count}}</span></a></li>
                                @endforeach
                                <li class="mb-0">
                                    <div class="view-content">
                                        <div class="viewall-one">
                                            <ul>
                                                @foreach($categoriesLimit as $categoryLimit)
                                                <?php
                                                $countLimit = Modules\GeneralSetting\Models\BlogPost::where('category', $categoryLimit->id)->whereNull('deleted_at')->count();
                                                ?>
                                                <li><a href="javascript:void(0)" class="category-filter" data-category="{{ $categoryLimit->name }}">{{ $categoryLimit->name }} <span>{{$countLimit}}</span></a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="view-all">
                                            <a href="javascript:void(0);" class="viewall-button-one">More Categories</a>
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
                            <h6><img src="{{ asset('frontend/assets/img/icons/blog-icon.svg')}}" alt="icon">{{__('web.blog.recent_blogs')}}</h6>
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
                            <h6><img src="{{ asset('frontend/assets/img/icons/tag-icon.svg')}}" alt="icon">{{__('web.blog.popular_tags')}}</h6>
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

            <!-- Blog Details -->
            <div class="col-lg-8">
                <!-- Blogs -->
                <div class="row">

                    <!-- Blog Details -->
                    <div class="col-lg-10 mx-auto">
                        <div class="blog-details">
                            <div class="blog-detail-img">
                                @php
                                $imagePath = 'storage/' . $blogPosts->image;
                                $defaultImage = asset('backend/assets/img/default-placeholder-image.png');
                                @endphp

                                <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}" class="img-fluid" alt="img">

                            </div>
                            <div class="blog-content border-bottom d-flex align-items-center justify-content-between pb-4 mb-4">
                                <div class="user-info">
                                    <a href="javascript:void(0);">
                                        @php
                                        $imagePath = 'storage/' . $blogPosts->profile_image;
                                        $defaultImage = asset('backend/assets/img/default-profile.png');
                                        @endphp

                                        <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}" alt="post">

                                    </a>
                                    <div class="d-flex align-items-center">
                                        <p class="me-3"><a href="javascript:void(0);">{{$blogPosts->customer}}</a></p>
                                        <span class="d-flex align-items-center me-3"><i class="feather-calendar me-1"></i>{{ \Carbon\Carbon::parse($blogPosts->created_at)->format('d M Y') }}</span>
                                        <span class="d-flex align-items-center"><i class="feather-message-square me-1"></i>{{$countReview}} comments</span>
                                    </div>
                                </div>
                                <span class="badge-text me-3">{{$blogPosts->category}}</span>
                            </div>
                            <div class="blog-contents">
                                {!! $blogPosts->description !!}
                            </div>
                            <div class="d-flex align-items-center mb-4">
                                @php
                                $tagIds = is_array($blogPosts->tags) ? $blogPosts->tags : json_decode($blogPosts->tags, true);
                                $tagNames = \Modules\GeneralSetting\Models\BlogTag::whereIn('id', $tagIds)->pluck('name');
                                @endphp

                                @foreach($tagNames as $tagName)
                                <span class="badge-text me-3">{{ $tagName }}</span>
                                @endforeach

                            </div>
                            <div class="blog-author">
                                <h5 class="mb-4">Author</h5>
                                <div class="blog-author-text">
                                    <div class="author-img">
                                        @php
                                        $imagePath = 'storage/' . $blogPosts->profile_image;
                                        $defaultImage = asset('backend/assets/img/default-profile.png');
                                        @endphp

                                        <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}" alt="post" class="img-fluid">

                                    </div>
                                    <div class="author-detail">
                                        <h6>{{$blogPosts->customer}}</h6>
                                        <p> {{$blogPosts->profile_description}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-pagination">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="page-previous page-link">
                                            <h6><a href="/blog-details/{{$otherBlogs[0]->slug ?? ''}}"><i class="feather-chevron-left"></i>{{__('web.blog.previous_post')}}</a></h6>
                                            <p>{{$otherBlogs[0]->title ?? ''}}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <div class="page-next page-link">
                                            <a href="/blog-details/{{$otherBlogs[1]->slug ?? ''}}" class="justify-content-sm-end">{{__('web.blog.next_post')}}<i class="feather-chevron-right"></i></a>
                                            <p>{{$otherBlogs[1]->title ?? ''}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Review Lists -->
                            <div class="review-widget">
                                <div class="review-title sort-search-gigs">
                                    <div class="row align-items-center mb-4">
                                        <div class="col-sm-6">
                                            <h5>Comments ({{$countReview}})</h5>
                                        </div>

                                    </div>
                                </div>
                                <ul class="review-lists">
                                    @foreach($blogReviews as $review)
                                    <li>
                                        <div class="review-wrap">
                                            <div class="review-user-info">
                                                <div class="review-img">
                                                    <img src="{{ asset('backend/assets/img/default-profile.png')}}" alt="img">
                                                </div>
                                                <div class="reviewer-info">
                                                    <div class="reviewer-loc">
                                                        <p><a href="javascript:void(0);">{{$review->name}}</a></p>
                                                    </div>

                                                    <p>{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="review-content">
                                            <p>{{$review->comments}}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>

                            </div>
                            <!-- /Review Lists -->

                            <!-- Leave a Comment -->
                            <div class="comment-section">
                                <h6>Leave a Comment</h6>
                                <form id="blogReviewForm" action="{{ route('blogs.review.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="blog_id" value="{{ $blogPosts->id }}">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-wrap">
                                                <label class="form-label">{{ __('web.blog.full_name') }}<span class="text-danger ms-1">*</span></label>
                                                <input type="text" name="name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-wrap">
                                                <label class="form-label">{{ __('web.blog.email_address') }}<span class="text-danger ms-1">*</span></label>
                                                <input type="email" name="email" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-wrap">
                                                <label class="form-label">{{ __('web.blog.comments') }}</label>
                                                <textarea class="form-control" rows="3" placeholder="Description" name="comment"></textarea>
                                            </div>
                                            <button id="blogReviewBtn" type="submit" class="btn btn-primary">{{ __('web.blog.submit_review') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /Leave a Comment -->

                        </div>
                    </div>
                    <!-- /Blog Details -->

                </div>
            </div>
            <!-- /Blog Details -->

        </div>
    </div>
</div>
<!-- /Page Content -->

<!-- Related Posts -->
<div class="relate-post-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex align-items-center justify-content-center">
                    <h3>Related Posts</h3>
                </div>
                <div class="relate-slider owl-carousel">
                    @foreach($otherBlogs as $otherBlog)
                    <div class="blog-grid">
                        <div class="blog-img">
                            <a href="/blog-details/{{$otherBlog->slug}}">
                                @php
                                $imagePath = 'storage/' . $otherBlog->image;
                                $defaultImage = asset('backend/assets/img/default-placeholder-image.png');
                                @endphp

                                <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}" alt="post" class="img-fluid">

                            </a>
                        </div>
                        <div class="blog-content">
                            <div class="user-head">
                                <div class="badge-text">
                                    <a href="javascript:void(0);" class="badge bg-primary-light">{{$otherBlog->category}}</a>
                                </div>
                            </div>
                            <div class="blog-title">
                                <h3><a href="/blog-details/{{$otherBlog->slug}}">{{$otherBlog->title}}</a></h3>
                                <p>{{ Str::limit(strip_tags($otherBlog->description), 250, '...') }}</p>
                            </div>
                            <div class="blog-content-footer d-flex justify-content-between align-items-center">
                                <div class="user-info">
                                    <a href="/blog-details/{{$otherBlog->slug}}">
                                        @php
                                        $imagePath = 'storage/' . $otherBlog->profile_image;
                                        $defaultImage = asset('backend/assets/img/default-profile.png');
                                        @endphp

                                        <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}" alt="post"></a>
                                    <div class="d-flex align-items-center">
                                        <p class="me-2"><a href="javascript:;">{{$otherBlog->customer}}</a></p>
                                        <span class="dot me-2"></span>
                                        <span>{{ \Carbon\Carbon::parse($otherBlog->created_at)->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Related Posts -->
@endsection
@push('scripts')
<script src="{{ asset('frontend/assets/js/blogs/blog.js') }}"></script>
@endpush

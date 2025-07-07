<div class="row" id="blogListContainer">
    @if(count($blogPosts) != 0)
    @foreach($blogPosts as $blogPost)
    <!-- Blog -->
    <div class="col-lg-6 blog-post-item" id="blogs-filter-item" data-category="{{ $blogPost->category }}">
        <div class="blog-grid">
            <div class="blog-img">
                <a href="/blog-details/{{$blogPost->slug}}">
                    @php
                    $imagePath = 'storage/' . $blogPost->image;
                    $defaultImage = asset('backend/assets/img/default-placeholder-image.png');
                    @endphp

                    <img class="img-fluid" src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}" alt="post">
                </a>

            </div>
            <div class="blog-content">
                <div class="user-head">
                    <div class="badge-text">
                        <a href="javascript:void(0);" class="badge bg-primary-light">{{$blogPost->category}}</a>
                    </div>
                </div>
                <div class="blog-title">
                    <h3 class="mb-2"><a href="/blog-details/{{$blogPost->slug}}">{{$blogPost->title}}</a></h3>
                    <p>{{ Str::limit(strip_tags($blogPost->description), 150, '...') }}</p>
                </div>
                <div class="blog-content-footer d-flex justify-content-between align-items-center">
                    <div class="user-info">
                        <a href="javascript:void(0);">
                            @php
                            $imagePath = 'storage/' . $blogPost->profile_image;
                            $defaultImage = asset('backend/assets/img/default-profile.png');
                            @endphp

                            <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}" alt="post">
                        </a>
                        <div class="d-flex align-items-center">
                            <p class="me-2"><a href="javascript:void(0);">{{$blogPost->customer}}</a></p>
                            <span class="dot me-2"></span>
                            <span>{{ \Carbon\Carbon::parse($blogPost->created_at)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Blog -->
    @endforeach
    <div class="d-flex align-items-center justify-content-center">
    <a href="javascript:void(0);" id="load-more-btn"  class="btn btn-dark">{{__('web.blog.load_more')}}</a>
</div>
    @else
    <h4 class="no-blog">{{__('web.blog.no_blogs_found')}}</h4>
    @endif
</div>

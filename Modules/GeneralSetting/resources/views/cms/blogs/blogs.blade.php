@extends('admin.admin')

@section('meta_title', __('admin.blog.blogs') . ' || ' . $companyName)

@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content me-0 me-md-0 me-lg-4">
            <x-admin.breadcrumb :title="__('admin.blog.blogs')" :breadcrumbs="[
                __('admin.blog.blogs') => '',
            ]">
                <x-slot name="toolbar">
                    <div class="p-1 border rounded bg-white me-3 mb-2">
                        <a href="javascript:void(0);" id="listViewBtn"
                            class="p-1 rounded d-inline-flex align-items-center justify-content-center me-1">
                            <i class="ti ti-list-tree"></i>
                        </a>
                        <a href="javascript:void(0);" id="gridViewBtn"
                            class="p-1 rounded text-white bg-primary d-inline-flex align-items-center justify-content-center">
                            <i class="ti ti-layout-grid fs-14"></i>
                        </a>
                    </div>
                    <div class="mb-2">
                        @if (hasPermission($permissions, 'blogs', 'create'))
                            <a href="/admin/content/add-blog" class="btn btn-primary d-flex align-items-center">
                                <i class="ti ti-plus me-2"></i>{{ __('admin.blog.add_blogs') }}
                            </a>
                        @endif
                    </div>
                </x-slot>
            </x-admin.breadcrumb>
            <!-- Table Header -->
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="d-flex align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <button type="button" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                            data-bs-toggle="dropdown" id="selectedFilterTextCategory">
                            <i class="ti ti-filter me-1"></i> {{ __('admin.blog.sort_by') }} :
                            <span>{{ __('admin.blog.latest') }}</span>
                        </button>
                        <ul id="sortDropdownBlog" class="dropdown-menu dropdown-menu-end p-2">
                            <li><a href="javascript:void(0);" class="dropdown-item dropdown-item-blog rounded-1"
                                    data-filter="latest">{{ __('admin.blog.latest') }}</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item dropdown-item-blog rounded-1"
                                    data-filter="asc">{{ __('admin.blog.ascending') }}</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item dropdown-item-blog rounded-1"
                                    data-filter="desc">{{ __('admin.blog.descending') }}</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item dropdown-item-blog rounded-1"
                                    data-filter="last_month">{{ __('admin.blog.last_month') }}</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item dropdown-item-blog rounded-1"
                                    data-filter="last_7_days">{{ __('admin.blog.last_7_days') }}</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button type="button"
                            class="filtercollapse coloumn d-inline-flex align-items-center"
                            data-bs-toggle="collapse"
                            data-bs-target="#filtercollapse"
                            aria-expanded="false"
                            aria-controls="filtercollapse">
                            <i class="ti ti-filter me-1"></i> {{ __('admin.blog.filter') }}
                        </button>
                    </div>
                </div>
                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="top-search me-2">
                        <div class="top-search-group">
                            <span class="input-icon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInputBlog"
                                placeholder="{{ __('admin.blog.search') }}">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Table Header -->
            <div class="collapse" id="filtercollapse">
                <div class="filterbox mb-3 d-flex align-items-center">
                    <h6 class="me-3">{{ __('admin.blog.filter') }}</h6>
                    <div class="dropdown me-3">
                        <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            {{ __('admin.blog.category') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg p-2">
                            @foreach ($categories as $category)
                                <li>
                                    <label class="dropdown-item d-flex align-items-center rounded-1">
                                        <input class="form-check-input m-0 me-2 category-checkbox" type="checkbox"
                                            value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Blogs -->
            <div class="row blogs-cover grid-view" id="blogList">
                @foreach ($blogPosts as $blogPost)
                    <div class="col-lg-4 col-md-6 blog-item" data-date="{{ $blogPost->created_at }}"
                        data-title="{{ $blogPost->title }}" data-category="{{ $blogPost->category }}">
                        <div class="card blog-item-1">
                            <div class="card-body p-0">
                                <div class="blog-img">
                                    <a href="/admin/content/blog-details/{{ $blogPost->slug }}">
                                        @php
                                            $imagePath = 'storage/' . $blogPost->image;
                                            $defaultImage = asset('backend/assets/img/default-image-02.jpg');
                                        @endphp
                                        <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}"
                                            alt="Post">
                                    </a>
                                    <div class="edit-delete-btns d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            @if (hasPermission($permissions, 'blogs', 'edit'))
                                                <a href="javascript:;" data-id="{{ $blogPost->id }}" id="blog-edit"
                                                    class="blog-edit me-2"><i class="ti ti-edit"></i></a>
                                            @endif
                                            @if (hasPermission($permissions, 'blogs', 'delete'))
                                                <a href="javascript:void(0);" class="blog-delete" data-bs-toggle="modal"
                                                    data-id="{{ $blogPost->id }}" data-bs-target="#delete_blogs"><i
                                                        class="ti ti-trash"></i></a>
                                            @endif
                                        </div>
                                        <span class="badge badge-info badge-md">
                                            <?php
                                            $category = isset($blogPost) ? Modules\GeneralSetting\Models\BlogCategory::where('id', $blogPost->category)->first() : null;
                                            ?>
                                            {{ ucfirst($category->name) ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="blog-content">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0);">
                                                @php
                                                    $imagePath = 'storage/' . $blogPost->profile_image;
                                                    $defaultImage = asset('backend/assets/img/default-profile.png');
                                                @endphp
                                                <img src="{{ file_exists(public_path($imagePath)) ? asset($imagePath) : $defaultImage }}"
                                                    class="avatar avatar-sm rounded-circle me-1" alt="Avatar">
                                            </a>
                                            <a href="javascript:void(0);" class="fs-16">{{ $blogPost->full_name }}</a>
                                        </div>
                                        <span class="d-flex align-items-center fs-16">
                                            <i class="ti ti-calendar me-1"></i>
                                            {{ formatDateTime($blogPost->created_at, false) }}
                                        </span>
                                    </div>
                                    <h5><a
                                            href="/admin/content/blog-details/{{ $blogPost->slug }}">{{ $blogPost->title }}</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex align-items-center justify-content-center mt-3">
                @if (count($blogPosts) > 15)
                    <a href="javascript:void(0);" class="load-btn btn btn-primary mt-3">
                        <i class="ti ti-loader me-1"></i> {{ __('admin.blog.load_more') }}
                    </a>
                @elseif(count($blogPosts) == 0)
                    <p>{{ __('admin.blog.no_blog_found') }}</p>
                @endif
            </div>
            <!-- Blogs -->
        </div>
        @include('admin.partials.footer')
    </div>
    <!-- /Page Wrapper -->

    <!-- Delete Blogs -->
    <x-admin.delete-modal className="deletemodal" id="delete_blogs" :title="__('admin.blog.delete_blog')" :hiddenInputs="['delete_blog_id' => '']" :description="__('admin.blog.are_you_sure_you_want_to_delete_blog')">
    </x-admin.delete-modal>
    <!-- /Delete Blogs -->
@endsection

@push('scripts')
    <script src="{{ asset('backend/assets/js/general_setting/blog.js') }}"></script>
@endpush

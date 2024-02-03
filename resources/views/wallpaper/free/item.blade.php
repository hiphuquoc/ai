@php
    $altImage = empty($language)||$language=='vi' ? $wallpaper->name : $wallpaper->en_name;
@endphp
<div id="js_calculatorPosition_item_{{ $wallpaper->id }}" class="freeWallpaperBox_item" data-id="{{ $wallpaper->id }}">
    <div class="freeWallpaperBox_item_image">
        <img class="lazyload" src="{{ \App\Helpers\Image::getUrlImageMiniByUrlImage($wallpaper->file_cloud) }}" data-src="{{ config('main.google_cloud_storage.default_domain').$wallpaper->file_cloud }}" alt="{{ $altImage }}" title="{{ $altImage }}" />
    </div>
    <div class="freeWallpaperBox_item_box">
        <div class="freeWallpaperBox_item_box_item">
            <div class="author">
                <div class="author_image">
                    <img src="https://name.com.vn/storage/images/upload/logo-type-manager-upload.webp" alt="websitekiengiang@gmail.com" />
                </div>
                <div class="author_name maxLine_1">
                    gaixinh AI
                </div>
            </div>
        </div>
        
        <div class="freeWallpaperBox_item_box_item">
            <!-- feeling -->
            <div class="feeling">
                <div class="feeling_item">
                    {!! file_get_contents(public_path('storage/images/svg/icon-vomit-2.svg')) !!}
                </div>
                <div class="feeling_item">
                    {!! file_get_contents(public_path('storage/images/svg/icon-notLike-2.svg')) !!}
                </div>
                <div class="feeling_item">
                    {!! file_get_contents(public_path('storage/images/svg/icon-haha-2.svg')) !!}
                </div>
                <div class="feeling_item">
                    {!! file_get_contents(public_path('storage/images/svg/icon-heart-2.svg')) !!}
                </div>
            </div>
            <!-- action -->
            <div class="action">
                <a href="{{ route('search.searchByImage', ['free_wallpaper_info_id' => $wallpaper->id]) }}" class="action_item">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
                <div class="action_item">
                    <i class="fa-regular fa-thumbs-up"></i>
                </div>
                {{-- <div class="action_item" onClick="downloadImg('{{ $wallpaper->file_cloud }}');">
                    <i class="fa-solid fa-download"></i>
                </div> --}}
                <a class="action_item download" href="{{ route('ajax.downloadImgFreeWallpaper', ['file_cloud' => $wallpaper->file_cloud]) }}" download>
                    <i class="fa-solid fa-download"></i>
                </a>
                
            </div>
        </div>
    </div>
    <div class="freeWallpaperBox_item_icon">
        {!! file_get_contents(public_path('storage/images/svg/icon-heart-2.svg')) !!}
    </div>
    <div class="freeWallpaperBox_item_preventClick"></div>
</div>
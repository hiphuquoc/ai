<div class="searchMessage">
    <div class="searchMessage_image">
        <img src="{{ \App\Helpers\Image::getUrlImageMiniByUrlImage($infoFreeWallpaper->file_cloud) }}" alt="{{ empty($language)||$language=='vi' ? $infoFreeWallpaper->name : $infoFreeWallpaper->enname }}" />
    </div>
    <div class="searchMessage_content">
        @php
            $message = empty($language)||$language=='vi' ? 'Có <span class="searchMessage_content_number">'.$total.'</span> ảnh tương tự phù hợp với đặc điểm' : 'There are <span class="searchMessage_content_number">'.$total.'</span> similar photos matching the trait';
        @endphp
        {!! $message !!}
        @foreach($infoFreeWallpaper->categories as $category)
            <span class="searchMessage_content_category">
                <input type="hidden" name="search_categories[]" value="{{ $category->infoCategory->id }}" readonly />
                {{ empty($language)||$language=='vi' ? $category->infoCategory->name : $category->infoCategory->en_name }}
                <i class="fa-solid fa-xmark"></i>
            </span>
        @endforeach
    </div>
    @php
        $slug = empty($language)||$language=='vi' ? 'anh-gai-xinh' : 'photo-beautiful-girl';
    @endphp
    <a href="{{ route('routing', ['slug' => $slug]) }}" class="searchMessage_close">
        <i class="fa-solid fa-xmark"></i>
    </a>
</div>
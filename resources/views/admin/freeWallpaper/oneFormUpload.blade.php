<div class="formFreeWallpaperBox_item js_uploadWallpaper_{{ $idBox }}">
    <div id="js_addFormUpload_wallpaper_{{ $idBox }}" class="formFreeWallpaperBox_item_gallery">
        {{-- @if(!empty($wallpaper))
            <input class="form-control" type="file" name="wallpapers[]" onChange="readImageWhenChoose(this);" />
        @endif --}}
    </div>
    <div class="formFreeWallpaperBox_item_form">
        <div class="formBox">
            <div class="formBox_full">
                <div class="formBox_full_item">
                    <label class="form-label inputRequired" for="name">Alt ảnh</label>
                    <textarea class="form-control" name="name[{{ $idBox }}]" rows="1" required>{{ $wallpaper->name ?? null }}</textarea>
                </div>
                <div class="formBox_full_item">
                    <label class="form-label inputRequired" for="name">Alt ảnh EN</label>
                    <textarea class="form-control" name="en_name[{{ $idBox }}]" rows="1" required>{{ $wallpaper->en_name ?? null }}</textarea>
                </div>
                @foreach(config('main.category_type') as $type)
                    <div class="formBox_full_item">
                        <label class="form-label" for="{{ $type['key'] }}">{{ $type['name'] }}</label>
                        <select class="select2 form-select select2-hidden-accessible" name="{{ $type['key'] }}[{{ $idBox }}]" multiple="true" onchange="autoFillNameAndEnName({{ $idBox }});">
                            <option value="">- Lựa chọn -</option>
                            @if(!empty($categories))
                                @foreach($categories as $category)
                                    @if(!empty($category->seo->type)&&$category->seo->type==$type['key'])
                                        @php
                                            $selected   = null;
                                        @endphp
                                        <option value="{{ $category->id }}" data-name="{{ $category->name }}" data-en-name="{{ $category->en_name }}" {{ $selected }}>{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                @endforeach
                <div class="formBox_full_item">
                    <label class="form-label" for="description">Prompt Midjourney</label>
                    <textarea class="form-control" name="description[{{ $idBox }}]" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
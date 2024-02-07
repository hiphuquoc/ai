<div class="sortBox">
    <form id="formViewBy" action="{{ route('ajax.settingViewBy') }}" method="GET">
        <div class="sortBox_left">
            <!-- số lương -->
            <span id="js_filterProduct_count" class="quantity">
                {{ empty($language)||$language=='vi' ? 'Ảnh' : 'Photos' }} {{ $total }}
            </span> 
            <!-- sort by -->
            @php
                $titleSortBy    = empty($language)||$language=='vi' ? 'Sắp xếp theo' : 'Sort by';
                $dataSort       = config('main.sort_type');
                $sortBy         = Cookie::get('sort_by') ?? config('main.sort_type')[0]['key'];
                $inputSortBy    = null;
                foreach($dataSort as $sortItem){
                    if($sortBy==$sortItem['key']) {
                        $tmp            = empty($language)||$language=='vi' ? $sortItem['name'] : $sortItem['en_name'];
                        $inputSortBy    = $sortItem['icon'].$tmp;
                    }
                }
            @endphp
            <div class="selectCustom hide-990">
                <div class="selectCustom_text">
                    {!! $titleSortBy !!}
                </div>
                <div class="selectCustom_input">
                    {!! $inputSortBy !!}
                </div>
                <div class="selectCustom_box">
                    @foreach($dataSort as $sortItem)
                        @php
                            $selected = null;
                            if($sortBy==$sortItem['key']) $selected = 'selected';
                        @endphp
                        <div class="selectCustom_box_item {{ $selected }}" data-value-sort-by="{{ $sortItem['key'] }}" onClick="setSortBy('{{ $sortItem['key'] }}')">
                            {!! $sortItem['icon'] !!}{{ empty($language)||$language=='vi' ? $sortItem['name'] : $sortItem['en_name'] }}
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Chủ đề -->
            <div class="selectCustom hide-990">
                <div class="selectCustom_text">
                    {{ empty($language)||$language=='vi' ? 'Lọc theo Thể Loại' : 'Filter by Category' }}
                </div>
                <div class="selectCustom_input">
                    @if(!empty($categoryChoose->seo)&&$categoryChoose->seo->type=='type_info')
                        {{ empty($language)||$language=='vi' ? $categoryChoose->seo->title : $categoryChoose->en_seo->title }}
                    @else 
                        {{ empty($language)||$language=='vi' ? 'Tất cả' : 'All' }}
                    @endif
                </div>
                <div class="selectCustom_box">
                    @if(empty($language)||$language=='vi')
                        <a href="{{env('APP_URL') }}/anh-gai-xinh" class="selectCustom_box_item {{ !empty($categoryChoose->seo)&&$categoryChoose->seo->title!='Ảnh gái xinh' ? '' : 'selected' }}">
                            Tất cả
                        </a>
                    @else 
                        <a href="{{env('APP_URL') }}/photo-beautiful-girl" class="selectCustom_box_item {{ !empty($categoryChoose->en_seo)&&$categoryChoose->en_seo->title!='Photos of beautiful girl' ? '' : 'selected' }}">
                            All
                        </a>
                    @endif
                    @if(!empty($categories)&&$categories->isNotEmpty())
                        @foreach($categories as $category)
                            @if(!empty($category->seo->type)&&$category->seo->type=='type_info'&&$category->flag_show==true)
                                @php
                                    $selected = '';
                                    if(!empty($categoryChoose->id)&&$categoryChoose->id==$category->id) $selected = 'selected';
                                @endphp
                                @if(empty($language)||$language=='vi')
                                    <a href="{{ env('APP_URL') }}/{{ $category->seo->slug_full }}" class="selectCustom_box_item {{ $selected }}">
                                        {{ $category->seo->title }}
                                    </a>
                                @else   
                                    <a href="{{ env('APP_URL') }}/{{ $category->en_seo->slug_full }}" class="selectCustom_box_item {{ $selected }}">
                                        {{ $category->en_seo->title }}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <!-- Phong cách -->
            <div class="selectCustom hide-990">
                <div class="selectCustom_text">
                    {{ empty($language)||$language=='vi' ? 'Lọc theo Phong Cách' : 'Filter by Style' }}
                </div>
                <div class="selectCustom_input">
                    @if(!empty($categoryChoose->seo)&&$categoryChoose->seo->type=='style_info')
                        {{ empty($language)||$language=='vi' ? $categoryChoose->seo->title : $categoryChoose->en_seo->title }}
                    @else 
                        {{ empty($language)||$language=='vi' ? 'Tất cả' : 'All' }}
                    @endif
                </div>
                <div class="selectCustom_box">
                    @if(empty($language)||$language=='vi')
                        <a href="{{env('APP_URL') }}/anh-gai-xinh" class="selectCustom_box_item {{ !empty($styleChoose->id) ? '' : 'selected' }}">
                            Tất cả
                        </a>
                    @else 
                        <a href="{{env('APP_URL') }}/photo-beautiful-girl" class="selectCustom_box_item {{ !empty($styleChoose->id) ? '' : 'selected' }}">
                            All
                        </a>
                    @endif
                    @if(!empty($categories)&&$categories->isNotEmpty())
                        @foreach($categories as $category)
                            @if(!empty($category->seo->type)&&$category->seo->type=='style_info'&&$category->flag_show==true)
                                @php
                                    $selected = '';
                                    if(!empty($categoryChoose->id)&&$categoryChoose->id==$category->id) $selected = 'selected';
                                @endphp
                                @if(empty($language)||$language=='vi')
                                    <a href="{{ env('APP_URL') }}/{{ $category->seo->slug_full }}" class="selectCustom_box_item {{ $selected }}">
                                        {{ $category->seo->title }}
                                    </a>
                                @else   
                                    <a href="{{ env('APP_URL') }}/{{ $category->en_seo->slug_full }}" class="selectCustom_box_item {{ $selected }}">
                                        {{ $category->en_seo->title }}
                                    </a>
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <!-- icon filter nâng cao -->
            @php
                $titleAdvancedFilter = empty($language)||$language=='vi' ? 'Bộ lọc nâng cao' : 'Advanced filters';
            @endphp
            <div class="filterAdvanced">
                <div id="js_toggleFilterAdvanced_element" class="filterAdvanced_icon" onclick="toggleFilterAdvanced('js_toggleFilterAdvanced_element');">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true" height="18" class="sm:mr-2 sm:text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </div>
                <div class="filterAdvanced_text">
                    {{ $titleAdvancedFilter }}
                </div>
                <div class="filterAdvanced_box">
                    <div class="filterAdvanced_box_title">
                        <span>{{ $titleAdvancedFilter}}</span>
                        <div class="filterAdvanced_box_title_close" onclick="toggleFilterAdvanced('js_toggleFilterAdvanced_element');">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                    
                    <div class="filterAdvanced_box_content">
                        @foreach(config('main.category_type') as $type)
                            @php
                                $addClass = null;
                                if($type['key']=='type_info'||$type['key']=='style_info') $addClass = 'show-990';
                            @endphp
                            <div class="filterAdvanced_box_content_item {{ $addClass }}">
                                <div class="selectCustom">
                                    <div class="selectCustom_text">
                                        {{ empty($language)||$language=='vi' ? 'Lọc theo '.$type['name'] : 'Filter by '.$type['en_name'] }}
                                    </div>
                                    <div class="selectCustom_input">
                                        {{-- @if(!empty($categoryChoose->seo)&&$categoryChoose->seo->type=='style_info')
                                            {{ empty($language)||$language=='vi' ? $categoryChoose->seo->title : $categoryChoose->en_seo->title }}
                                        @else 
                                            {{ empty($language)||$language=='vi' ? 'Tất cả' : 'All' }}
                                        @endif --}}
                                        @php
                                            $nameSelect = empty($language)||$language=='vi' ? 'Tất cả' : 'All';
                                            if(!empty($categoryChoose->seo->type)&&$categoryChoose->seo->type==$type['key']){
                                                if(!empty($categories)&&$categories->isNotEmpty()){
                                                    foreach($categories as $category){
                                                        if($category->name==$categoryChoose->name) {
                                                            $nameSelect = empty($language)||$language=='vi' ? $category->name : $category->en_name;
                                                            break;
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        {{ $nameSelect }}
                                    </div>
                                    <div class="selectCustom_box">
                                        @if(empty($language)||$language=='vi')
                                            <a href="{{env('APP_URL') }}/anh-gai-xinh" class="selectCustom_box_item {{ !empty($styleChoose->id) ? '' : 'selected' }}">
                                                Tất cả
                                            </a>
                                        @else 
                                            <a href="{{env('APP_URL') }}/photo-beautiful-girl" class="selectCustom_box_item {{ !empty($styleChoose->id) ? '' : 'selected' }}">
                                                All
                                            </a>
                                        @endif
                                        @if(!empty($categories)&&$categories->isNotEmpty())
                                            @foreach($categories as $category)
                                                @if(!empty($category->seo->type)&&$category->seo->type==$type['key']&&$category->flag_show==true)
                                                    @php
                                                        $selected = '';
                                                        if(!empty($categoryChoose->id)&&$categoryChoose->id==$category->id) $selected = 'selected';
                                                    @endphp
                                                    @if(empty($language)||$language=='vi')
                                                        <a href="{{ env('APP_URL') }}/{{ $category->seo->slug_full }}" class="selectCustom_box_item {{ $selected }}">
                                                            {{ $category->seo->title }}
                                                        </a>
                                                    @else   
                                                        <a href="{{ env('APP_URL') }}/{{ $category->en_seo->slug_full }}" class="selectCustom_box_item {{ $selected }}">
                                                            {{ $category->en_seo->title }}
                                                        </a>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <form id="search_feeling" action="{{ Url::current() }}" method="get">
        <div class="sortBox_right">
            <div class="sortBox_right_item">
                <!-- feeling -->
                <div class="feelingBox">
                    <div class="feelingBox_item selected" onclick="setFeelingAndSubmit(this);">
                        Tất cả
                        <input type="checkbox" name="search_feeling[]" value="all" /> 
                    </div>
                    @foreach(config('main.feeling_type') as $feeling)
                        @php
                            $icon       = $feeling['icon_unactive'];
                            $checked    = null;
                            if(!empty($searchFeeling)){
                                foreach($searchFeeling as $f){
                                    if($f==$feeling['key']) {
                                        $icon = $feeling['icon'];
                                        $checked = 'checked';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="feelingBox_item" onclick="setFeelingAndSubmit(this);">
                            {!! file_get_contents(public_path($icon)) !!}
                            <input type="checkbox" name="search_feeling[]" value="{{ $feeling['key'] }}" {{ $checked }} /> 
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>

@pushonce('scriptCustom')
    <script type="text/javascript">

        function toggleFilterAdvanced(idElement){
            $('#'+idElement).toggleClass('active');
        }
        
        function setValueViewBy(idSelect) {
            var childFirst          = $('#' + idSelect + ' .selectCustom_box .selectCustom_box_item:first');
            var textSelected        = childFirst.html();
            var valueSelected       = childFirst.data('value-view-by');
            $('#'+idSelect+' .selectCustom_box').children().each(function(){
                if($(this).hasClass('selected')){
                    textSelected    = $(this).html();
                    valueSelected   = $(this).data('value-view-by');
                }
            })
            $('#'+idSelect).find(".selectCustom_input").html(textSelected);
            $('#'+idSelect).find("input").val(valueSelected);
        }

        function changeViewBy(inputElement){
            var element             = $(inputElement);
            /* nếu click đối tượng đang được chọn thì không làm gì cả */
            if(!element.hasClass('selected')){
                // Lấy nội dung của mục đã chọn
                var selectedValue   = element.data('value-view-by');
                // Cập nhật nội dung của .selectCustom_input
                element.closest(".selectCustom").find("input").val(selectedValue);
                $('#formViewBy').submit();
            }
        }

        function setSortBy(key){
            $.ajax({
                url         : '{{ route("ajax.setSortBy") }}',
                type        : 'get',
                dataType    : 'json',
                data        : {
                    key
                },
                success     : function(response){
                    location.reload();
                }
            });
        }

        function setFeelingAndSubmit(element) {
            // Xác định input checkbox trong phần tử cha của element
            var checkbox = $(element).find('input[type="checkbox"]');

            // Toggle trạng thái checked của checkbox
            checkbox.prop('checked', !checkbox.prop('checked'));

            // Submit form
            $(element).closest('form').submit();
        }
        
    </script>
@endpushonce
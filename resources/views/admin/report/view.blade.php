@extends('layouts.admin')
@section('content')

    @php
        $titlePage      = 'Thêm Category mới';
        $submit         = 'admin.category.createAndUpdate';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Category';
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="seo_id" name="seo_id" value="{{ $itemSeo->id ?? 0 }}" />
    <input type="hidden" id="category_info_id" name="category_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" />
    <input type="hidden" id="language" name="language" value="{{ $language ?? 'vi' }}" />
    <input type="hidden" id="type" name="type" value="{{ $type }}" />
        <div class="pageAdminWithRightSidebar withRightSidebar">
            <div class="pageAdminWithRightSidebar_header">
                <div style="display:flex;align-items:flex-end;">
                    <div style="width:100%;">{{ $titlePage }}</div>
                    @include('admin.template.languageBox', [
                        'item' => $item,
                        'language' => $language,
                        'routeName' => 'admin.category.view',
                    ])
                </div>
            </div>
            
            <!-- Error -->
            @if ($errors->any())
                <ul class="errorList">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <!-- MESSAGE -->
            @include('admin.template.messageAction')
            
            <div class="pageAdminWithRightSidebar_main">
                <!-- START:: Main content -->
                <div class="pageAdminWithRightSidebar_main_content">
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Thông tin trang</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.category.formPage', [
                                    'item'              => !empty($itemSourceToCopy) ? $itemSourceToCopy : $item,
                                    'itemSeo'           => !empty($itemSeoSourceToCopy) ? $itemSeoSourceToCopy : $itemSeo,
                                    'flagCopySource'    => !empty($itemSeoSourceToCopy) ? true : false,
                                ])

                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Thông tin SEO</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.form.formSeo', [
                                    'item'              => !empty($itemSourceToCopy) ? $itemSourceToCopy : $item,
                                    'itemSeo'           => !empty($itemSeoSourceToCopy) ? $itemSeoSourceToCopy : $itemSeo,
                                    'flagCopySource'    => !empty($itemSeoSourceToCopy) ? true : false,
                                    'idSeoSource'       => $itemSeoSourceToCopy->id ?? 0
                                ])
                                
                            </div>
                        </div>
                    </div>
                    <!-- nội dung -->
                    @php
                        $i = 0;
                    @endphp
                    @foreach($prompts as $prompt)
                        <!-- tiếng việt -> form viết content (đối với bản viết có nhiều box theo layout prompt viết bài) -->
                        @if($language=='vi') 
                            @if($prompt->type=='auto_content'&&$prompt->reference_name=='content')
                                <div class="pageAdminWithRightSidebar_main_content_item width100">
                                    <div class="card">
                                        <div class="card-body">
                                        
                                            @include('admin.form.formContent', [
                                                'prompt'            => $prompt,
                                                'content'           => $itemSeoSourceToCopy->contents[$i]->content ?? $itemSeo->contents[$i]->content ?? null, 
                                                'flagCopySource'    => !empty($itemSeoSourceToCopy->contents[$i]->content) ? true : false,
                                                'idBox'             => 'content_'.$i,
                                            ])
                                                
                                        </div>
                                    </div>
                                </div>
                                @php
                                    ++$i;
                                @endphp
                            @endif
                        @else 
                            <!-- tiếng khác -> form dịch -->
                            @if($prompt->type=='translate_content'&&$prompt->reference_name=='content')
                                @if(!empty($item->seo->contents))
                                    @for($i=0;$i<$item->seo->contents->count();++$i)
                                        <div class="pageAdminWithRightSidebar_main_content_item width100">
                                            <div class="card">
                                                <div class="card-body">
                                                    @include('admin.form.formContent', [
                                                        'prompt'            => $prompt,
                                                        'content'           => $itemSeoSourceToCopy->contents[$i]->content ?? $itemSeo->contents[$i]->content ?? null, 
                                                        'flagCopySource'    => !empty($itemSeoSourceToCopy->contents[$i]->content) ? true : false,
                                                        'idBox'             => 'content_'.$i,
                                                        'idContent'         => $itemSourceToCopy->seo->contents[$i]->id ?? $item->seo->contents[$i]->id ?? 0, /* truyền id của content tiếng viết (để dịch) */
                                                    ]) 
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                @endif
                            @endif
                        @endif
                    @endforeach
                </div>
                <!-- END:: Main content -->

                <!-- START:: Sidebar content -->
                <div class="pageAdminWithRightSidebar_main_rightSidebar">
                    <!-- Button Save -->
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction">
                        @if(!empty($itemSeo->slug_full))
                            <a href="/{{ $itemSeo->slug_full }}" target="_blank" style="font-size:1.4rem;"><i class="fa-regular fa-eye"></i></a>
                        @endif
                        <a href="{{ route('admin.category.list') }}" type="button" class="btn btn-secondary waves-effect waves-float waves-light">Quay lại</a>
                        <button type="submit" class="btn btn-success waves-effect waves-float waves-light" aria-label="Lưu">Lưu</button>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction">
                        <div class="btn btn-success waves-effect waves-float waves-light" aria-label="Lưu" style="width:100%;" onclick="submitForm('formAction', { 'index_google': true });">Lưu & Index</div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                        <div class="actionBox">
                            @if($language=='vi')
                                <div class="actionBox_item maxLine_1" onClick="callAI('auto_content')">
                                    <i class="fa-solid fa-robot"></i>Viết toàn trang
                                </div>
                            @else   
                                <div class="actionBox_item maxLine_1" onClick="callAI('translate_content')">
                                    <i class="fa-solid fa-language"></i>Dịch toàn trang (trực quan)
                                </div>
                                @php
                                    $promptTranslateContent = [];
                                    foreach($prompts as $prompt){
                                        if($prompt->reference_name=='content'&&$prompt->type=='translate_content'){
                                            $promptTranslateContent = $prompt;
                                            break;
                                        }
                                    }
                                    /* nếu số lượng content không rỗng < số lương prompts mới cho dùng tính năng auto translate */
                                    $countPromptAutoContent = 0;
                                    foreach($prompts as $p) if($p->reference_name=='content'&&$p->type=='auto_content') $countPromptAutoContent = $countPromptAutoContent + 1;
                                    $countContentNotEmpty = 0;
                                    foreach($itemSeo->contents as $c) if(!empty($c->content)) $countContentNotEmpty = $countContentNotEmpty + 1;
                                @endphp
                                @if(!empty($promptTranslateContent->id)&&!empty($itemSeo->id)&&$countContentNotEmpty<$countPromptAutoContent)
                                    <div class="actionBox_item maxLine_1" onClick="createJobTranslate({{ $promptTranslateContent->id }}, {{ $item->seo->id }}, {{ $itemSeo->id }}, '{{ $language }}')">
                                        <i class="fa-solid fa-language"></i>Dịch nội dung (chạy ngầm)
                                    </div>
                                @endif
                            @endif
                            @if(!empty($itemSeo->link_canonical))
                                <a href="{{ URL::current().'?id='.$item->id.'&language='.$language.'&id_seo_source='.$itemSeo->link_canonical }}" class="actionBox_item maxLine_1">
                                    <i class="fa-solid fa-file-import"></i>Copy từ trang gốc
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="customScrollBar-y" style="height: calc(100% - 90px);">
                        <!-- Form Upload -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.form.formImage')
                        </div>
                        {{-- <!-- Form Slider -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.form.formSlider')
                        </div>
                        <!-- Form Gallery -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.form.formIcon')
                        </div> --}}
                    </div>
                </div>
                <!-- END:: Sidebar content -->
            </div>
        </div>
    </form>    
@endsection
@push('scriptCustom')
    <script type="text/javascript">

        $('.repeater').repeater();

    </script>
@endpush
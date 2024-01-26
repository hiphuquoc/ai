@if(!empty($data))
    <div class="tocContentMain">
        <div class="tocContentMain_title">
            <span class="tocContentMain_title_icon"></span>
            <span class="tocContentMain_title_text">Mục lục</span>
        </div>
        <div class="tocContentMain_list" style="display: block;">
            @foreach($data as $row)
                <a href="#{{ $row['id'] }}" class="tocContentMain_list_item">
                    {{ $row['title'] }}
                </a>
            @endforeach
        </div>
        <div class="tocContentMain_close"></div>
    </div>
@endif
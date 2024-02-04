@if(!empty($infoFreeWallpaper))
    <!-- search -->
    @include('wallpaper.template.searchMessage', compact('total', 'infoFreeWallpaper', 'language'))
@else 
    <!-- filter box -->
    <form id="formViewBy" action="{{ route('ajax.settingViewBy') }}" method="GET">
        @include('wallpaper.template.sortContent', [
            'language'          => $language ?? 'vi',
            'total'             => $total,
            'categories'        => $categories ?? null,
            'categoryChoose'    => $categoryChoose ?? null
        ])
    </form>
@endif

@pushonce('scriptCustom')
    <script type="text/javascript">
        $(document).ready(function () {
            showSortBoxFreeWallpaper();
        });

        function showSortBoxFreeWallpaper(){
            const id                = "{{ $item->id ?? 0 }}";
            const total             = "{{ $total ?? 0 }}";
            $.ajax({
                url: "{{ route('ajax.showSortBoxFreeWallpaper') }}",
                type: 'get',
                dataType: 'html',
                data: {
                    id,
                    total
                },
            }).done(function (response) {
                $('#formViewBy').html(response);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.error("Ajax request failed: " + textStatus, errorThrown);
            });
        }
    </script>
@endpushonce
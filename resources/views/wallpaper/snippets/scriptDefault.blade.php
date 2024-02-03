<!-- BEGIN: SLICK -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- END: SLICK -->
<script type="text/javascript">
    $(window).ready(function(){
        /* check để xem có cookie csrf chưa (do lần đầu truy cập trang không có lỗi google login) */
        // checkToSetCsrfFirstTime();
        
        /* lazyload ảnh lần đầu */
        lazyload();
        lazyloadWithResize();
        /* lazyload ảnh khi scroll */
        $(window).on('scroll', function() {
            lazyload();
            lazyloadWithResize();
        });
        
        /* tải lại view sort cart */
        viewSortCart();

        // /* hiệu ứng */
        // $('.effectFadeIn').each(function(){
        //     $(this).css('opacity', 0);
        // });

        // buildTocContentMain('js_contentBox');

        // $('img').each(function() {
        //     if (!$(this).attr('alt') || !$(this).attr('title')) {
        //         console.log(this);
        //     }
        // });

        /* check login để hiện thị button */
        checkLoginAndSetShow();

        preventClickImgAndEffectDownload();
    });
    
    function lazyload(){
        /* đối với ảnh */
        $('img.lazyload').each(function() {
            if (!$(this).hasClass('loaded')) {
                var distance = $(window).scrollTop() - $(this).offset().top + 900;
                if (distance > 0) {
                    $(this).attr('src', $(this).attr('data-src'));
                    $(this).addClass('loaded').removeClass('loading_1').css('opacity', 1);
                }
            }
        });
        /* đối với div dùng background */
        $('div.lazyload').each(function() {
            if (!$(this).hasClass('loaded')) {
                var distance = $(window).scrollTop() - $(this).offset().top + 900;
                if (distance > 0) {
                    $(this).css({
                        background  : 'url("'+$(this).attr('data-src')+'") no-repeat center center / cover',
                        filter      : 'unset'
                    });
                    $(this).addClass('loaded');
                }
            }
        });
    }

    /* lazyload và resize từ ảnh gốc */
    function lazyloadWithResize() {
        $('img.lazyloadWithResize, div.lazyloadWithResize').each(function() {
            var boxThis = $(this);
            if (!boxThis.hasClass('loaded')) {
                var distance = $(window).scrollTop() - boxThis.offset().top + 900;
                if (distance > 0) {
                    loadImageWithResize(boxThis);
                }
            }
        });
    }
    function loadImageWithResize(boxThis) {
        const urlImage = boxThis.data('url-image');
        const resize = boxThis.data('resize');
        $.ajax({
            url: "{{ route('ajax.loadImageWithResize') }}",
            type: 'get',
            dataType: 'html',
            data: {
                url_image: urlImage,
                resize: resize
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        }).done(function (response) {
            if (boxThis.is('img')) {
                boxThis.attr('src', response);
            } else if (boxThis.is('div')) {
                boxThis.css({
                    background: 'url("' + response + '") no-repeat center center / cover',
                    filter: 'unset'
                });
            }
            boxThis.addClass('loaded');
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.error("Ajax request failed: " + textStatus, errorThrown);
        });
    }

    /* đang xây dựng */
    function noticeContrustion(){
        alert('Tính năng thanh toán online đang được xây dựng. Vui lòng quay lại sau hoặc liên hệ Zalo 0968617168');
    }

    function openCloseElemt(idElemt){
        let displayE    = $('#' + idElemt).css('display');
        if(displayE=='none'){
            $('#' + idElemt).css('display', 'block');
            $('body').css('overflow', 'hidden');
        }else {
            $('#' + idElemt).css('display', 'none');
            $('body').css('overflow', 'unset');
        }
    }
    
    /* fixed menu khi scroll đối với giao diện nhỏ hơn 990px */ 
    $(window).scroll(function(){
        // const heightMenu = $('.headerMain').outerHeight();
        if($(window).scrollTop()>300){
            $('.headerMain').addClass('fixed');
            $('.headerMain').css('opacity', '1');
        }
        if($(window).scrollTop()<=300){
            $('.headerMain').removeClass('fixed');
            $('.headerMain').css('opacity', '0');
        }
        if($(window).scrollTop()<55){
            $('.headerMain').removeClass('fixed');
            $('.headerMain').css('opacity', '1');
        }
    })
    const percentHeightScreenEffect = 1.3;
    /* hiệu ứng fade in */
    $(window).scroll(function(){
        $('.effectFadeIn').each(function(){
            const positionElement   = $(this).offset().top;
            const heightWindow      = $(window).height();
            const positionScroll    = $(window).scrollTop();
            if(parseInt(heightWindow/percentHeightScreenEffect + positionScroll)>=positionElement){
                $(this).animate({
                    opacity : 1,
                }, 800);
            }
        })
    });
    /* hiệu ứng rơi xuống => dùng cho phần tử có scrollTop thấp hơn ít nhất 1 màn hình */
    $(window).scroll(function(){
        $('.effectDropdown').each(function(){
            /* ẩn trước */
            if(!$(this).hasClass('alreadyEffectDropdown')) $(this).css('opacity', 0);
            /* thao tác */
            const positionElement   = $(this).offset().top;
            const heightWindow      = $(window).height();
            const positionScroll    = $(window).scrollTop();
            if(!$(this).hasClass('alreadyEffectDropdown')&&parseInt(heightWindow/percentHeightScreenEffect + positionScroll)>=positionElement){
                    const marginTopReal = parseInt($(this).css('margin-top'));
                    $(this).css({
                        'margin-top'    : (marginTopReal - 200)+'px'
                    });
                    $(this).animate({
                        'margin-top'    : marginTopReal+'px',
                        'opacity'       : 1
                    }, 800);
                    /* thực hiện rồi thì không thực hiện nữa */
                    $(this).addClass('alreadyEffectDropdown');
            }
        })
    });
    /* hiệu ứng xuất hiện từ trái qua phải => dùng cho phần tử có scrollTop thấp hơn ít nhất 1 màn hình */
    $(window).scroll(function(){
        $('.effectLeftToRight').each(function(){
            /* ẩn trước */
            if(!$(this).hasClass('alreadyEffectLeftToRight')) $(this).css('opacity', 0);
            /* thao tác */
            const positionElement           = $(this).offset().top;
            const heightWindow              = $(window).height();
            const positionScroll            = $(window).scrollTop();
            if(!$(this).hasClass('alreadyEffectLeftToRight')&&parseInt(heightWindow/percentHeightScreenEffect + positionScroll)>=positionElement){
                    const marginLeftReal    = parseInt($(this).css('margin-left'));
                    $(this).css({
                        'margin-left'   : (marginLeftReal - 200)+'px'
                    });
                    $(this).animate({
                        'margin-left'    : marginLeftReal+'px',
                        'opacity'       : 1
                    }, 800);
                    /* thực hiện rồi thì không thực hiện nữa */
                    $(this).addClass('alreadyEffectLeftToRight');
            }
        })
    });
    /* hiệu ứng xuất hiện từ dưới lên => dùng cho phần tử có scrollTop thấp hơn ít nhất 1 màn hình */
    $(window).scroll(function(){
        $('.effectBottomToTop').each(function(){
            /* ẩn trước */
            if(!$(this).hasClass('alreadyEffectBottomToTop')) $(this).css('opacity', 0);
            /* thao tác */
            const positionElement           = $(this).offset().top;
            const heightWindow              = $(window).height();
            const positionScroll            = $(window).scrollTop();
            if(!$(this).hasClass('alreadyEffectBottomToTop')&&parseInt(heightWindow/percentHeightScreenEffect + positionScroll)>=positionElement){
                    const marginTopReal     = parseInt($(this).css('margin-top'));
                    $(this).css({
                        'margin-top'    : (marginTopReal + 200)+'px'
                    });
                    $(this).animate({
                        'margin-top'    : marginTopReal+'px',
                        'opacity'       : 1
                    }, 800);
                    /* thực hiện rồi thì không thực hiện nữa */
                    $(this).addClass('alreadyEffectBottomToTop');
            }
        })
    });
    /* Go to top */
    mybutton 					    = document.getElementById("smoothScrollToTop");
    mybutton.style.display 	        = "none";
    window.onscroll                 = function() {scrollFunction()};
    function scrollFunction() {
        if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
            mybutton.style.display 	= "block";
        } else {
            mybutton.style.display 	= "none";
        }
    }
    function smoothScrollToTop() {
        // const currentScroll = document.documentElement.scrollTop;
        // if (currentScroll > 0) {
        //     window.requestAnimationFrame(smoothScrollToTop);
        //     window.scrollTo(0, currentScroll - currentScroll / 8);
        // }
        document.documentElement.scrollTop          = 0;
    }
    /* link to a href #id smooth */
    document.querySelectorAll('a[href^="#"]').forEach(function(a) {
        a.addEventListener('click', function(event) {
            event.preventDefault();
            let id = this.getAttribute('href');
            let element = document.querySelector(id);
            if (!element) {
                console.error(`Element with ID ${id} not found`);
                return;
            }
            let offsetTop = element.offsetTop;
            window.scrollTo({
                top: offsetTop + 200,
                behavior: 'smooth'
            });
        });
    });
    /* toggle menu mobile */
    function toggleMenuMobile(idElement){
        const element   = $('#'+idElement);
        const displayE  = element.css('display');
        if(displayE=='none'){
            /* hiển thị */
            element.css('display', 'block');
            $('body').css('overflow', 'hidden');
            $('#js_blurBackground').addClass('blurBackground');
            $('.menuTopBackground').addClass('blurBackground');
            $('.backgroundBlurMobileMenu').css('display', 'block');
        }else {
            element.css('display', 'none');
            $('body').css('overflow', 'unset');
            $('#js_blurBackground').removeClass('blurBackground');
            $('.menuTopBackground').removeClass('blurBackground');
            $('.backgroundBlurMobileMenu').css('display', 'none');
        }
    }
    /* thay đổi option của product phần hiển thị ngoài */
    function changeOption(idShow){
        const elemtShow     = $('#'+idShow);
        const parent        = elemtShow.parent();
        /* ẩn tất cả phần tử con */
        parent.children().each(function(){
            $(this).removeClass('show').addClass('hide');
        })
        /* bật lại phần tử được chọn */
        elemtShow.removeClass('hide').addClass('show');
        /* lazy load cho ảnh trong phần tử */
        elemtShow.find('img.lazyloadAfter').each(function(){
            $(this).addClass('lazyload');
            lazyload();
        })
    }
    /* hiện thông báo cho sản phẩm vào giỏ hàng thành công */
    function openCloseModal(idModal, action = null){
        const elementModal  = $('#'+idModal);
        const flag          = elementModal.css('display');
        /* tooggle */
        if(action==null){
            if(flag=='none'){
                elementModal.css('display', 'flex');
                $('#js_openCloseModal_blur').addClass('blurBackground');
                $('body').css('overflow', 'hidden');
            }else {
                elementModal.css('display', 'none');
                $('#js_openCloseModal_blur').removeClass('blurBackground');
                $('body').css('overflow', 'unset');
            }
        }
        /* đóng */
        if(action=='close'){
            elementModal.css('display', 'none');
            $('#js_openCloseModal_blur').removeClass('blurBackground');
            $('body').css('overflow', 'unset');
        }
        /* mở */
        if(action=='open'){
            elementModal.css('display', 'flex');
            $('#js_openCloseModal_blur').addClass('blurBackground');
            $('body').css('overflow', 'hidden');
        }
    }
    /* tăng giảm số lượng input quantity */
    function plusMinusQuantity(idInput, action){
        const elementInput  = $('#'+idInput);
        const valueInput    = elementInput.val();
        if(action=='minus'){
            if(valueInput>1) elementInput.val(parseInt(valueInput)-1);
        }else {
            elementInput.val(parseInt(valueInput)+1);
        }
    }
    /* thêm sản phẩm vào giỏ hàng */
    function addToCart(idProduct, idPrice, type){
        let dataForm                = {};
        dataForm.product_info_id    = idProduct;
        dataForm.product_price_id   = idPrice;
        dataForm.type               = type;
        // $('#js_addToCart_options').children().each(function(){
        //     if($(this).hasClass('selected')) {
        //         /* lấy url riêng của option được chọn */ 
        //         dataForm.product_price_id     = $(this).data('product_price_id');
        //     }
        // });
        // /* lấy id sản phẩm */ 
        // dataForm.product_info_id    = $('#product_info_id').val();
        $.ajax({
            url         : '{{ route("main.addToCart") }}',
            type        : 'get',
            dataType    : 'html',
            data        : dataForm
        }).done(function(data){
            /* reset lại value số lượng */
            $('#js_addToCart_quantity').val(1);
            /* hiện thông báo trong 5s */
            $('#js_addToCart_idWrite').html(data);
            openCloseModal('cartMessage');
            // setTimeout(() => {
            //     openCloseModal('cartMessage', 'close');
            // }, 5000);
            /* cập nhật lại thông tin giỏ hàng */ 
            viewSortCart();
        })
    }
    /* tải lại thông tin icon giỏ hàng */
    function viewSortCart(){
        $.ajax({
            url         : '{{ route("main.viewSortCart") }}',
            type        : 'get',
            dataType    : 'html',
            success     : function(response){
                $('#js_viewSortCart_idWrite').html(response);
            }
        });
    }
    // /* tải lại thành tiền khi thay đổi số lượng */
    // function updateCart(idRow, idTotal, idCount, idInput, theme = 'cartSort'){
    //     /* tải loading */ 
    //     loadLoading(idRow);
    //     /* lấy dữ liệu */
    //     const heightElementWrite    = $('#'+idRow).outerHeight();
    //     const elementInput          = $('#'+idInput);
    //     const valueInput            = elementInput.val();
    //     const idProduct             = elementInput.data('product_info_id');
    //     const idPrice               = elementInput.data('product_price_id');
    //     $.ajax({
    //         url         : '{{ route("main.updateCart") }}',
    //         type        : 'get',
    //         dataType    : 'json',
    //         data        : {
    //             product_info_id     : idProduct,
    //             product_price_id    : idPrice,
    //             quantity            : valueInput,
    //             theme
    //         },
    //         success     : function(response){
    //             setTimeout(function(){
    //                 $('#'+idRow).html(response.row);
    //                 $('#'+idTotal).html(response.total);
    //                 $('#'+idCount).html(response.count);
    //             }, 1000);
    //         }
    //     });
    // }
    /* xóa sản phẩm khỏi cart */ 
    function removeProductCart(idProduct, idProductPrice, idRow, idTotal, idCount){
        /* tải loading */ 
        loadLoading(idRow);
        $.ajax({
            url         : '{{ route("main.removeProductCart") }}',
            type        : 'get',
            dataType    : 'json',
            data        : {
                product_info_id     : idProduct,
                product_price_id    : idProductPrice
            },
            success     : function(response){
                /* cart trống */
                if(response.empty_cart!='') $('#js_checkEmptyCart_idWrite').html(response.empty_cart);
                $('#'+idTotal).html(response.total);
                $('#'+idCount).html(response.count);
                setTimeout(() => {
                    $('#'+idRow).remove();
                }, 300);
                /* trong page giỏ hàng => tải lại thành tiền */
                if (typeof loadTotalCart === 'function') {
                    loadTotalCart($('#payment_method_info_id').val());
                }
                /* trường hợp xóa không còn sản phẩm */
                if(response.isEmpty!='') {
                    $('#js_checkEmptyCart_idWrite').html(response.isEmpty);
                    $('#js_scrollMenu').remove();
                }
            }
        });
    }
    /* add loading icon */
    function loadLoading(idAppend, theme = 'loading_2'){
        $.ajax({
            url         : '{{ route("ajax.loadLoading") }}',
            type        : 'get',
            dataType    : 'html',
            data        : {
                theme       : theme
            },
            success     : function(response){
                $('#'+idAppend).append(response);
            }
        });
    }
    /* tính năng registry email ở footer */
    function submitFormRegistryEmail(idForm){
            event.preventDefault();
            const inputEmail = $('#'+idForm).find('[name*=registry_email]');
            const valueEmail = inputEmail.val();
            if(isValidEmail(valueEmail)){
                $.ajax({
                    url         : '{{ route("ajax.registryEmail") }}',
                    type        : 'get',
                    dataType    : 'json',
                    data        : {
                        registry_email : valueEmail
                    },
                    success     : function(response){
                        /* bật thông báo */
                        setMessageModal(response.title, response.content);
                    }
                });
            }else {
                inputEmail.val('');
                inputEmail.attr('placeholder', 'Email không hợp lệ!');
            }
        }
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    /* toc content */
    function buildTocContentMain(idElement){
        var dataTocContent      = {};
        var i                   = 0;
        var indexToc            = 0;
        $('#'+idElement).find('h2').each(function(){
            let dataId        = $(this).attr('id');
            if(typeof dataId=='undefined'){
                dataId          = 'randomIdTocContent_'+i;
                $(this).attr('id', dataId);
                ++indexToc;
            }
            const name          = $(this)[0].localName;
            const dataTitle     = $(this).html();
            dataTocContent[i]   = {
                id      : dataId,
                name    : name,
                title   : dataTitle
            };
            ++i;
        });
        $.ajax({
            url         : '{{ route("main.buildTocContentMain") }}',
            type        : 'get', 
            dataType    : 'html',
            data        : {
                data    : dataTocContent
            },
            success     : function(data){
                $('#tocContentMain').html(data);
                fixedTocContentIcon();
                setHeightTocFixed();

                $(window).resize(function() {
                    fixedTocContentIcon();
                    setHeightTocFixed();
                });

                $('.tocFixedIcon, .tocContentMain.tocFixed .tocContentMain_close').click(function(){
                    let elementMenu = $('.tocContentMain.tocFixed');
                    let displayMenu = elementMenu.css('display');
                    if(displayMenu=='none'){
                        elementMenu.css('display', 'block');
                    }else {
                        elementMenu.css('display', 'none');
                    }
                    // fixedTocContentIcon();
                });

                $('.tocContentMain_title, .tocContentMain_close').click(function(){
                    let elemtMenu   = $('.tocContentMain .tocContentMain_list');
                    let displayMenu = elemtMenu.css('display');
                    if(displayMenu=='none'){
                        elemtMenu.css('display', 'block');
                        $('.tocContentMain_close').removeClass('hidden');
                    }else {
                        elemtMenu.css('display', 'none');
                        $('.tocContentMain_close').addClass('hidden');
                    }
                });

                function fixedTocContentIcon(){
                    let widthS      = $(window).width();
                    let widthC      = $('.container').outerWidth();
                    let leftE       = parseInt((widthS - widthC - 70) / 2);
                    if($(window).width() < 1200){
                        leftE       = parseInt((widthS - widthC + 20) / 2);
                    }
                    $('.tocFixedIcon').css('left', leftE);
                }

                function setHeightTocFixed(){
                    let heightToc   = parseInt($(window).height() - 210);
                    $('.tocContentMain.tocFixed .tocContentMain_list').css('height', heightToc+'px');
                }

                let element         = $('#tocContentMain');
                if(element.length>0){
                    let positionE       = element.offset().top;
                    let heightE         = element.outerHeight();
                    let boxContent      = $('#'+idElement);
                    let positionB       = boxContent.offset().top;
                    let heightB         = boxContent.outerHeight();
                    let heightFooter    = $('.footerBox').outerHeight();
                    $(document).scroll(function(){
                        let scrollNow   = $(document).scrollTop();
                        let minScroll   = parseInt(heightE + positionE);
                        let maxScroll   = parseInt(heightB + positionB - heightFooter);
                        if(scrollNow > minScroll && scrollNow < maxScroll){ 
                            $('.tocFixedIcon').css('display', 'block');
                        }else {
                            $('.tocFixedIcon').css('display', 'none');
                        }
                    });
                }
            }
        });
    }
    /* validate form khi nhập */
    function validateWhenType(elementInput, type = 'empty'){
        const idElement         = $(elementInput).attr('id');
        const parent            = $(document).find('[for*="'+idElement+'"]').parent();
        /* validate empty */
        if(type=='empty'){
            const valueElement  = $.trim($(elementInput).val());
            if(valueElement!=''&&valueElement!='0'){
                parent.removeClass('validateErrorEmpty');
                parent.addClass('validateSuccess');
            }else {
                parent.removeClass('validateSuccess');
                parent.addClass('validateErrorEmpty');
            }
        }
        /* validate phone */ 
        if(type=='phone'){
            const valueElement = $.trim($(elementInput).val());
            if(valueElement.length>=10&&/^\d+$/.test(valueElement)){
                parent.removeClass('validateErrorPhone');
                parent.addClass('validateSuccess');
            }else {
                parent.removeClass('validateSuccess');
                parent.addClass('validateErrorPhone');
            }
        }
        /* validate email */ 
        if(type=='email'){
            const valueElement = $.trim($(elementInput).val());
            /* check empty (nếu required) */
            if($(elementInput).prop('required')){
                if(valueElement==''){
                    parent.removeClass('validateSuccess');
                    parent.removeClass('validateErrorEmail');
                    parent.addClass('validateErrorEmpty');
                    return false;
                }
                /* check email hợp lệ */
                if(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valueElement)){
                    parent.removeClass('validateErrorEmail');
                    parent.removeClass('validateErrorEmpty');
                    parent.addClass('validateSuccess');
                }else {
                    parent.removeClass('validateSuccess');
                    parent.removeClass('validateErrorEmpty');
                    parent.addClass('validateErrorEmail');
                }
            }else {
                /* check email hợp lệ */
                if(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valueElement)){
                    parent.removeClass('validateErrorEmail');
                    parent.removeClass('validateErrorEmpty');
                    parent.addClass('validateSuccess');
                }
            }
        }
    }
    /* load quận/huyện */
    function loadDistrictByIdProvince(elementProvince, idWrite){
        const valueProvince = $(elementProvince).val();
        $.ajax({
            url         : '{{ route("ajax.loadDistrictByIdProvince") }}',
            type        : 'get',
            dataType    : 'html',
            data        : {
                province_info_id : valueProvince
            },
            success     : function(response){
                $('#'+idWrite).html(response);
            }
        });
    }
    /* validate form */
    function validateForm(idForm){
        let error       = [];
        /* input required không được bỏ trống */
        $('#'+idForm).find('input[required]').each(function(){
            /* đưa vào mảng */
            if($(this).val()==''){
                error.push($(this).attr('name'));
            }
        })
        /* select */
        $('#'+idForm).find('select[required]').each(function(){
            if($(this).val()==0) error.push($(this).attr('name'));
        })
        return error;
    }
    // /* check csrf first time */
    // function checkToSetCsrfFirstTime(){
    //     /* dùng cho trường hợp người dùng vào trang lần đầu chưa có cookie CSRF */
    //     const flag = '{{ $_COOKIE["XSRF-TOKEN"] ?? "" }}';
    //     if(flag==''){
    //         $.ajax({
    //             url: '{{ route("main.setCsrfFirstTime") }}',
    //             dataType: 'json',
    //             type: 'get',
    //             success: function(response) {
    //                 if(response==true) location.reload();
    //             }
    //         });
    //     }
    // }
    /* check đăng nhập */
    function checkLoginAndSetShow(){
        const language = $('#language').val();
        $.ajax({
            url         : '{{ route("ajax.checkLoginAndSetShow") }}',
            type        : 'get',
            dataType    : 'json',
            data        : {
                '_token'            : '{{ csrf_token() }}',
                language
            },
            success     : function(response){
                /* button desktop */
                $('#js_checkLoginAndSetShow_button').html(response.button);
                $('#js_checkLoginAndSetShow_button').css('display', 'flex');
                /* button mobile */
                $('#js_checkLoginAndSetShow_buttonMobile').html(response.button_mobile);
                /* modal chung */
                $('#js_checkLoginAndSetShow_modal').html(response.modal);
            }
        });
    }
    /* phần wallpaper */
    function setViewAllImage(dataJson){
        const box = $('.freeWallpaperBox');
        /* tính số cột đang hiển thị */ 
        const row = calculateColumns();
        /* duyệt và set vị trí */
        var dataColumn          = {};
        box.children().each(function(index) {
            const idWallpaper = $(this).data('id');
            /* set vị trí của từng ảnh */
            const domItem   = $('#js_calculatorPosition_item_'+idWallpaper);
            if(index>=row) setViewPerImage(domItem, index, row);
            /* tính tổng chiều cao của box */
            const itemHeight = domItem.outerHeight(true);
            const columnIndex = index % row;
            if (!dataColumn[columnIndex]) dataColumn[columnIndex] = 0;
            dataColumn[columnIndex] += parseInt(itemHeight + 16);
        });
        /* set height cho toàn box -> lấy height của cột cao nhất (vì các phần tử con position: absolute; nên phải lấy theo cách này) */
        const maxHeight = Math.round(Math.max(...Object.values(dataColumn)));
        $('.freeWallpaperBox').css('min-height', maxHeight+'px');
        /* set khi load gần đến cột ngắn nhất load tiếp */ 
        const minHeight = Math.round(Math.min(...Object.values(dataColumn)));
        $('#topLoad').val(minHeight);
    }
    function setViewPerImage(domItem, indexItem, row) {
        const positionItem  = domItem.position();
        const parent        = $(domItem).parent();
        // Tìm domItemTop cách domItem 3 phần tử về phía trên
        const index         = domItem.index();
        const domItemTop    = parent.children().eq(index - row);
        const domItemBefore = domItem.prev();
        if (domItemTop.length > 0) {
            // Lấy vị trí của domItemTop
            const positionTop       = domItemTop.position();
            const positionBefore    = domItemBefore.position();
            // Thiết lập lại vị trí của domItem
            const newPositionTop    = positionTop.top + 16 + domItemTop.height();
            var newPositionleft     = positionBefore.left + 15 + domItemBefore.width();
            if(indexItem%row==0) newPositionleft = 0;
            domItem.css({
                'position': 'absolute',
                'top': newPositionTop + 'px',
                'left': newPositionleft + 'px',
                'width': domItemTop.width() + 'px',
            });
        }
    }
    function calculateColumns() {
        // Lấy chiều rộng của .freeWallpaperBox (bao gồm cả margin và padding)
        const boxWidth = $('.freeWallpaperBox').outerWidth();
        // Lấy kích thước mỗi cột trong grid
        const columnSize = $('.freeWallpaperBox').css('grid-template-columns').match(/\d+px/g);
        // Tính toán số cột hiển thị
        let totalColumns = 0;
        columnSize.forEach(size => {
            if (parseInt(size) <= boxWidth) {
            totalColumns++;
            }
        });
        return totalColumns;
    }
    /* loadmore wallpaper */
    function loadFreeWallpaperMore(requestLoad = 20){
        var boxCategory         = $('.freeWallpaperBox');
        const total             = $('#total').val();
        const loaded            = $('#loaded').val();
        
        if(boxCategory.length&&!boxCategory.hasClass('loading')&&parseInt(total)>parseInt(loaded)){
            
            const distanceLoad  = $('#topLoad').val();;
            if($(window).scrollTop() + 1500 > distanceLoad) {
                /* thực thi */
                /* thêm class để đánh dấu đăng load => không load nữa */
                boxCategory.addClass('loading');
                /* lấy dữ liệu */
                const arrayIdCategory   = $('#arrayIdCategory').val();
                $.ajax({
                    url         : '{{ route("admin.freeWallpaper.loadmoreFreeWallpapers") }}',
                    type        : 'get',
                    dataType    : 'json',
                    data        : {
                        total, loaded, arrayIdCategory, requestLoad
                    },
                    success     : function(response){
                        /* xóa bỏ class để thể hiện đã load xong */
                        boxCategory.removeClass('loading');
                        /* append dữ liệu */
                        $('#loaded').val(response.loaded);
                        if(response.content!='') {
                            boxCategory.append(response.content);
                        }
                        // Kiểm tra khi tất cả các phần tử đã được load xong
                        waitForImagesLoaded(boxCategory, function () {
                            setViewAllImage();
                        });
                        // ngân click chuột phải các ảnh được load
                        preventClickImgAndEffectDownload();
                    }
                });
            }
        }
    }
    function preventClickImgAndEffectDownload(){
        $("img").on("contextmenu", function (e) {
            e.preventDefault();
            alert("Chức năng chuột phải đã bị vô hiệu hóa cho ảnh này.");
        });
        // Xử lý sự kiện click trên .action_item ẩn box khi click download
        $('.freeWallpaperBox_item .download').on('click', function (e) {
            e.stopPropagation(); // Ngăn chặn sự kiện click từ lan tỏa lên các phần tử cha

            // Tìm phần tử .freeWallpaperBox_item_box trong phần tử cha của .action_item và thêm style display: none; với !important
            $(this).closest('.freeWallpaperBox_item').find('.freeWallpaperBox_item_box').attr('style', 'display: none !important');
        });
        $('.freeWallpaperBox_item').hover(
            function () {
                // Khi di chuột vào
                $(this).find('.freeWallpaperBox_item_box').css('display', 'flex !important');
            },
            function () {
                // Khi di chuột ra
                $(this).find('.freeWallpaperBox_item_box').css('display', 'none');
            }
        );
    }
    // Hàm kiểm tra khi tất cả các hình ảnh trong boxCategory đã được load xong
    function waitForImagesLoaded(boxCategory, callback) {
        var images = boxCategory.find('img');
        var imagesToLoad = images.length;

        if (imagesToLoad === 0) {
            callback();
        }

        images.on('load', function () {
            imagesToLoad--;
            if (imagesToLoad === 0) {
                callback();
            }
        }).each(function () {
            // Trigger sự kiện load để xử lý trường hợp ảnh đã được cache
            if (this.complete || this.complete === undefined) {
                var src = this.src;
                // Thiết lập src vào không gì để tránh lỗi caching
                this.src = '#';
                this.src = src;
            }
        });
    }
</script>
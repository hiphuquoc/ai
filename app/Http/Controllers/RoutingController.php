<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Blade;
use App\Helpers\Url;
use App\Models\Product;
use App\Models\Category;
use App\Models\Style;
use App\Models\Event;
use App\Models\Page;
use App\Models\CategoryBlog;
use App\Models\FreeWallpaper;
use App\Models\Seo;
use Illuminate\Support\Facades\Auth;


class RoutingController extends Controller{
    public function routing(Request $request, $slug, $slug2 = null, $slug3 = null, $slug4 = null, $slug5 = null, $slug6 = null, $slug7 = null, $slug8 = null, $slug9 = null, $slug10 = null, $searchByImage = null){
        /* dùng request uri */
        $tmpSlug        = explode('/', $_SERVER['REQUEST_URI']);
        /* loại bỏ phần tử rỗng */
        $arraySlug      = [];
        foreach($tmpSlug as $slug) if(!empty($slug)&&$slug!='public') $arraySlug[] = $slug;
        /* loại bỏ hashtag và request trước khi check */
        $arraySlug[count($arraySlug)-1] = preg_replace('#([\?|\#]+).*$#imsU', '', end($arraySlug));
        $urlRequest     = implode('/', $arraySlug);
        /* check url có tồn tại? => lấy thông tin */
        $checkExists    = Url::checkUrlExists(end($arraySlug));
        /* nếu sai => redirect về link đúng */
        if(!empty($checkExists->slug_full)&&$checkExists->slug_full!=$urlRequest){
            /* ko rút gọn trên 1 dòng được => lỗi */
            return Redirect::to($checkExists->slug_full, 301);
        }
        /* ============== nếu đúng => xuất dữ liệu */
        /* ngôn ngữ */
        $language                   = $checkExists->language;
        SettingController::settingLanguage($language);
        /* chế đệ xem */
        $viewBy     = Cookie::get('view_by') ?? 'set';
        if(!empty($checkExists->type)){
            $flagMatch              = false;
            /* cache HTML */
            $nameCache              = self::buildNameCache($checkExists['slug_full'], [$viewBy]).'.'.config('main.cache.extension');
            $pathCache              = Storage::path(config('main.cache.folderSave')).$nameCache;
            $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
            if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
                $xhtml              = file_get_contents($pathCache);
                echo $xhtml;
            }else {
                /* ===== Các trang chủ đề/phong cách/sự kiện ==== */
                foreach(config('main.category_type') as $type){
                    if($checkExists->type==$type['key']){
                        $flagMatch      = true;
                        /* breadcrumb */
                        $breadcrumb     = Url::buildBreadcrumb($checkExists->slug_full, $language);
                        $idSeo          = $language=='vi' ? $checkExists->id : $checkExists->seo->infoSeo->id;
                        $item           = Category::select('*')
                                            ->where('seo_id', $idSeo)
                                            ->with('seo', 'en_seo')
                                            ->first();
                        
                        /* content */
                        if($language=='en'){
                            $content        = Blade::render(Storage::get(config('main.storage.enContentCategory').$item->en_seo->slug.'.blade.php'));
                        }else {
                            $content        = Blade::render(Storage::get(config('main.storage.contentCategory').$item->seo->slug.'.blade.php'));
                        }
                        /* tìm kiếm bằng hình ảnh */
                        $idFreeWallpaper    = $request->get('idFreeWallpaper') ?? 0;
                        $infoFreeWallpaper  = FreeWallpaper::select('*')
                                                ->where('id', $idFreeWallpaper)
                                                ->first();
                        if(!empty($infoFreeWallpaper)){
                            /* trường hợp search bằng hình ảnh ::: */
                            $arrayIdCategory    = [];
                            foreach($infoFreeWallpaper->categories as $category){
                                $arrayIdCategory[] = $category->infoCategory->id;
                            }
                            $typeWhere          = 'and';
                        }else {
                            /* trường hợp bình thường không phải search ::: lấy danh sách category hiện tại và con (nếu có) */
                            $tmp                = Category::getTreeCategoryByInfoCategory($item, []);
                            $arrayIdCategory = [$item->id];
                            foreach($tmp as $t) $arrayIdCategory[] = $t->id;
                            $typeWhere          = 'or';
                        }
                        /* tìm kiếm bằng feeling */
                        $searchFeeling = $request->get('search_feeling') ?? [];
                        if(!empty($request->get('search_feeling'))){

                        }
                        /* lấy wallpapers */
                        $loaded         = 10;
                        $sortBy         = Cookie::get('sort_by') ?? null;
                        $user           = Auth::user();
                        $idUser         = $user->id ?? 0;
                        $wallpapers     = FreeWallpaper::select('*')
                                            ->whereHas('categories', function($query) use($arrayIdCategory, $typeWhere) {
                                                if(!empty($arrayIdCategory)){
                                                    if ($typeWhere == 'or') {
                                                        $query->whereIn('category_info_id', $arrayIdCategory);
                                                    } elseif ($typeWhere == 'and') {
                                                        $query->where(function($subquery) use($arrayIdCategory) {
                                                            foreach($arrayIdCategory as $c) {
                                                                $subquery->where('category_info_id', $c);
                                                            }
                                                        });
                                                    }
                                                }
                                            })
                                            ->when(empty($sortBy), function($query){
                                                $query->orderBy('id', 'DESC');
                                            })
                                            ->when($sortBy=='new'||$sortBy=='propose', function($query){
                                                $query->orderBy('id', 'DESC');
                                            })
                                            ->when($sortBy=='favourite', function($query){
                                                $query->orderBy('heart', 'DESC')
                                                        ->orderBy('id', 'DESC');
                                            })
                                            ->when($sortBy=='old', function($query){
                                                $query->orderBy('id', 'ASC');
                                            })
                                            ->when(!empty($idUser), function($query) use($idUser){
                                                $query->with(['feeling' => function($subquery) use($idUser){
                                                    $subquery->where('user_info_id', $idUser);
                                                }]);
                                            })
                                            ->skip(0)
                                            ->take($loaded)
                                            ->get();
                        $total          = FreeWallpaper::select('*')
                                            ->whereHas('categories', function($query) use($arrayIdCategory, $typeWhere) {
                                                if(!empty($arrayIdCategory)){
                                                    if ($typeWhere == 'or') {
                                                        $query->whereIn('category_info_id', $arrayIdCategory);
                                                    } elseif ($typeWhere == 'and') {
                                                        $query->where(function($subquery) use($arrayIdCategory) {
                                                            foreach($arrayIdCategory as $c) {
                                                                $subquery->where('category_info_id', $c);
                                                            }
                                                        });
                                                    }
                                                }
                                            })
                                            ->count();
                        $xhtml              = view('wallpaper.category.index', compact('item', 'breadcrumb', 'content', 'wallpapers', 'arrayIdCategory', 'total', 'loaded', 'language', 'infoFreeWallpaper', 'typeWhere', 'user', 'searchFeeling'))->render();
                    }
                }
                
                /* ===== Trang ==== */
                if($flagMatch==false&&$checkExists->type=='page_info'){
                    $flagMatch      = true;
                    $idSeo          = $language=='vi' ? $checkExists->id : $checkExists->seo->infoSeo->id;
                    /* thông tin brand */
                    $item           = Page::select('*')
                                        ->where('seo_id', $idSeo)
                                        ->with('seo', 'files')
                                        ->first();
                    /* breadcrumb */
                    $breadcrumb     = Url::buildBreadcrumb($checkExists->slug_full);
                    /* content */
                    if($language=='en'){
                        $content        = Blade::render(Storage::get(config('main.storage.enContentPage').$item->en_seo->slug.'.blade.php'));
                    }else {
                        $content        = Blade::render(Storage::get(config('main.storage.contentPage').$item->seo->slug.'.blade.php'));
                    }
                    /* page related */
                    $typePages      = Page::select('page_info.*')
                                        ->where('show_sidebar', 1)
                                        ->join('seo', 'seo.id', '=', 'page_info.seo_id')
                                        ->with('type')
                                        ->orderBy('seo.ordering', 'DESC')
                                        ->get()
                                        ->groupBy('type.id');
                    $xhtml          = view('wallpaper.page.index', compact('item', 'language', 'breadcrumb', 'content', 'typePages'))->render();
                }
                /* Ghi dữ liệu - Xuất kết quả */
                if($flagMatch==true){
                    if(env('APP_CACHE_HTML')==true) Storage::put(config('main.cache.folderSave').$nameCache, $xhtml);
                    echo $xhtml;
                }else {
                    return \App\Http\Controllers\ErrorController::error404();
                }
            }
            return false;
        }else {
            return \App\Http\Controllers\ErrorController::error404();
        }
    }

    public static function buildNameCache($slugFull, $prefix = []){
        $result     = $prefix;
        if(!empty($slugFull)){
            $tmp    = explode('/', $slugFull);
            foreach($tmp as $t){
                if(!empty($t)) $result[] = $t;
            }
            $result = implode('-', $result);
        }
        return $result;
    }
}

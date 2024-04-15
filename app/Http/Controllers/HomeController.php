<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use App\Models\Page;
use App\Models\FreeWallpaper;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Cookie;
use App\Models\Product;
use App\Models\RelationSeoEnSeo;
use Intervention\Image\ImageManagerStatic;

use GuzzleHttp\Client as ClientGuzzle;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Jobs\ReadWebsiteFix;
use Illuminate\Support\Facades\Bus;

class HomeController extends Controller{
    public static function home(Request $request){
        // /* xác định trang tiếng anh hay tiếng việt */
        // $currentRoute           = Route::currentRouteName();
        // /* lưu ngôn ngữ sử dụng */
        // $language               = $currentRoute=='main.home' ? 'vi' : 'en';
        // SettingController::settingLanguage($language);
        // /* cache HTML */
        // $nameCache              = $language.'home.'.config('main.cache.extension');
        // $pathCache              = Storage::path(config('main.cache.folderSave')).$nameCache;
        // $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        // if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
        //     $xhtml              = file_get_contents($pathCache);
        // }else {
        //     $item               = Page::select('*')
        //                             ->whereHas('type', function($query){
        //                                 $query->where('code', 'home');
        //                             })
        //                             ->when($language=='vi', function($query){
        //                                 $query->whereHas('seo', function($query){
        //                                     $query->where('slug', '/');
        //                                 });
        //                             })
        //                             ->with('seo', 'type')
        //                             ->first();
        //     $params     = [];
        //     /* tìm kiếm bằng feeling */
        //     $searchFeeling = $request->get('search_feeling') ?? [];
        //     foreach($searchFeeling as $feeling){
        //         if($feeling=='all'){ /* trường hợp tìm kiếm có all thì clear */
        //             $searchFeeling = [];
        //             break;
        //         }
        //     }
        //     /* lấy wallpapers */
        //     $arrayIdCategory                    = [];
        //     $params['array_category_info_id']   = $arrayIdCategory;
        //     $params['loaded']                   = 0;
        //     $params['request_load']             = 20;
        //     $params['sort_by']                  = Cookie::get('sort_by') ?? null;
        //     $params['filters']                  = $request->get('filters') ?? [];
        //     $tmp                                = CategoryController::getFreeWallpapers($params);
        //     $wallpapers                         = $tmp['wallpapers'];
        //     $total                              = $tmp['total'];
        //     $loaded                             = $tmp['loaded'];
        //     // dd($tmp);
        //     $user                               = Auth::user();
        //     $breadcrumb  = [];
        //     $xhtml      = view('wallpaper.category.index', compact('breadcrumb', 'item', 'arrayIdCategory', 'wallpapers', 'total', 'loaded', 'language', 'user', 'searchFeeling'))->render();
        //     /* Ghi dữ liệu - Xuất kết quả */
        //     if(env('APP_CACHE_HTML')==true) Storage::put(config('main.cache.folderSave').$nameCache, $xhtml);
        // }
        // echo $xhtml;
        return redirect()->route('routing', ['slug' => 'anh-gai-xinh']);
    }

    // public static function test(Request $request){
    //     $tags   = \App\Models\FreeWallpaper::select('*')
    //                 ->with('seo')
    //                 ->get();
    //     foreach($tags as $tag){
    //         if(!empty($tag->seo)){
    //             \App\Models\RelationSeoFreeWallpaperInfo::insertItem([
    //                 'seo_id'    => $tag->seo->id,
    //                 'free_wallpaper_info_id'   => $tag->id,
    //             ]);
    //         }
    //     }
    //     dd($tags);
    // }

    // public static function test(Request $request){

    //     $filePath = 'contents/urls_post.txt';
    //     $urls = file(storage_path('app/'.$filePath), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    //     // dd($urls);
    //     $linksWithEn = [];
    //     $linksWithoutEn = [];
    //     foreach ($urls as $url) {

    //         $client = new ClientGuzzle();
    //         $arrayData = [];

    //         try {
    //             $response = $client->get($url, ['timeout' => 30]);
    //             $statusCode = $response->getStatusCode();
                
    //             // Kiểm tra nếu mã trạng thái là 404
    //             if ($statusCode === 404) {
    //                 // echo "Link bị lỗi 404";
    //             } else {
    //                 $arrayData = self::filterContent($url);
    //             }

    //         } catch (\GuzzleHttp\Exception\RequestException $e) {
    //             // Nếu xảy ra lỗi trong quá trình yêu cầu
    //             // echo "Link bị lỗi: " . $e->getMessage();
    //         }

    //         if(!empty($arrayData)){
    //             $xhtml = view('wallpaper.template.rootytrip', compact('arrayData'))->render();
    //         }else {
    //             $xhtml = '<h2>'.$url.' <span style="color:red;">Trang không hoạt động</span></h2>';
    //         }

    //         // Đường dẫn tới file trong storage/app/contents/response.html
    //         $filePath = storage_path('app/contents/response_post.html');
    //         // Kiểm tra xem file có tồn tại không
    //         if (file_exists($filePath)) {
    //             // Đọc nội dung hiện tại của file
    //             $existingContent = file_get_contents($filePath);

    //             // Thêm nội dung mới vào cuối file
    //             file_put_contents($filePath, $existingContent . $xhtml);

    //             // Hoặc bạn có thể sử dụng Storage facade để lưu trữ file trong Laravel
    //             // Storage::disk('local')->append('contents/response.html', $xhtml);
    //         } else {
    //             // File không tồn tại, bạn có thể xử lý tùy thuộc vào yêu cầu của bạn
    //             // Ví dụ: tạo file mới và thêm nội dung vào đó
    //             file_put_contents($filePath, $xhtml);
    //         }
    //     }
    // }   
    
    // private static function filterContent($url){
    //     $response = [];
    //     if(!empty($url)){
    //         $client = new Client(HttpClient::create(['timeout' => 60]));
    //         // Gửi yêu cầu HTTP để lấy nội dung từ URL
    //         $crawler = $client->request('GET', $url);

    //         // Lấy tiêu đề, mô tả, và các thẻ từ h1 đến h6
    //         $title = $crawler->filter('title')->text();
    //         $description = $crawler->filter('meta[name="description"]')->attr('content');

    //         $headings = [];
    //         /* lấy riêng thẻ h1 ngoài class */
    //         $filteredHeadings = $crawler->filter('h1');
    //         $i          = 0;
    //         if ($filteredHeadings->count() > 0) {
    //             $filteredHeadings->each(function ($node) use (&$headings, &$i) {
    //                 // Lưu nội dung của thẻ h vào mảng $headings theo định dạng mong muốn
    //                 $level = $node->nodeName();
    //                 $headings[$i]['level']  = $level;
    //                 $headings[$i]['text']   = $node->text();
    //                 ++$i;
    //             });
    //         }
    //         /* lấy các thẻ còn lại trong content */
    //         $filteredHeadingsInSideNine = $crawler->filterXPath('//div[contains(@class, "side-nine")]//h1 | //div[contains(@class, "side-nine")]//h2 | //div[contains(@class, "side-nine")]//h3 | //div[contains(@class, "side-nine")]//h4 | //div[contains(@class, "side-nine")]//h5 | //div[contains(@class, "side-nine")]//h6');
    //         if ($filteredHeadingsInSideNine->count() > 0) {
    //             $filteredHeadingsInSideNine->each(function ($node) use (&$headings, &$i) {
    //                 // Lưu nội dung của thẻ heading trong class "side-nine"
    //                 $level = $node->nodeName();
    //                 $headings[$i]['level']  = $level;
    //                 $headings[$i]['text']   = $node->text();
    //                 ++$i;
    //             });
    //         }
    //         // Link
    //         $clientGuzzle = new ClientGuzzle();
    //         $links = [];
    //         $filteredLinksInSideNine = $crawler->filterXPath('//div[contains(@class, "side-nine")]//a');
    //         $i = 0;
    //         if ($filteredLinksInSideNine->count() > 0) {
    //             $filteredLinksInSideNine->each(function ($node) use (&$links, &$i, &$clientGuzzle) {
    //                 $links[$i]['href'] = $node->attr('href');
    //                 $links[$i]['anchor_text'] = trim($node->html());

    //                 /* Xử lý kiểm tra link hỏng */
    //                 $brokenLink = false;

    //                 // Kiểm tra xem link có chứa chỉ '#', '#abc123', '#anything'
    //                 if($links[$i]['href']=='#'||$links[$i]['href']==''){
    //                     $brokenLink = true;
    //                 } else if(preg_match('/^#.*$/', $links[$i]['href'])) {
    //                     // Không coi là link hỏng
    //                     $brokenLink = false;
    //                 } else {
    //                     try {
    //                         // Thực hiện yêu cầu với thời gian chờ là 30 giây (hoặc lớn hơn nếu cần)
    //                         $href = $links[$i]['href'];
    //                         // Kiểm tra xem $href có bắt đầu bằng "https://rootytrip.com"
    //                         if (strpos($href, 'https://rootytrip.com') === 0) {
    //                             // Kiểm tra kí tự tiếp theo
    //                             $nextCharacter = substr($href, strlen('https://rootytrip.com'), 1);

    //                             // Nếu kí tự tiếp theo là "/" thì là true, ngược lại là false
    //                             $isCorrectFormat = ($nextCharacter === '/');
    //                         } else {
    //                             // Nếu không bắt đầu bằng "https://rootytrip.com" thì là true
    //                             $isCorrectFormat = true;
    //                         }
    //                         if($isCorrectFormat==true){
    //                             $response = $clientGuzzle->get($links[$i]['href'], ['timeout' => 30]);
    //                             $statusCode = $response->getStatusCode();

    //                             // Kiểm tra xem mã trạng thái có phải là 404 không
    //                             if ($statusCode === 404) {
    //                                 $brokenLink = true;
    //                             }
    //                         }else {
    //                             $brokenLink = true;
    //                         }
    //                     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //                         // Bỏ qua mọi lỗi và coi link là hỏng
    //                         $brokenLink = true;
    //                     }
    //                 }
    //                 $links[$i]['error'] = $brokenLink;
    //                 ++$i;
    //             });
    //         }

    //         // Lấy những thẻ img trong class "side-nine" và kiểm tra thuộc tính alt và src không phải base64
    //         $imagesInSideNine = $crawler->filterXPath('//div[contains(@class, "side-nine")]//img')->each(function ($node) {
    //             $alt = $node->attr('alt');
    //             $src = $node->attr('src');

    //             // Kiểm tra xem src có bắt đầu bằng "data:image" không (base64)
    //             $isBase64 = strpos($src, 'data:image') === 0;

    //             // Chỉ thêm vào mảng nếu src không phải là base64
    //             if (!$isBase64) {
    //                 $response['check'] = !empty($alt);
    //                 $response['src'] = $src;
    //                 $response['alt'] = $alt;
    //                 return $response;
    //             }

    //             return null; // Trả về null để loại bỏ khỏi kết quả cuối cùng
    //         });
    //         // Loại bỏ các giá trị null từ mảng kết quả
    //         $imagesInSideNine = array_filter($imagesInSideNine);

    //         // Lấy những thẻ img nằm ngoài class "side-nine" không có thuộc tính alt hoặc alt rỗng
    //         $imagesOutsideSideNine = $crawler->filterXPath('//*[not(contains(@class, "side-nine"))]//img[not(@alt) or @alt=""]')->each(function ($node) {
    //             return '<img alt="" src="'.$node->attr('src').'" />';
    //         });
    //         // Thêm dữ liệu vào mảng kết quả
    //         $response = [
    //             'url' => $url,
    //             'title' => $title,
    //             'description' => $description,
    //             'headings' => $headings,
    //             'images_incontent' => $imagesInSideNine,
    //             'images_notincontent' => $imagesOutsideSideNine,
    //             'links' => $links,
    //         ];
    //     }
    //     return $response;
    // }

    public static function filterContent(Request $request){
        $response   = [];
        $url        = 'https://rootytrip.com/san-pham/combo-du-lich-phu-quoc-3-ngay-2-dem-danh-cho-cap-doi-2450k-khach-san-the-juliet-phu-quoc/';
        if(!empty($url)){
            $client = new Client(HttpClient::create(['timeout' => 60]));
            // Gửi yêu cầu HTTP để lấy nội dung từ URL
            $crawler = $client->request('GET', $url);

            // Lấy những thẻ img trong class "side-nine" và kiểm tra thuộc tính alt và src không phải base64
            $imagesInSideNine = $crawler->filterXPath('//div[contains(@class, "side-nine")]//img')->each(function ($node) {
                $alt = $node->attr('alt');
                $src = $node->attr('src');

                // Kiểm tra xem src có bắt đầu bằng "data:image" không (base64)
                $isBase64 = strpos($src, 'data:image') === 0;

                // Chỉ thêm vào mảng nếu src không phải là base64
                if (!$isBase64) {
                    $response['check'] = !empty($alt);
                    $response['src'] = $src;
                    $response['alt'] = $alt;
                    return $response;
                }

                return null; // Trả về null để loại bỏ khỏi kết quả cuối cùng
            });
            // Loại bỏ các giá trị null từ mảng kết quả
            $imagesInSideNine = array_filter($imagesInSideNine);

            // // Lấy những thẻ img nằm ngoài class "side-nine" không có thuộc tính alt hoặc alt rỗng
            // $imagesOutsideSideNine = $crawler->filterXPath('//*[not(contains(@class, "side-nine"))]//img[not(@alt) or @alt=""]')->each(function ($node) {
            //     return '<img alt="" src="'.$node->attr('src').'" />';
            // });
            $imagesOutsideSideNine = $crawler->filterXPath('//img')->each(function ($node) {
                return '<img alt="'.$node->attr('alt').'" src="'.$node->attr('src').'" />';
            });

            // Link
            $clientGuzzle = new ClientGuzzle();
            $links = [];
            $filteredLinksInSideNine = $crawler->filterXPath('//a');
            $i = 0;
            if ($filteredLinksInSideNine->count() > 0) {
                $filteredLinksInSideNine->each(function ($node) use (&$links, &$i, &$clientGuzzle) {
                    $links[$i]['href'] = $node->attr('href');
                    $links[$i]['anchor_text'] = trim($node->html());
                    $links[$i]['full'] = '<a href="'.$links[$i]['href'].'">'.$links[$i]['anchor_text'].'</a>';
                    ++$i;
                });
            }

            // Thêm dữ liệu vào mảng kết quả
            $response = [
                'links'             => $links,
                'images_incontent' => $imagesInSideNine,
                'images_notincontent' => $imagesOutsideSideNine
            ];
        }

        foreach($response['images_incontent'] as $img){
            // if(empty($img['alt'])) echo htmlspecialchars('<img alt="" src='.$img['src'].' />').'<br/>';
            echo htmlspecialchars('<img alt="'.$img['alt'].'" src="'.$img['src'].'" />').'<br/>';
        }

        // foreach($links as $link){
        //     if(empty($link['href'])) echo htmlspecialchars($link['full']).'<br/>';
        // }

        dd(123);
        return $response;
    }
}

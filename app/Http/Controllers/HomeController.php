<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use App\Models\Page;
use App\Models\FreeWallpaper;
use App\Http\Controllers\SettingController;
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
        $home                   = true;
        /* xác định trang tiếng anh hay tiếng việt */
        $currentRoute           = Route::currentRouteName();
        /* lưu ngôn ngữ sử dụng */
        $language               = $currentRoute=='main.home' ? 'vi' : 'en';
        SettingController::settingLanguage($language);
        /* cache HTML */
        $nameCache              = $language.'home.'.config('main.cache.extension');
        $pathCache              = Storage::path(config('main.cache.folderSave')).$nameCache;
        $cacheTime    	        = env('APP_CACHE_TIME') ?? 1800;
        if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
            $xhtml              = file_get_contents($pathCache);
        }else {
            $item               = Page::select('*')
                                    ->whereHas('type', function($query){
                                        $query->where('code', 'home');
                                    })
                                    ->when($language=='vi', function($query){
                                        $query->whereHas('seo', function($query){
                                            $query->where('slug', '/');
                                        });
                                    })
                                    ->when($language=='en', function($query){
                                        $query->whereHas('en_seo', function($query){
                                            $query->where('slug', 'en');
                                        });
                                    })
                                    ->with('seo', 'en_seo', 'type')
                                    ->first();
            /* tìm kiếm bằng feeling */
            $searchFeeling = $request->get('search_feeling') ?? [];
            foreach($searchFeeling as $feeling){
                if($feeling=='all'){ /* trường hợp tìm kiếm có all thì clear */
                    $searchFeeling = [];
                    break;
                }
            }
            /* lấy wallpapers */
            $arrayIdCategory = [];
            $loaded     = 10;
            $sortBy     = Cookie::get('sort_by') ?? null;
            $filters    = $request->get('filters') ?? [];
            $user       = Auth::user();
            $wallpapers = FreeWallpaper::select('*')
                            ->when(!empty($filters), function($query) use($filters){
                                foreach($filters as $filter){
                                    $query->whereHas('categories.infoCategory', function($query) use($filter){
                                        $query->where('id', $filter);
                                    });
                                }
                            })
                            ->when(!empty($searchFeeling), function($query) use ($searchFeeling) {
                                $query->whereHas('feeling', function($subquery) use ($searchFeeling) {
                                    $subquery->whereIn('type', $searchFeeling);
                                });
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
                            ->skip(0)
                            ->take($loaded)
                            ->get();
            $total      = FreeWallpaper::count();
            $breadcrumb  = [];
            $xhtml      = view('wallpaper.category.index', compact('home', 'breadcrumb', 'item', 'arrayIdCategory', 'wallpapers', 'total', 'loaded', 'language', 'user', 'searchFeeling'))->render();
            /* Ghi dữ liệu - Xuất kết quả */
            if(env('APP_CACHE_HTML')==true) Storage::put(config('main.cache.folderSave').$nameCache, $xhtml);
        }
        echo $xhtml;
    }

    // public static function test(Request $request){
        // $client = new Client();

        // $response = $client->post('https://api.cognitive.microsoft.com/bing/v7.0/search', [
        //     'headers' => [
        //         'Ocp-Apim-Subscription-Key' => env('BING_AI_API_KEY'),
        //         'Content-Type' => 'application/json',
        //     ],
        //     'json' => [
        //         'question' => 'Your chat question here',
        //     ],
        // ]);

        // $result = json_decode($response->getBody(), true);

        // dd($result);

        // $styles = \App\Models\Event::select('*')
        //             ->with('seo', 'en_seo')
        //             ->get();
        // foreach($styles as $style){
        //     /* insert SEO */
        //     $insertSeo      = $style->seo->toArray();
        //     unset($insertSeo['id'], $insertSeo['created_at'], $insertSeo['updated_at']);
        //     $idSeo          = \App\Models\Seo::insertItem($insertSeo);
        //     /* insert EN_SEO */
        //     $insertEnSeo    = $style->en_seo->toArray();
        //     unset($insertEnSeo['id'], $insertEnSeo['created_at'], $insertEnSeo['updated_at']);
        //     $idEnSeo        = \App\Models\EnSeo::insertItem($insertEnSeo);
        //     /* delete relation */
        //     $tmp = RelationSeoEnSeo::select('*')
        //             ->where('en_seo_id', $style->en_seo->id)
        //             ->delete();
        //     RelationSeoEnSeo::insertItem([
        //         'seo_id'    => $idSeo,
        //         'en_seo_id' => $idEnSeo
        //     ]);

        //     /* insert category_info */
        //     $insertCategory     = $style->toArray();
        //     unset($insertCategory['id'], $insertCategory['created_at'], $insertCategory['updated_at'], $insertCategory['seo'], $insertCategory['en_seo']);
        //     $insertCategory['seo_id'] = $idSeo;
        //     $insertCategory['en_seo_id'] = $idEnSeo;
        //     $idCategory = \App\Models\Category::insertItem($insertCategory);
        //     echo '<pre>';
        //     print_r('<div>'.$idCategory.'</div>');
        //     echo '</pre>';
        // }

        // $arrayIdSeo = [];
        // $arrayIdEnSeo = [];
        // $tmp = Category::all();
        // foreach($tmp as $t){
        //     $arrayIdSeo[] = $t->seo->id;
        //     $arrayIdEnSeo[] = $t->en_seo->id;;
        // }
        // $tmp = Page::all();
        // foreach($tmp as $t){
        //     $arrayIdSeo[] = $t->seo->id;
        //     $arrayIdEnSeo[] = $t->en_seo->id;;
        // }
        // $tmp = Product::all();
        // foreach($tmp as $t){
        //     $arrayIdSeo[] = $t->seo->id;
        //     $arrayIdEnSeo[] = $t->en_seo->id;;
        // }

        // $result = \App\Models\Seo::select('*')
        //             ->whereNotIn('id', $arrayIdSeo)
        //             ->delete();
        // dd($result->toArray());

        // $sitemapUrls = [
        //     'https://rootytrip.com/post-sitemap1.xml',
        //     'https://rootytrip.com/page-sitemap.xml',
        //     'https://rootytrip.com/e-landing-page-sitemap.xml',
        //     'https://rootytrip.com/product-sitemap.xml',
        //     'https://rootytrip.com/mona_experiences-sitemap.xml'
        // ];

        // $urls = [];

        // foreach ($sitemapUrls as $sitemapUrl) {
        //     $response = Http::get($sitemapUrl);

        //     if ($response->successful()) {
        //         $sitemapContent = $response->body();
        //         $xml = simplexml_load_string($sitemapContent);
        //         $xmlArray = json_decode(json_encode($xml), true);

        //         $urls = array_merge($urls, self::extractUrlsFromSitemap($xmlArray));
        //     }
        // }

        // // Lưu danh sách URL vào file
        // $filePath   = 'contents/urls.txt';
        // $urlsString = implode(PHP_EOL, $urls);
        // $flag       = Storage::put($filePath, $urlsString);

        // // Lưu danh sách URL vào file
        // $filePath   = 'contents/urls.txt';
        // $urlsString = implode(PHP_EOL, $urls);
        // $flag       = Storage::put($filePath, $urlsString);
        /*
        
        Title: 
        Description:
        Url: 

        <h1>Nội dung h1</h1>
            <h2>Nội dung</h2>
        */
        
        // $filePath = 'contents/urls_post.txt';
        // $urls = file(storage_path('app/'.$filePath), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // $result = [];
        // foreach ($urls as $url) {

            

        // }
    // }

    public static function test(Request $request){

        $filePath = 'contents/urls_post.txt';
        $urls = file(storage_path('app/'.$filePath), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // dd($urls);
        $linksWithEn = [];
        $linksWithoutEn = [];
        foreach ($urls as $url) {

            $client = new ClientGuzzle();
            $arrayData = [];

            try {
                $response = $client->get($url, ['timeout' => 30]);
                $statusCode = $response->getStatusCode();
                
                // Kiểm tra nếu mã trạng thái là 404
                if ($statusCode === 404) {
                    // echo "Link bị lỗi 404";
                } else {
                    $arrayData = self::filterContent($url);
                }

            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // Nếu xảy ra lỗi trong quá trình yêu cầu
                // echo "Link bị lỗi: " . $e->getMessage();
            }

            if(!empty($arrayData)){
                $xhtml = view('wallpaper.template.rootytrip', compact('arrayData'))->render();
            }else {
                $xhtml = '<h2>'.$url.' <span style="color:red;">Trang không hoạt động</span></h2>';
            }

            // Đường dẫn tới file trong storage/app/contents/response.html
            $filePath = storage_path('app/contents/response_post.html');
            // Kiểm tra xem file có tồn tại không
            if (file_exists($filePath)) {
                // Đọc nội dung hiện tại của file
                $existingContent = file_get_contents($filePath);

                // Thêm nội dung mới vào cuối file
                file_put_contents($filePath, $existingContent . $xhtml);

                // Hoặc bạn có thể sử dụng Storage facade để lưu trữ file trong Laravel
                // Storage::disk('local')->append('contents/response.html', $xhtml);
            } else {
                // File không tồn tại, bạn có thể xử lý tùy thuộc vào yêu cầu của bạn
                // Ví dụ: tạo file mới và thêm nội dung vào đó
                file_put_contents($filePath, $xhtml);
            }
        }
    }   
    
    private static function filterContent($url){
        $response = [];
        if(!empty($url)){
            $client = new Client(HttpClient::create(['timeout' => 60]));
            // Gửi yêu cầu HTTP để lấy nội dung từ URL
            $crawler = $client->request('GET', $url);

            // Lấy tiêu đề, mô tả, và các thẻ từ h1 đến h6
            $title = $crawler->filter('title')->text();
            $description = $crawler->filter('meta[name="description"]')->attr('content');

            $headings = [];
            /* lấy riêng thẻ h1 ngoài class */
            $filteredHeadings = $crawler->filter('h1');
            $i          = 0;
            if ($filteredHeadings->count() > 0) {
                $filteredHeadings->each(function ($node) use (&$headings, &$i) {
                    // Lưu nội dung của thẻ h vào mảng $headings theo định dạng mong muốn
                    $level = $node->nodeName();
                    $headings[$i]['level']  = $level;
                    $headings[$i]['text']   = $node->text();
                    ++$i;
                });
            }
            /* lấy các thẻ còn lại trong content */
            $filteredHeadingsInSideNine = $crawler->filterXPath('//div[contains(@class, "side-nine")]//h1 | //div[contains(@class, "side-nine")]//h2 | //div[contains(@class, "side-nine")]//h3 | //div[contains(@class, "side-nine")]//h4 | //div[contains(@class, "side-nine")]//h5 | //div[contains(@class, "side-nine")]//h6');
            if ($filteredHeadingsInSideNine->count() > 0) {
                $filteredHeadingsInSideNine->each(function ($node) use (&$headings, &$i) {
                    // Lưu nội dung của thẻ heading trong class "side-nine"
                    $level = $node->nodeName();
                    $headings[$i]['level']  = $level;
                    $headings[$i]['text']   = $node->text();
                    ++$i;
                });
            }
            // Link
            $clientGuzzle = new ClientGuzzle();
            $links = [];
            $filteredLinksInSideNine = $crawler->filterXPath('//div[contains(@class, "side-nine")]//a');
            $i = 0;
            if ($filteredLinksInSideNine->count() > 0) {
                $filteredLinksInSideNine->each(function ($node) use (&$links, &$i, &$clientGuzzle) {
                    $links[$i]['href'] = $node->attr('href');
                    $links[$i]['anchor_text'] = trim($node->html());

                    /* Xử lý kiểm tra link hỏng */
                    $brokenLink = false;

                    // Kiểm tra xem link có chứa chỉ '#', '#abc123', '#anything'
                    if($links[$i]['href']=='#'||$links[$i]['href']==''){
                        $brokenLink = true;
                    } else if(preg_match('/^#.*$/', $links[$i]['href'])) {
                        // Không coi là link hỏng
                        $brokenLink = false;
                    } else {
                        try {
                            // Thực hiện yêu cầu với thời gian chờ là 30 giây (hoặc lớn hơn nếu cần)
                            $href = $links[$i]['href'];
                            // Kiểm tra xem $href có bắt đầu bằng "https://rootytrip.com"
                            if (strpos($href, 'https://rootytrip.com') === 0) {
                                // Kiểm tra kí tự tiếp theo
                                $nextCharacter = substr($href, strlen('https://rootytrip.com'), 1);

                                // Nếu kí tự tiếp theo là "/" thì là true, ngược lại là false
                                $isCorrectFormat = ($nextCharacter === '/');
                            } else {
                                // Nếu không bắt đầu bằng "https://rootytrip.com" thì là true
                                $isCorrectFormat = true;
                            }
                            if($isCorrectFormat==true){
                                $response = $clientGuzzle->get($links[$i]['href'], ['timeout' => 30]);
                                $statusCode = $response->getStatusCode();

                                // Kiểm tra xem mã trạng thái có phải là 404 không
                                if ($statusCode === 404) {
                                    $brokenLink = true;
                                }
                            }else {
                                $brokenLink = true;
                            }
                        } catch (\GuzzleHttp\Exception\RequestException $e) {
                            // Bỏ qua mọi lỗi và coi link là hỏng
                            $brokenLink = true;
                        }
                    }
                    $links[$i]['error'] = $brokenLink;
                    ++$i;
                });
            }

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

            // Lấy những thẻ img nằm ngoài class "side-nine" không có thuộc tính alt hoặc alt rỗng
            $imagesOutsideSideNine = $crawler->filterXPath('//*[not(contains(@class, "side-nine"))]//img[not(@alt) or @alt=""]')->each(function ($node) {
                return '<img alt="" src="'.$node->attr('src').'" />';
            });
            // Thêm dữ liệu vào mảng kết quả
            $response = [
                'url' => $url,
                'title' => $title,
                'description' => $description,
                'headings' => $headings,
                'images_incontent' => $imagesInSideNine,
                'images_notincontent' => $imagesOutsideSideNine,
                'links' => $links,
            ];
        }
        return $response;
    }
}

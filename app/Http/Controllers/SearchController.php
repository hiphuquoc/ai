<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic;
use App\Models\District;
use App\Models\Product;
use App\Models\Category;
use App\Models\Style;
use App\Models\Event;
use App\Models\FreeWallpaper;
use App\Models\RegistryEmail;
use Illuminate\Support\Facades\Cookie;
use App\Services\BuildInsertUpdateModel;

class SearchController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public static function searchByImage(Request $request){
        $idFreeWallpaper    = $request->get('free_wallpaper_info_id') ?? 0;
        $language           = $request->session()->get('language') ?? 'vi';
        $slug               = $language=='vi' ? 'anh-gai-xinh' : 'photo-beautiful-girl';
        if(!empty($idFreeWallpaper)){
            return redirect()->route('routing', [
                'slug'              => $slug,
                'idFreeWallpaper'   => $idFreeWallpaper
            ]);
        }
        return redirect()->back()->withInput();
    }

}

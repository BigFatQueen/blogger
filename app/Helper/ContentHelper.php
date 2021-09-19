<?php

namespace App\Helper;

use App\Content;
use App\User;

class ContentHelper {

    public static function search($creator_id, $category_id, $keyword) {
        $keyword = ($keyword == null) ? '' : $keyword;

        if ($creator_id != null && $category_id != null) {

            $contents = Content::where([
                ['creator_id', '=', $creator_id],
                ['category_id', '=', $category_id],
                ['title', 'LIKE', "%$keyword%"],
            ])->orderBy('id', 'desc')->paginate(10);
            
        }elseif ($creator_id != null) {

            $contents = Content::where([
                ['creator_id', '=', $creator_id],
                ['title', 'LIKE', "%$keyword%"],
            ])->orderBy('id', 'desc')->paginate(10);
            
        }elseif ($category_id != null) {

            $contents = Content::where([
                ['category_id', '=', $category_id],
                ['title', 'LIKE', "%$keyword%"],
            ])->orderBy('id', 'desc')->paginate(10);
            
        }else {
            $contents = Content::orderBy('id', 'desc')->paginate(10);
        }
        return $contents;
    }
}
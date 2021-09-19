<?php

namespace App\Helper;
use App\User;

class UserHelper {

    public static function search($role_id, $permission_id, $keyword) {
        $keyword = ($keyword == null) ? '' : $keyword;

        if ($permission_id != null) {

            $users = User::whereHas('permissions', function ($query) use ($permission_id) {
                return $query->where('id', '=' , $permission_id);
            })->where([
                ['role_id', '=', $role_id],
            ])->orderBy('id', 'desc')->paginate(10);
            
        }else {
            $users = User::where('role_id', $role_id)->orderBy('id', 'desc')->paginate(10);
        }
        return $users;
    }
}
<?php

namespace App\Http\Format;

use App\Models\User;

class UserFormat
{

    public static function toList(User $user)
    {
        $ret = $user;
        $ret["nickname"] = $user->name;

        return $ret;
    }

    public static function toDetail(User $user)
    {
        $ret = self::toList($user);
        $ret['phone'] = $user->phone;
        $ret['profile'] = $user->profile;

        return $ret;
    }

    public static function toAuth(User $user)
    {
        $ret = self::toList($user);
        $ret['token'] = $user->token;

        return $ret;
    }

}

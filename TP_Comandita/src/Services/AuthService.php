<?php

namespace App\Services;

use App\Core\Exceptions\AppException;
use App\Core\Utils\JWTHelper;
use App\Models\Role;
use App\Models\User;

function jsonize($obj)
{
    return json_decode(json_encode($obj));
}

class AuthService
{
    function login($username, $password, $remember = false)
    {
        /** @var User */
        $user = User::findByName($username);

        if ($user == null || $user->removed_at != null) throw new AppException("User not found");
        if (!password_verify($password, $user->password)) throw new AppException("Password is incorrect");

        $user->last_login_at = date('Y-m-d H:i:s');
        $user->edit();

        return JWTHelper::encode([
            "id" => $user->id,
            "username" => $user->name,
            "role" => $user->role,
        ], $remember);
    }

    function roles()
    {
        return Role::all()->orderBy("id", "ASC")->fetch();
    }

    function changePassword($userId, $oldPassword, $newPassword, $newPasswordRepeat)
    {
        if ($newPassword != $newPasswordRepeat) throw new AppException("Passwords don't match");

        /** @var User */
        $user = User::find($userId);

        if ($user == null || $user->removed_at != null) throw new AppException("User not found");
        if (!password_verify($oldPassword, $user->password)) throw new AppException("Old password is incorrect");

        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->edit();
    }
}

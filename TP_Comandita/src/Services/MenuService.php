<?php

namespace App\Services;

use App\Models\Menu;
use App\Core\Exceptions\AppException;
use Slim\Http\UploadedFile;

class MenuService
{
    function list()
    {
        /** @var Menu[] */
        $menu = Menu::whereRemoved_at(null)
            ->orderBy("name")
            ->take(10)
            ->fetch();

        return $menu;
    }

    function read($id)
    {
        /** @var Menu */
        $menu = Menu::findById($id);

        if ($menu == null || $menu->removed_at != null) {
            return null;
        }

        return $menu;
    }

    function create($model, UploadedFile $image = null)
    {
        $menu = new Menu();

        $menu->name = $model->name;
        $menu->description = $model->description;
        $menu->price = $model->price;
        $menu->stock = $model->stock;
        $menu->role = $model->role;

        if (!$menu->create()) throw new AppException("Menu item could not be added, please try again later");

        if ($image != null && ImageHelper::validate($image)) {
            ImageHelper::saveTo("Menu", $image, "$menu->name.png");
        }

        return Menu::findByName($menu->name)->id;
    }

    function update($id, $model, UploadedFile $image = null)
    {
        /** @var Menu */
        $menu = Menu::findById($id);

        if ($menu == null || $menu->removed_at != null) throw new AppException("Menu not found");

        $menu->name = $model->name;
        $menu->description = $model->description;
        $menu->price = $model->price;
        $menu->stock = $model->stock;
        $menu->role = $model->role;
        $menu->updated_at = date('Y-m-d H:i:s');

        if ($image != null && ImageHelper::validate($image)) {
            ImageHelper::saveTo("Menu", $image, "$menu->name.png", true);
        }

        return $menu->edit();
    }
    function delete($id)
    {
        /** @var Menu */
        $menu = Menu::findById($id);

        if ($menu == null || $menu->removed_at != null) throw new AppException("Menu not found");

        return $menu->delete();
    }

    function changeState($id)
    {
        /** @var Menu */
        $menu = Menu::find($id);

        if ($menu == null) throw new AppException("Menu not found");

        if ($menu->removed_at == null) {
            $menu->removed_at = date('Y-m-d H:i:s');
        } else {
            $menu->removed_at = null;
        }

        return $menu->edit();
    }
}

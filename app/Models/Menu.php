<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'menu_id';
    protected $fillable = array('menu_id', 'name');
    public $timestamps = false;

    protected $menu__id = 0;
    protected $name = 0;

    public function menuItems()
    {
        return $this->hasMany('App\Models\MenuItem', 'menu_id', 'menu_id');
    }
}

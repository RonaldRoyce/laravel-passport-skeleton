<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';
    protected $primaryKey = 'menu_item_id';
    protected $fillable = array('menu_item_id', 'menu_item_parent_id', 'menu_item_type', 'level_order', 'page_id', 'div_anchor_name', 'anchor_url', 'menu_item_text', 'image_class');
    public $timestamps = false;

    protected $menu_item_id = 0;
    protected $menu_item_parent_d = 0;
    protected $menu_item_type = '';
    protected $page_id = '';
    protected $level_order = 0;
    protected $div_anchor_name = '';
    protected $anchor_url = '';
    protected $menu_item_text = '';
    protected $image_class = '';

    public function parentItem()
    {
        echo "Getting submenu items\n";
        return $this->hasOne('App\Models\MenuItem', 'menu_item_id', 'menu_item_parent_id');
    }

    public function submenuItems()
    {
        return $this->hasMany('App\Models\MenuItem', 'menu_item_parent_id', 'menu_item_id');
    }
}

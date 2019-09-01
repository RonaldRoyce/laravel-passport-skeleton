<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\DB;
use App\Models\Role\Permission;
use App\Models\Role\RolePermission;
use App\Models\Role\Role;
use App\Models\MenuItem;
use App\User;
use App\Helpers\LdapHelper;
use App\Helpers\ConfigHelper;
use App\Helpers\ModelHelper;
use App\Helpers\PermissionHelper;

class MenuAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a menu item';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $menuItems = MenuItem::whereNull('menu_item_parent_id')->where('menu_item_type', '=', 'G')->orderBy('level_order')->get();

        if (count($menuItems) > 0) {
            if ($this->confirm('Add menu item to existing menu')) {
                $optionNum = 1;
                $choices = array();
                $choiceClasses = array();
                $selectedMenuItem = null;
                $selectedMenuPath = "";

                while (true) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system('cls');
                    } else {
                        system('clear');
                    }
                    if ($selectedMenuItem) {
                        $menuItems = MenuItem::where('menu_item_parent_id', '=', $selectedMenuItem->menu_item_id)
                                             ->where('menu_item_type', '=', 'G')->orderBy('level_order')->get();
                    }

                    if (count($menuItems) == 0) {
                        break;
                    }
                    
                    $choices = array();
                    
                    foreach ($menuItems as $menuItem) {
                        $choices[] = $menuItem->menu_item_text;
                        $choiceClasses[$menuItem->menu_item_text] = $menuItem;
                    }

                    $choices[] = "Add to this menu level";

                    $this->info('Current menu [' . ($selectedMenuItem ? $selectedMenuItem->menu_item_text : '<root>') . ']');

                    $choice = $this->choice('Choose menu to add it to', $choices, "0");

                    if ($choice == "Add to this menu level") {
                        $this->info('Adding to this level');
                        break;
                    } else {
                        $selectedMenuItem = $choiceClasses[$choice];

                        if ($selectedMenuPath != "") {
                            $selectedMenuPath .= "/";
                        }
                        $selectedMenuPath .= $selectedMenuItem->menu_item_text;

                        $selectedMenuItem->load(['submenuItems' => function ($query) {
                            $query->where('menu_item_type', '=', 'G')->orderBy('level_order', 'asc');
                        }]);

                        if (count($menuItem->submenuItems) == 0) {
                            //  No items in menu are submenus.  Add at this menuItem level
                            break;
                        }
                    }
                }

                if ($selectedMenuPath == "") {
                    $selectedMenuPath = '<root>';
                }

                if (!$this->confirm('Add menu item in menu => ' . $selectedMenuPath . " ?")) {
                    return;
                }

                $parentMenuItemId = $selectedMenuItem->menu_item_id;

                $menuItemText = $this->askForRequired('Enter the menu item text');
                $menuItemType = $this->choiceForRequired('Enter the menu item type', array("Menu", "Menu Item"));
                $menuItemAnchorUrl = $this->askForRequired('Enter the anchor url for the menu/menu item');
                $menuItemImageClass = $this->askForRequired('Enter the class for the menu/menu item image');
                $menuItemLevelOrder = $this->askForRequired('Enter the level order for the menu/menu item');
                $menuItemPageId = $this->askForRequired('Enter the page id for the menu/menu item');
 
                $newMenuItem = new MenuItem();

                $newMenuItem->menu_item_text = $menuItemText;
                $newMenuItem->menu_item_parent_id = $parentMenuItemId;
                $newMenuItem->menu_item_type = ($menuItemType == 'Menu' ? "G" : "M");
                $newMenuItem->level_order = $menuItemLevelOrder;
                $newMenuItem->page_id = $menuItemPageId;
                $newMenuItem->div_anchor_name = $menuItemPageId . "-div";
                $newMenuItem->image_class = $menuItemImageClass;

                $newMenuItem->save();
            }
        } else {
            $this->info('No top level menu items.  Adding at top');
        }
    }

    protected function askForRequired($prompt)
    {
        $text = "";

        while ($text == "") {
            $text = trim($this->ask($prompt));
        }

        return $text;
    }
    protected function choiceForRequired($prompt, $choices)
    {
        return $this->choice($prompt, $choices, "1");
    }
}

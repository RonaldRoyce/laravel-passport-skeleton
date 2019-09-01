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

class MenuClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes all menu items from the table';

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
        $this->info('Menu clear');

        MenuItem::truncate();
    }
}

<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal" style="text-align: center;">{{ _('Dashboard') }}</a>
        </div>
        <ul class="nav">
             <?php
             echo \App\Helpers\MenuHelper::render($pageSlug);
             ?>
        </ul>
    </div>
</div>

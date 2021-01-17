<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home">
        <div class="sidebar-brand-icon">
            <img src="assets/img/logo/planod-bolota.png">
        </div>
        <div class="sidebar-brand-text mx-3">PlanoD</div>
    </a>
    <hr class="sidebar-divider my-0">
    <?php
    foreach ($template["menu"] as $menu_item) {
        echo "
        <li class='nav-item" . (strcasecmp($menu_item["url"], $this->controller_name) == 0 ? " active" : "") . "'>
            <a class='nav-link' href='" . BASE_URL . $menu_item["url"] . "'>
                <i class='" . $menu_item["icon"] . "'></i>
                <span>" . $menu_item["name"] . "</span>
            </a>
        </li>";
    }
    ?>
    <hr class="sidebar-divider">
    <div class="version" id="version-ruangadmin"></div>
</ul>
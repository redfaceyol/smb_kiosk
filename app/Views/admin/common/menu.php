<?
$session = \Config\Services::session();
?>
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand title">
            <a href="/admin/dashboard" class="app-brand-link">
              <span class="app-brand-text title menu-text fw-bolder ms-2">SMB KIOSK</span>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item">
              <div class="menu-link" >
                <div data-i18n="<?=$session->member_name?>"><?=$session->member_name?></div>
              </div>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">정보관리</span></li>

            <? if($session->member_grade >= 90) { ?>

            <li class="menu-item">
              <a href="/admin/representative" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Representative">업주 관리</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/shop" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Shop">업장 관리</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Menu">메뉴 관리</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="/admin/menu/categoryList" class="menu-link">
                    <div data-i18n="Vertical Form">카테고리 관리</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="form-layouts-horizontal.html" class="menu-link">
                    <div data-i18n="Horizontal Form">Horizontal Form</div>
                  </a>
                </li>
              </ul>
            </li>
            <li class="menu-item">
              <a href="/admin/kiosk" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Kiosk">KIOSK 관리</div>
              </a>
            </li>

            <? } elseif($session->member_grade >= 50) { ?>

            <li class="menu-item">
              <a href="/admin/shop" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Shop">업장 관리</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/menu" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Menu">메뉴 관리</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/kiosk" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Kiosk">KIOSK 관리</div>
              </a>
            </li>

            <? } ?>
            
            <li class="menu-header small text-uppercase"><span class="menu-header-text">계정</span></li>

            <li class="menu-item">
              <a href="/admin/myaccount" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="MyAccount">내 정보 수정</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/login/Logout" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Logout">로그아웃</div>
              </a>
            </li>

            <? /*
            <li class="menu-header small text-uppercase">
              <span class="menu-header-text">Pages</span>
            </li>        
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Layouts">Form Layouts</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item">
                  <a href="form-layouts-vertical.html" class="menu-link">
                    <div data-i18n="Vertical Form">Vertical Form</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a href="form-layouts-horizontal.html" class="menu-link">
                    <div data-i18n="Horizontal Form">Horizontal Form</div>
                  </a>
                </li>
              </ul>
            </li>
            <!-- Tables -->
            <li class="menu-item">
              <a href="tables-basic.html" class="menu-link">
                <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Tables">Tables</div>
              </a>
            </li>

            */ ?>
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

          <!-- Content wrapper -->
          <div class="content-wrapper">
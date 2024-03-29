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
              <a href="/admin/kiosk" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Kiosk">KIOSK 관리</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/menu" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Menu">메뉴 관리</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/sales" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Sales">매출 현황</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/point" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Point">포인트 관리</div>
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
              <a href="/admin/kiosk" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Kiosk">KIOSK 관리</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/menu" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Menu">메뉴 관리</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="/admin/sales" class="menu-link" >
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Sales">매출 현황</div>
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

            <li class="menu-header small"><span class="menu-header-text">v.0.12 - 2023.06.22</span></li>

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

          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme d-xl-none"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>
            <div class="app-brand title">
              <a href="/admin/dashboard" class="app-brand-link">
                <span class="app-brand-text title menu-text fw-bolder ms-2">SMB KIOSK</span>
              </a>
            </div>

          </nav>

          <!-- / Navbar -->


          <!-- Content wrapper -->
          <div class="content-wrapper">
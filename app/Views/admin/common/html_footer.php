

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-fluid d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , SMB
                </div>
                <div>
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->

        </div>
        <!-- / Layout page -->
        
      </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Toast with Placements -->
    <div
      class="bs-toast toast toast-placement-ex m-2"
      role="alert"
      aria-live="assertive"
      aria-atomic="true"
      data-delay="2000"
    >
      <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <div class="me-auto fw-semibold"></div>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body"></div>
    </div>
    <!-- Toast with Placements -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="/assets/vendors/libs/jquery/jquery.js"></script>
    <script src="/assets/vendors/libs/popper/popper.js"></script>
    <script src="/assets/vendors/js/bootstrap.js"></script>
    <script src="/assets/vendors/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="/assets/vendors/js/menu.js"></script>
    <!-- endbuild -->
    
		<!-- Daum Address -->
		<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

    <!-- bootstrap-treeview -->
    <script src="/assets/plugins/bootstrap-treeview/bootstrap-treeview.min.js"></script>

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="/assets/js/main.js"></script>
    

    <script src="/assets/js/olLib.js"></script>

    <!-- Page JS -->

    <?php
    $session = session();

    if( !empty($session->getFlashdata('message')) ) {
      list($t_class, $t_title, $t_msg) = explode("|", $session->getFlashdata('message'));
    ?>
    <script>
      viewToast("<?=$t_class?>", "<?=$t_title?>", "<?=$t_msg?>");
    </script>
    <?
    }
    ?>
    

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>

<?= session()->getFlashdata('error') ?>
<?= service('validation')->listErrors() ?>

<div class="limiter">
  <div class="container-login100" style="background-image: url('/assets/skin/login1/images/bg-01.jpg');">
    <div class="wrap-login100 p-t-30 p-b-50">
      <span class="login100-form-title p-b-41">
        Account Login<?=($_SERVER["SERVER_ADDR"]=="127.0.0.1"?"<br>from TEST":"")?>
      </span>
      <form class="login100-form validate-form p-b-33 p-t-5" action="/admin/login/prcLogin" method="post">
        <?= csrf_field() ?>

        <div class="wrap-input100 validate-input" data-validate = "Enter userid">
          <input class="input100" type="text" name="userid" placeholder="User id">
          <span class="focus-input100" data-placeholder="&#xe82a;"></span>
        </div>

        <div class="wrap-input100 validate-input" data-validate="Enter password">
          <input class="input100" type="password" name="userpw" placeholder="Password">
          <span class="focus-input100" data-placeholder="&#xe80f;"></span>
        </div>

        <div class="container-login100-form-btn m-t-32">
          <button class="login100-form-btn">
            Login
          </button>
        </div>

      </form>
      
    </div>
  </div>
</div>

<div id="dropDownSelect1"></div>
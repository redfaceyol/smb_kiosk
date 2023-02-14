
  <script src="/assets/plugins/jquery/jquery-3.2.1.min.js"></script>

  <script src="/assets/plugins/animsition/js/animsition.min.js"></script>

	<script src="/assets/plugins/bootstrap/js/popper.js"></script>
	<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

	<script src="/assets/plugins/select2/select2.min.js"></script>

	<script src="/assets/plugins/daterangepicker/moment.min.js"></script>
	<script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>

	<script src="/assets/plugins/countdowntime/countdowntime.js"></script>

  <?php
  if(isset($login_skin_name)) {
  ?>
	<script src="/assets/skin/<?=$login_skin_name?>/js/main.js"></script>
  <?php
  }
  ?>

</body>
</html>
<?
$session = \Config\Services::session();
?>

<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">내 정보 수정</h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <form name="modForm" id="modForm" method="post" action="/admin/myaccount/putMyinfo">
      <input type="hidden" name="oid" value="<?=$myInfo["id"]?>">
      <div class="card-body">                  

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="id">아이디</label>
          <div class="col-sm-10">
            <input type="text" readonly class="form-control-plaintext" id="id" value="<?=$myInfo["id"]?>" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="name">이름</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name" value="<?=$myInfo["name"]?>" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="password">비밀번호</label>
          <div class="col-sm-10">
            <input type="password" class="form-control" id="password" name="password" value="" />
            <div id="passwordHelp" class="form-text text-danger">
              비밀번호 수정시에만 입력해주세요.
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="password2">비밀번호확인</label>
          <div class="col-sm-10">
            <input type="password" class="form-control" id="password2" value="" />
          </div>
        </div>

        <? if($session->member_grade >= 90) { ?>
        <? } elseif($session->member_grade >= 50) { ?>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="phone">전화번호</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="phone" name="phone" value="<?=$myInfo["phone"]?>" placeholder="- 를 제외하고 입력하세요." onkeydown="onlyNumber(this)" />
          </div>
        </div>
        <? } ?>

      </div>
      <div class="card-footer pt-0">
        <a href="javascript:chkForm()" class="btn btn-primary">수정</a>
      </div>
    </form>
  </div>
</div>
<!-- / Content -->
<script>
  function chkForm() {
    if(!$('#name').val()) {
      alert("이름을 입력해주세요.");
      return;
    }

    if($('#password').val()) {
      if(!$('#password2').val()) {
        alert("비밀번호확인을 입력해주세요.");
        return;
      }
      
      if($('#password').val() != $('#password2').val()) {
        alert("비밀번호를 다르게 입력했습니다.");
        return;
      }
    }

    $('#modForm').submit();
  }
</script>
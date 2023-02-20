<?php
$_Link = "";
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">업주 관리</h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <form name="modForm" id="modForm" method="post" action="/admin/representative/putRepresentative">
      <input type="hidden" name="oid" value="<?=$representativeData["id"]?>">
      <input type="hidden" name="cid" value="<?=md5($representativeData["id"])?>">
      <div class="card-body">

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="id">아이디</label>
          <div class="col-sm-3 mb-3 mb-sm-0">
            <input type="text" readonly class="form-control-plaintext" id="id" name="id" value="<?=$representativeData["id"]?>" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="name">이름</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="name" name="name" value="<?=$representativeData["name"]?>" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="password">비밀번호</label>
          <div class="col-sm-3">
            <input type="password" class="form-control" id="password" name="password" value="" />
            <div id="passwordHelp" class="form-text text-danger">
              비밀번호 수정시에만 입력해주세요.
            </div>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="password2">비밀번호확인</label>
          <div class="col-sm-3">
            <input type="password" class="form-control" id="password2" value="" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="phone">전화번호</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="phone" name="phone" value="<?=$representativeData["phone"]?>" placeholder="- 를 제외하고 입력하세요." onkeydown="onlyNumber(this)" />
          </div>
        </div>

      </div>
      <div class="card-footer pt-0">
        <a href="javascript:chkForm()" class="btn btn-primary">수정</a>
        <a href="/admin/representative/representativeList?<?=$_Link?>" class="btn btn-secondary">목록</a>
      </div>
    </form>
  </div>
</div>
<!-- / Content -->
<script>
  function checkID(TypeofUser, getElement, setElement) {
    if(!$('#'+getElement).val()) {
      alert("아이디를 입력해 주세요.");
    }
    else {
      $.ajax({
        type: "POST",
        url: "/admin/representative/checkID",
        data: "TypeofUser="+TypeofUser+
              "&id="+$('#'+getElement).val(),
        success: function(result) {
          eval("var result="+result);

          if(result.status=="OK") {
            $('#idHelp').text("사용이 가능한 아이디입니다.").addClass('text-primary').show();
            $('#'+setElement).val('1');
          }
          else {
            $('#idHelp').text("중복된 아이디가 있습니다.").addClass('text-danger').show();
            $('#'+setElement).val('0');
            $('#'+getElement).val('');
            $('#'+getElement).focus();
          }
        },
        error:function(request, status, error){
          alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
      });
    }
  }

  function chkForm() {
    /*
    if(!$('#id').val()) {
      alert("아이디를 입력해 주세요.");
      return false;
    }

    if($('#id').val() && $('#id_check').val() == "0") {
      alert("아이디 중복검사를 해주세요.");
      return false;
    }
    */

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
        $('#password').val('');
        $('#password2').val('');
        return;
      }
    }
		
    if(!$('#phone').val()) {
      alert("전화번호를 입력해 주세요.");
      return false;
    }
  
    if(!isCellPhone($('#phone').val())) {
      alert("전화번호를 핸드폰번호 양식으로 입력해주세요.");
      return false;
    }



    $('#modForm').submit();
  }
</script>
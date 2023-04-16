<?php
$session = \Config\Services::session();
$_Link = "";
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">업장 관리<button type="button" class="btn btn-primary btn-xs btn-help" data-bs-toggle="modal" data-bs-target="#shopHelp">Help</button></h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <form name="modForm" id="modForm" method="post" action="/admin/shop/putShop" enctype="multipart/form-data">
      <input type="hidden" name="oid" value="<?=$shopData["id"]?>">
      <input type="hidden" name="cid" value="<?=md5($shopData["id"])?>">
      <div class="card-body">

        <!--div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="id">아이디</label>
          <div class="col-sm-3 mb-3 mb-sm-0">
            <input type="text" class="form-control" id="id" name="id" value="" onChange="$('#id_check').val('0');"/>
            <input type="hidden" id="id_check" name="id_check" value="0"/>
            <div id="idHelp" class="form-text display-none"></div>
          </div>
          <div class="col-sm-5">
            <a href="javascript:checkID('shop', 'id', 'id_check')" class="btn btn-secondary">중복확인</a>
          </div>
        </!--div-->

        <? if($session->member_grade >= 90) { ?>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="search_representative">업주명 검색</label>
          <div class="col-sm-2 mb-3 mb-sm-0">
            <input type="text" class="form-control" id="search_representative" name="search_representative" value=""/>
          </div>
          <div class="col-sm-1">
            <a class="btn btn-secondary" href="javascript:findRepresentative()">검색</a>
          </div>
        </div>

        <div class="row mb-0">
          <label class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10" id="searchlist">
          </div>
        </div>
        <? } ?>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="representative_name">업주명</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="representative_name" name="representative_name" value="<?=$shopData["representative_name"]?>" />
            <input type="hidden" class="form-control" id="representative" name="representative" value="<?=$shopData["representative"]?>" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="title">업장명</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="title" name="title" value="<?=$shopData["title"]?>" />
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
          <label class="col-sm-2 col-form-label" for="tel">전화번호</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="tel" name="tel" value="<?=$shopData["tel"]?>" placeholder="- 를 제외하고 입력하세요." onkeydown="onlyNumber(this)" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="zipcode">우편번호</label>
          <div class="col-sm-2 mb-3 mb-sm-0">
            <input type="text" readonly class="form-control" id="zipcode" name="zipcode" value="<?=$shopData["zipcode"]?>"/>
          </div>
          <div class="col-sm-1">
            <a class="btn btn-secondary" href="javascript:findAddress('zipcode', 'address1', 'address2')">주소찾기</a>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="address1">기본주소</label>
          <div class="col-sm-6">
            <input type="text" readonly class="form-control" id="address1" name="address1" value="<?=$shopData["address1"]?>"/>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="address2">상세주소</label>
          <div class="col-sm-6">
            <input type="text" class="form-control" id="address2" name="address2" value="<?=$shopData["address2"]?>"/>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="biznum">사업자등록번호</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="biznum" name="biznum" value="<?=$shopData["biznum"]?>" placeholder="- 를 제외하고 입력하세요." onkeydown="onlyNumber(this)" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="imagefile">업장이미지 (1080 * 1676)</label>
          <div class="col-sm-6">
            <input class="form-control" type="file" id="imagefile" name="imagefile" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="imagefile">등록이미지</label>
          <div class="col-sm-6">
            <? if($shopData["shopimage"]) { ?>
            <img src="/image/shop/<?=$shopData["id"]?>.jpg" height="200">
            <? } ?>
          </div>
        </div>

      </div>
      <div class="card-footer pt-0">
        <a href="javascript:chkForm()" class="btn btn-primary">수정</a>
        <a href="/admin/shop/shopList?<?=$_Link?>" class="btn btn-secondary">목록</a>
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
        url: "/admin/shop/checkID",
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

    if(!$('#representative').val()) {
      alert("업주를 선택해주세요.");
      return;
    }

    if(!$('#title').val()) {
      alert("업장명을 입력해주세요.");
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
		
    if(!$('#tel').val()) {
      alert("전화번호를 입력해 주세요.");
      return false;
    }
		
    if(!$('#zipcode').val()) {
      alert("주소를 입력해 주세요.");
      return false;
    }
		
    if(!$('#biznum').val()) {
      alert("사업자등록번호를 입력해 주세요.");
      return false;
    }
		
    if(!checkCorporateRegistrationNumber($('#biznum').val())) {
      alert("사업자등록번호가 유효하지 않습니다. 확인해주세요.");
      return false;
    }

    $('#modForm').submit();
  }

  function findRepresentative() {
    if(!$('#search_representative').val()) {
      viewAlert("검색할 업주명을 입력해 주세요.");
    }
    else {
      $.ajax({
        type: "POST",
        url: "/admin/shop/findRepresentative",
        data: "searchrepresentative="+$('#search_representative').val(),
        success: function(result) {
          eval("var result="+result);

          if(result.status=="OK") {
            $('#searchlist').html('');

            for(i=0; i<result.list.length; i++) {
              $('#searchlist').append('<a class="btn btn-outline-secondary mb-3" href="javascript:selectRepresentative(\''+result.list[i].id+'\')" style="margin-right: 10px;">'+result.list[i].name+' ( '+result.list[i].id+' )</a>');
            }
          }
          else {
          }
        },
        error:function(request, status, error){
          alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
      });
    }
  }

  function selectRepresentative(selectId) {
    $.ajax({
      type: "POST",
      url: "/admin/shop/selectRepresentative",
        data: "selectrepresentative="+selectId,
      success: function(result) {
        eval("var result="+result);

        if(result.status=="OK") {
          $('#representative_name').val(result.result[0].name);
          $('#representative').val(result.result[0].id);
        }
        else {
        }
      },
      error:function(request, status, error){
        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }
    });
  }
</script>
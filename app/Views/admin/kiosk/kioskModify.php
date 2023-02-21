<?php
$_Link = "";
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">업장 관리</h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <form name="modForm" id="modForm" method="post" action="/admin/kiosk/putKiosk">
      <input type="hidden" name="oid" value="<?=$kioskData["id"]?>">
      <input type="hidden" name="cid" value="<?=md5($kioskData["id"])?>">
      <div class="card-body">

        <!--div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="id">아이디</label>
          <div class="col-sm-3 mb-3 mb-sm-0">
            <input type="text" class="form-control" id="id" name="id" value="" onChange="$('#id_check').val('0');"/>
            <input type="hidden" id="id_check" name="id_check" value="0"/>
            <div id="idHelp" class="form-text display-none"></div>
          </div>
          <div class="col-sm-5">
            <a href="javascript:checkID('kiosk', 'id', 'id_check')" class="btn btn-secondary">중복확인</a>
          </div>
        </!--div-->

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="search_shop">업장 검색</label>
          <div class="col-sm-2 mb-3 mb-sm-0">
            <input type="text" class="form-control" id="search_shop" name="search_shop" value=""/>
          </div>
          <div class="col-sm-1">
            <a class="btn btn-secondary" href="javascript:findShop()">검색</a>
          </div>
        </div>

        <div class="row mb-0">
          <label class="col-sm-2 col-form-label"></label>
          <div class="col-sm-10" id="searchlist">
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="shop_title">업장</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="shop_title" name="shop_title" value="<?=$kioskData["shop_title"]?>" />
            <input type="hidden" class="form-control" id="shop" name="shop" value="<?=$kioskData["shop"]?>" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="kiosktype">KIOSK 타입</label>
          <div class="col-sm-3">
            <select class="form-select" id="kiosktype" name="kiosktype">
              <option value="">선택해주세요.</option>
              <option value="1" <?=($kioskData["types"]=="1"?"selected":"")?>>KIOSK</option>
              <option value="2" <?=($kioskData["types"]=="2"?"selected":"")?>>Table Order</option>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="kiosknumber">KIOSK 번호</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="kiosknumber" name="kiosknumber" value="<?=$kioskData["number"]?>" onkeydown="onlyNumber(this)" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="kiosknumber">이용중인 KIOSK 번호</label>
          <div class="col-sm-10" id="kiosknumberlist">
            <? for($i=0; $i<sizeof($kioskData["kiosk_number"]); $i++) { ?>
            <a class="btn btn-outline-secondary mb-3" href="#" style="margin-right: 10px;"><?=$kioskData["kiosk_number"][$i]->number?></a>
            <? } ?>
          </div>
        </div>

      </div>
      <div class="card-footer pt-0">
        <a href="javascript:chkForm()" class="btn btn-primary">수정</a>
        <a href="/admin/kiosk/kioskList?<?=$_Link?>" class="btn btn-secondary">목록</a>
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
        url: "/admin/kiosk/checkID",
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

    if(!$('#shop').val()) {
      alert("업장을 선택해주세요.");
      return;
    }
		
    if(!$('#kiosktype').val()) {
      alert("KIOSK 타입을 선택해 주세요.");
      return false;
    }
		
    if(!$('#kiosknumber').val()) {
      alert("KIOSK 번호를 선택해 주세요.");
      return false;
    }

    if(kiosknulberlist.findIndex(element => element == $('#kiosknumber').val()) >= 0) {
      alert("중복되지 않은 KIOSK 번호를 입력해주세요.");
      return false;
    }

    $('#modForm').submit();
  }

  function findShop() {
    if(!$('#search_shop').val()) {
      viewAlert("검색할 업장명을 입력해 주세요.");
    }
    else {
      $.ajax({
        type: "POST",
        url: "/admin/kiosk/findShop",
        data: "searchshop="+$('#search_shop').val(),
        success: function(result) {
          eval("var result="+result);

          if(result.status=="OK") {
            $('#searchlist').html('');

            for(i=0; i<result.list.length; i++) {
              $('#searchlist').append('<a class="btn btn-outline-secondary mb-3" href="javascript:selectShop(\''+result.list[i].id+'\')" style="margin-right: 10px;">'+result.list[i].title+' ( '+result.list[i].id+' )</a>');
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

  var kiosknulberlist = [];

  function selectShop(selectId) {
    $.ajax({
      type: "POST",
      url: "/admin/kiosk/selectShop",
        data: "selectshop="+selectId,
      success: function(result) {
        eval("var result="+result);

        if(result.status=="OK") {
          $('#shop_title').val(result.result[0].title);
          $('#shop').val(result.result[0].id);
          $('#kiosknumberlist').html('');
          kiosknulberlist = [];

          for(i=0; i<result.kiosk_number.length; i++) {
            kiosknulberlist.push(result.kiosk_number[i].number);
            $('#kiosknumberlist').append('<a class="btn btn-outline-secondary mb-3" href="#" style="margin-right: 10px;">'+result.kiosk_number[i].number+'</a>');
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
</script>
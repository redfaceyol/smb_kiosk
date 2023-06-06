<?php
$session = \Config\Services::session();

$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->
<style>
  .progress { height: 1.5rem; font-size: 1rem; }
</style>
<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">KIOSK 관리<button type="button" class="btn btn-primary btn-xs btn-help" data-bs-toggle="modal" data-bs-target="#kioskHelp">Help</button></h4>

  
  <div class="row">
    <? if($session->member_grade >= 90) { ?>
    <div class="col-sm-6 row">      
      <label class="col-sm-2 col-form-label" for="shop_title">업장</label>
      <div class="col-sm-6">
        <input type="text" readonly class="form-control" id="shop_title" name="shop_title" value="<?=$kioskDataList["shop_title"]?>" />
      </div>
    </div>
    <div class="col-sm-6 text-end">
      <a class="btn btn-primary" href="/admin/kiosk/postKiosk?sid=<?=$_request->getGet('sid')?>&<?=$_Link?>">신규등록</a>
      <a href="/admin/kiosk/shopList?<?=$_Link?>" class="btn btn-secondary">목록</a>
    </div>
    <? } ?>
  </div>
  <? foreach($kioskDataList["list"] as $kioskData) { ?>
  
  <!-- Basic Bootstrap Table -->
  <div class="card mt-3">
    <form name="modForm" id="modForm" method="post" action="/admin/kiosk/putKiosk">
      <input type="hidden" name="sid" value="<?=$_request->getGet('sid')?>">
      <input type="hidden" name="kid" value="<?=$kioskData["id"]?>">
      <div class="card-body">

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="kiosknumber">KIOSK 번호</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="kiosknumber" name="kiosknumber" value="<?=$kioskData["number"]?>" />
          </div>
          <label class="col-sm-3 col-form-label text-end" for="kioskid">KIOSK ID</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="kioskid" name="kioskid" value="<?=$kioskData["id"]?>" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="kiosknumber">KIOSK 설치 버전</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="kiosknumber" name="kiosknumber" value="<?=$kioskData["kiosk_version"]?>" style="<?=($kioskData["kiosk_version"]!=$kioskData["maxVersion"]?"color:red":"")?>" />
          </div>
          <label class="col-sm-3 col-form-label text-end" for="kiosknumber">전체 최신 버전</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="kiosknumber" name="kiosknumber" value="<?=$kioskData["maxVersion"]?>"  />
          </div>
        </div>

        <?php
        $sizeper = ($kioskData["total_space"]>0 ? round(($kioskData["used_space"]/$kioskData["total_space"])*100) : 0);
        ?>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="kiosknumber">KIOSK HDD 용량</label>
          <div class="col-sm-10">
            <div class="progress mt-2 mb-2">
              <div class="progress-bar <?=($sizeper>75?"bg-danger":"")?>" role="progressbar" style="width: <?=$sizeper?>%">
                <?=$sizeper?>%
              </div>
            </div>
            <div class="row"><div class="col-sm-6 text-start"><?=formatBytes($kioskData["used_space"])?></div><div class="col-sm-6 text-end"><?=formatBytes($kioskData["total_space"])?></div></div>
          </div>
        </div>

        <div class="row mb-0">
          <label class="col-sm-2 col-form-label" for="kiosknumber">마지막 업데이트</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="kiosknumber" name="kiosknumber" value="<?=$kioskData["lastupdate_datetime"]?>" style="<?=(($kioskData["lastupdate_datetime"]?(time() - strtotime($kioskData["lastupdate_datetime"])>(60 * 30)):false)?"color:red":"")?>" />
          </div>
        </div>

      </div>
      <div class="card-footer pt-0 text-end">
        <? if($kioskData["existMenu"] < 1) { ?>
        <a class="btn btn-sm btn-danger" href="javascript:delConfirm('/admin/kiosk/delKiosk?sid=<?=$_request->getGet('sid')?>&oid=<?=$kioskData["id"]?>&cid=<?=md5($kioskData["id"])?>&<?=$_Link?>')"><i class="bx bx-trash-alt me-1"></i> 삭제</a>
        <? } else { ?>
        <a class="btn btn-sm btn-danger" href="javascript:alert('메뉴가 존재하여 삭제가 불가능합니다. 메뉴 먼저 삭제해주세요.')"><i class="bx bx-trash-alt me-1"></i> 삭제</a>
        <? } ?>
      </div>
    </form>
  </div>

  <? } ?>

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
            $('#kiosknumberlist').append('<a class="btn btn-outline-secondary mb-3 disabled" disabled href="#" style="margin-right: 10px;">'+result.kiosk_number[i].number+'</a>');
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
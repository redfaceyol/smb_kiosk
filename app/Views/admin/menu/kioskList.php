<?php
$session = \Config\Services::session();

$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->
<style>
  .progress { height: 1.5rem; font-size: 1rem; }
</style>
<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">메뉴 관리<button type="button" class="btn btn-primary btn-xs btn-help" data-bs-toggle="modal" data-bs-target="#menuHelp">Help</button></h4>
  
  <div class="row">
    <div class="col-sm-6 row">      
      <label class="col-sm-2 col-form-label" for="shop_title">업장</label>
      <div class="col-sm-6">
        <input type="text" readonly class="form-control" id="shop_title" name="shop_title" value="<?=$kioskDataList[0]["shop_title"]?>" />
      </div>
    </div>
    <div class="col-sm-6 text-end">
      <a href="/admin/menu/shopList?<?=$_Link?>" class="btn btn-secondary">목록</a>
    </div>
  </div>

  <? foreach($kioskDataList as $kioskData) { ?>
  
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

        <div class="row mb-0">
          <label class="col-sm-2 col-form-label" for="cntCategory">등록 카테고리 수</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="cntCategory" name="cntCategory" value="<?=$kioskData["cntCategory"]?>" />
          </div>
          <label class="col-sm-3 col-form-label text-end" for="cntMenu">등록 메뉴 수</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="cntMenu" name="cntMenu" value="<?=$kioskData["cntMenu"]?>"  />
          </div>
        </div>

      </div>
      <div class="card-footer pt-0">
        <div class="row">
          <div class="col-sm-6 row">
            <div class="col-sm-4">
              <select id="sourcekiosk_<?=$kioskData["id"]?>" name="sourcekiosk_<?=$kioskData["id"]?>" class="form-select">
                <option value="">선택해주세요</option>
              <? foreach($kioskDataList as $kioskData2) { if($kioskData["id"] != $kioskData2["id"]) { ?>
                <option value="<?=$kioskData2["id"]?>"><?=$kioskData2["number"]?> - <?=$kioskData2["id"]?></option>
              <? } } ?>
              </select>
            </div>
            <a class="btn btn-primary col-sm-3" href="javascript:copyKioskMenu('sourcekiosk_<?=$kioskData["id"]?>', <?=$kioskData["id"]?>)"><i class="bx bx-edit-alt me-1"></i> 메뉴복사</a>
          </div>
          <div class="col-sm-6 text-end">
            <a class="btn btn-info" style="margin-right: 5px;" href="/admin/menu/menuManage?sid=<?=$_request->getGet('sid')?>&kid=<?=$kioskData["id"]?>&<?=$_Link?>"><i class="bx bx-edit-alt me-1"></i> 메뉴관리</a>
            <a class="btn btn-danger" href="javascript:delConfirm('/admin/menu/delAllMenu?sid=<?=$_request->getGet('sid')?>&kid=<?=$kioskData["id"]?>&ckid=<?=md5($kioskData["id"])?>&<?=$_Link?>')"><i class="bx bx-trash-alt me-1"></i> 메뉴삭제</a>
          </div>
        </div>
      </div>
    </form>
  </div>

  <? } ?>
  
  <div class="row mt-3">
    <? if($session->member_grade >= 90) { ?>
    <div class="col-sm-12 row mb-1">      
      <label class="col-sm-2 col-form-label">타매장 메뉴 복사</label>
    </div>
    <div class="col-sm-12 row mb-1">
      <div class="col-sm-3">
        <input type="text" class="form-control" id="search_shop_title" name="search_shop_title" value="" placeholder="매장명검색" />
      </div>
      <a href="javascript:searchShop()" class="btn btn-secondary col-sm-1">검색</a>
    </div>
    <div class="col-sm-12 row">
      <div class="col-sm-3">
        <select id="sourcekiosk" name="sourcekiosk" class="form-select">
          <option value="">검색해주세요</option>
        </select>
      </div>
      <a class="btn btn-primary col-sm-2" href="javascript:copyShopMenu('<?=$kioskData["id"]?>', <?=$_request->getGet('sid')?>)"><i class="bx bx-edit-alt me-1"></i> 메뉴복사</a>
      </div>
    </div>
    <? } ?>
  </div>

</div>
<!-- / Content -->
<script>
  function copyKioskMenu(soucekiosk, targetkiosk) {
    if(!$('#'+soucekiosk).val()) {
      alert("복사를 원하는 키오스크를 선택해주세요.");
    }
    else {
      if(confirm("기존의 모든 메뉴가 삭제된 후 복사됩니다. 진행하시겠습니까?")) {
        location.href="/admin/menu/copyKioskMenu?sid=<?=$_request->getGet('sid')?>&sourcekid="+$('#'+soucekiosk).val()+"&targetkid="+targetkiosk;
      }
    }
  }

  function searchShop() {
    if(!$('#search_shop_title').val()) {
      alert("복사를 원하는 매장명을 입력해주세요.");
    }
    else {
      $.ajax({
        type: "POST",
        url: '/admin/menu/searchShop',
        data: 'searchTitle=' + $('#search_shop_title').val()+
              '&sid=<?=$_request->getGet('sid')?>',
        success: function (result) {console.log(result);
          eval("var result="+result);

          if(result.status=="OK") {
            if(result.list.length > 0) {
              $('#sourcekiosk').html('');
              $('#sourcekiosk').append('<option value="">선택해주세요</option>');

              for(i=0; i<result.list.length; i++) {
                $('#sourcekiosk').append('<option value="'+result.list[i].id+'">'+result.list[i].shop_title+' - '+result.list[i].id+'</option>');
              }
            }
            else {
              alert("검색된 매장이 없습니다.");
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

  function copyShopMenu() {
    if(!$('#sourcekiosk').val()) {
      alert("복사를 원하는 키오스크를 선택해주세요.");
    }
    else {
      if(confirm("기존의 모든 키오스크의 모든 메뉴가 삭제된 후 복사됩니다.\r\n모든 키오스크에 동일하게 복사됩니다.\r\n\r\n진행하시겠습니까?")) {
        location.href="/admin/menu/copyShopMenu?sid=<?=$_request->getGet('sid')?>&sourcekid="+$('#sourcekiosk').val();
      }
    }
  }
</script>
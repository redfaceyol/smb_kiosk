<?php
$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">메뉴 관리</h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <form name="regForm" id="regForm" method="post" action="/admin/menu/postMenu" enctype="multipart/form-data">
      <input type="hidden" name="sid" value="<?=$_request->getGet('sid')?>">
      <input type="hidden" name="depth" value="<?=$_request->getGet('depth')?>">
      <input type="hidden" name="uid" value="<?=$_request->getGet('uid')?>">
      <div class="card-body">

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="title">메뉴명</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="title" name="title" value="" />
          </div>
        </div>

        <? if($_request->getGet('depth') != "1") { ?>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="price">매장가격</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="price" name="price" value="" onkeydown="onlyNumber(this)" />
          </div>
        </div>
        
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="price">포장가격</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="takeoutprice" name="takeoutprice" value="" onkeydown="onlyNumber(this)" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="imagefile">이미지</label>
          <div class="col-sm-6">
            <input class="form-control" type="file" id="imagefile" name="imagefile" />
          </div>
        </div>
        <? } ?>

      </div>
      <div class="card-footer pt-0">
        <a href="javascript:chkForm()" class="btn btn-primary">등록</a>
        <a href="/admin/menu/menuList?sid=<?=$_request->getGet('sid')?>&<?=$_Link?>" class="btn btn-secondary">목록</a>
      </div>
    </form>
  </div>
</div>
<!-- / Content -->
<script>
  function chkForm() {
    if(!$('#title').val()) {
      alert("메뉴명을 입력해주세요.");
      return;
    }

    $('#regForm').submit();
  }
</script>
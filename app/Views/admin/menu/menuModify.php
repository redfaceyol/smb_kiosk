<?php
$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">메뉴 관리</h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <form name="modForm" id="modForm" method="post" action="/admin/menu/putMenu" enctype="multipart/form-data">
      <input type="hidden" name="sid" value="<?=$_request->getGet('sid')?>">
      <input type="hidden" name="oid" value="<?=$menuData["id"]?>">
      <input type="hidden" name="cid" value="<?=md5($menuData["id"])?>">
      <div class="card-body">

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="title">메뉴명</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="title" name="title" value="<?=$menuData["title"]?>" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="price">가격</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="price" name="price" value="<?=$menuData["price"]?>" onkeydown="onlyNumber(this)" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="imagefile">이미지</label>
          <div class="col-sm-6">
            <input class="form-control" type="file" id="imagefile" name="imagefile" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="imagefile">등록이미지</label>
          <div class="col-sm-6">
            <? if($menuData["image"]) { ?>
            <img src="/image/menu/<?=$menuData["id"]?>/<?=$menuData["id"]?>.jpg" height="200">
            <? } ?>
          </div>
        </div>

      </div>
      <div class="card-footer pt-0">
        <a href="javascript:chkForm()" class="btn btn-primary">수정</a>
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

    $('#modForm').submit();
  }
</script>
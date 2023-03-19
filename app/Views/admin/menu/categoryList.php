<?php
$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">카테고리 관리</h4>

  <!-- Basic Bootstrap Table -->
  
  <div class="card">
    <div class="card-header row">
      <div class="col-sm-6 row">
      </div>
      <div class="col-sm-6 text-end">
        <a class="btn btn-primary" href="/admin/menu/categoryRegiste?<?=$_Link?>">카테고리 등록</a>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover table-bordered table-sm">
        <thead>
          <tr>
            <th width="80"></th>
            <th width="300">매장명</th>
            <th width="400">카테고리명</th>
            <th width="100">사용</th>
            <th width="100">메뉴갯수</th>
            <th width="180">관리</th> 
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
					<? $rowNum = 1; foreach($list as $lt) { ?>
          <tr>
            <td></td>
            <td><?=$lt->shop_title?></td>
            <td><?=$lt->title?></td>
            <td><?=($lt->view=="1"?"사용":"미사용")?></td>
            <td><?=$lt->cnt_menu?></td>
            <td>
              <a class="btn btn-sm btn-info" href="/admin/menu/categoryModify?cid=<?=$lt->id?>&ccid=<?=md5($lt->id)?>&<?=$_Link?>"><i class="bx bx-edit-alt me-1"></i> 수정</a>
              <? if($lt->cnt_menu > 0) {?>
              <a class="btn btn-sm btn-danger disabled" href="#" disabled><i class="bx bx-trash-alt me-1"></i> 삭제</a>
              <? } else {?>                
              <a class="btn btn-sm btn-danger" href="javascript:delConfirm('/admin/menu/delCategory?cid=<?=$lt->id?>&ccid=<?=md5($lt->id)?>&<?=$_Link?>')"><i class="bx bx-trash-alt me-1"></i> 삭제</a>
              <? }?>
            </td>
          </tr>
				  <? $rowNum++; } ?>
        </tbody>
      </table>
    </div>    
    <div class="card-footer">
    </div>
  </div>

</div>
<!-- / Content -->

<script>
</script>
<?php
$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">메뉴 관리</h4>

  <!-- Basic Bootstrap Table -->
  
  <div class="card">
    <div class="card-header row">
      <div class="col-sm-6 row">
      </div>
      <div class="col-sm-6 text-end">
        <a class="btn btn-primary" href="/admin/menu/menuRegiste?sid=<?=$_request->getGet('sid')?>&depth=1&uid=0&<?=$_Link?>">1단계 메뉴등록</a>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover table-bordered table-sm">
        <thead>
          <tr>
            <th width="80">No.</th>
            <th width="600">메뉴명</th>
            <th width="100">가격</th>
            <th width="100">이미지</th>
            <th width="180">관리</th> 
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
					<? $rowNum = 1; foreach($list as $lt) { ?>
          <tr>
            <td><?=$rowNum?></td>
            <td><?=$lt->title?></td>
            <td><?=$lt->price?></td>
            <td><img src="/menu/image/<?=$lt->id?>" height="50"></td>
            <td>
              <a class="btn btn-sm btn-info" href="/admin/menu/menuList?id=<?=$lt->id?>&<?=$_Link?>"><i class="bx bx-edit-alt me-1"></i> 메뉴관리</a>
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
<?php
$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">매출 현황<button type="button" class="btn btn-primary btn-xs btn-help" data-bs-toggle="modal" data-bs-target="#salesHelp">Help</button></h4>

  <!-- Basic Bootstrap Table -->
  
  <div class="card">
    <div class="card-header row">
      <div class="col-sm-6 row">
        <div class="col-sm-6">
          <input type="text" class="form-control" id="searchtext" name="searchtext" value="<?=$_request->getGet('searchtext')?>" placeholder="검색어" />
        </div>
        <div class="col-sm-6">
          <a class="btn btn-secondary" href="javascript:searchGo()">검색</a>
        </div>
      </div>
      <div class="col-sm-6 text-end">
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table table-hover table-bordered table-sm">
        <thead>
          <tr>
            <th width="80">No.</th>
            <th width="200">업장명</th>
            <th width="200">아이디</th>
            <th width="100">등록키오스크수</th>
            <th width="100">금일매출</th>
            <th width="180">관리</th> 
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
					<? $rowNum = 0; foreach($list as $lt) { ?>
          <tr>
            <td><?=((($cur_page - 1) * $num_per_page) + $rowNum + 1)?></td>
            <td><?=$lt->title?></td>
            <td><?=$lt->id?></td>
            <td><?=$lt->kioskcnt?></td>
            <td><?=number_format(($lt->totalsales?$lt->totalsales:0))?></td>
            <td>
              <a class="btn btn-sm btn-info" href="/admin/sales/salesDashboard?sid=<?=$lt->id?>&<?=$_Link?>"><i class="bx bx-edit-alt me-1"></i> 매출요약</a>
              <a class="btn btn-sm btn-primary" href="/admin/sales/salesDetail?sid=<?=$lt->id?>&<?=$_Link?>"><i class="bx bx-edit-alt me-1"></i> 매출상세</a>
            </td>
          </tr>
				  <? $rowNum++; } ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      <ul class="pagination justify-content-center mb-0">
        <? if($cur_block == 1) { ?>
        <li class="page-item prev">
          <a class="page-link" href="#"><i class="tf-icon bx bx-chevrons-left"></i></a>
        </li>
        <? } else { ?>
        <li class="page-item prev">
          <a class="page-link" href="?<?=$_Link?>&page=<?=($first_page)?>"><i class="tf-icon bx bx-chevrons-left"></i></a>
        </li>
        <? } ?>
        <? for($i=$first_page+1; $i<=$last_page; $i++) { ?>
          <? if($cur_page == $i) { ?>
        <li class="page-item active">
          <a class="page-link" href="?<?=$_Link?>&page=<?=$i?>"><?=$i?></a>
        </li>
          <? } else { ?>
        <li class="page-item">
          <a class="page-link" href="?<?=$_Link?>&page=<?=$i?>"><?=$i?></a>
        </li>
          <? } ?>
        <? } ?>
        <? if($total_page == $last_page) { ?>
        <li class="page-item next">
          <a class="page-link" href="#"><i class="tf-icon bx bx-chevrons-right"></i></a>
        </li>
        <? } else { ?>
        <li class="page-item next">
          <a class="page-link" href="?<?=$_Link?>&page=<?=($last_page+1)?>"><i class="tf-icon bx bx-chevrons-right"></i></a>
        </li>
        <? } ?>
      </ul>
    </div>
  </div>

</div>
<!-- / Content -->

<script>
  function searchGo() {
    location.href="?searchtext="+$('#searchtext').val()+"&<?=$_Link?>";
  }
</script>
<?php
$getStartDate = ($_request->getGet('startdate')?$_request->getGet('startdate'):date("Y-m-d"));
$getEndDate = ($_request->getGet('enddate')?$_request->getGet('enddate'):date("Y-m-d"));

$_Link = "sid=".$_request->getGet('sid')."&page=".$_request->getGet('page')."&startdate=".$getStartDate."&enddate=".$getEndDate;
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-0">매출 현황<button type="button" class="btn btn-primary btn-xs btn-help" data-bs-toggle="modal" data-bs-target="#salesHelp">Help</button></h4>

  <div class="row">
    <div class="col-sm-6 row">
    </div>
    <div class="col-sm-6 text-end">
      <a href="/admin/sales/ShopList?<?=$_Link?>" class="btn btn-secondary">목록</a>
    </div>
  </div>

  <!-- Basic Bootstrap Table -->
  
  <div class="card mt-3">
    <div class="card-header row">
      <div class="col-sm-6 row">
        <div class="col-sm-6">
        <div class="input-group input-daterange">
          <input type="text" class="form-control" id="startDate" required value="<?=($getStartDate)?>">
          <span class="input-group-text">~</span>
          <input type="text" class="form-control" id="endDate" required value="<?=($getEndDate)?>">
        </div>
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
            <th width="100">kiosk번호</th>
            <th width="200">결제일시</th>
            <th width="100">결제수단</th>
            <th width="300">결제수단정보</th>
            <th width="100">승인번호</th>
            <th width="180">금액</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
					<? $rowNum = 0; foreach($list as $lt) { ?>
          <tr>
            <td><?=($total_count - (($cur_page - 1) * $num_per_page) - $rowNum)?></td>
            <td><?=$lt->number?></td>
            <td><?=$lt->payment_datetime?></td>
            <td><?=$lt->method?></td>
            <td><?=($lt->method=="CARD"?$lt->card_name."/".$lt->card_number:"")?></td>
            <td><?=$lt->authnumber?></td>
            <td><?=number_format(($lt->amount?$lt->amount:0))?></td>
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
  function fncPageReady() {
    $('.input-daterange').datepicker({
      format: "yyyy-mm-dd",
      language: "ko",
      autoclose: true,
    });
  }

  function searchGo() {
    location.href="?<?=$_Link?>&startdate="+$('#startDate').val()+"&enddate="+$('#endDate').val();
  }
</script>
<?php
$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">카테고리 관리</h4>

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <form name="regForm" id="regForm" method="post" action="/admin/menu/postCategory" enctype="multipart/form-data">
      <div class="card-body">

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="search_shop">매장명 검색</label>
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
          <label class="col-sm-2 col-form-label" for="shop_name">매장명</label>
          <div class="col-sm-3">
            <input type="text" readonly class="form-control" id="shop_name" name="shop_name" value="" />
            <input type="hidden" class="form-control" id="shop" name="shop" value="" />
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label" for="title">카테고리명</label>
          <div class="col-sm-3">
            <input type="text" class="form-control" id="title" name="title" value="" />
          </div>
        </div>

      </div>
      <div class="card-footer pt-0">
        <a href="javascript:chkForm()" class="btn btn-primary">등록</a>
        <a href="/admin/menu/categoryList?<?=$_Link?>" class="btn btn-secondary">목록</a>
      </div>
    </form>
  </div>
</div>
<!-- / Content -->
<script>
  function chkForm() {
    if(!$('#shop').val()) {
      alert("매장을 선택해주세요.");
      return;
    }
    
    if(!$('#title').val()) {
      alert("카테고리명을 입력해주세요.");
      return;
    }

    $('#regForm').submit();
    }

  function findShop() {
    if(!$('#search_shop').val()) {
      viewAlert("검색할 매장명을 입력해 주세요.");
    }
    else {
      $.ajax({
        type: "POST",
        url: "/admin/menu/findShop",
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

  function selectShop(selectId) {
    $.ajax({
      type: "POST",
      url: "/admin/shop/selectShop",
        data: "selectshop="+selectId,
      success: function(result) {
        eval("var result="+result);

        if(result.status=="OK") {
          $('#shop_name').val(result.result[0].title);
          $('#shop').val(result.result[0].id);
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
<?php
$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->
<style>
  .settings { display: none; }
</style>

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">메뉴 관리</h4>

  <!-- Basic Bootstrap Table -->
  
  <div class="card">
    <div class="card-header row">
      <div class="col-sm-6 row">
        <h5 class="card-title">
          매장 : test
        </h5>
      </div>
      <div class="col-sm-6 text-end">
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-3">
          <div id="menus"></div>
        </div>
        <div class="col-sm-8 offset-sm-1">

          <div id="category_set" class="settings">
            <form name="categoryForm" id="categoryForm" method="post" action="/admin/menu/prcCategory?<?=$_Link?>" enctype="multipart/form-data">
              <input type="hidden" name="opt" id="opt" value="">
              <input type="hidden" name="shop" id="shop" value="<?=$_request->getGet('sid')?>">
              <input type="hidden" name="cid" id="cid" value="">

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="category_title">카테고리명</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="category_title" name="category_title" value="" />
                </div>
                <a href="javascript:prcCategory()" class="btn btn-primary col-sm-1">저장</a>
              </div>

            </form>
          </div>


          <div id="menu_set" class="settings">
            <form name="menuForm" id="menuForm" method="post" action="/admin/menu/prcMenu?<?=$_Link?>" enctype="multipart/form-data">

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="ctitle">카테고리</label>
                <div class="col-sm-4">
                  <input type="text" readonly class="form-control-plaintext" id="ctitle" name="ctitle" value="" />
                  <input type="hidden" readonly class="form-control-plaintext" id="cid" name="cid" value="" />
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="title">메뉴명</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="title" name="title" value="" />
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="price">매장가격</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="price" name="price" value="" onkeydown="onlyNumber(this)" />
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="shop_view">매장노출</label>
                <div class="col-sm-4">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="" id="shop_view" name="shop_view" checked />
                  </div>
                </div>
              </div>
              
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="price">포장가격</label>
                <div class="col-sm-4">
                  <input type="text" class="form-control" id="takeoutprice" name="takeoutprice" value="" onkeydown="onlyNumber(this)" />
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="takeout_view">포장노출</label>
                <div class="col-sm-4">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="" id="takeout_view" name="takeout_view" checked />
                  </div>
                </div>
              </div>
              
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="description">메뉴간단설명</label>
                <div class="col-sm-10">
                  <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="imagefile">이미지</label>
                <div class="col-sm-10">
                  <input class="form-control" type="file" id="imagefile" name="imagefile" />
                </div>
              </div>
              
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="useoption">옵션사용</label>
                <div class="col-sm-4">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="" id="useoption" name="useoption" checked />
                  </div>
                </div>
              </div>
              
              <div class="row card shadow-none bg-transparent border border-secondary mb-3" id="optionlist">
                <div class="col-sm-12">
                  <div class="row card-body">
                    <div class="col-sm-4">
                      <div id="options"></div>
                    </div>
                    <div class="col-sm-8">

                      <div id="optiongroup_set" class="settings">

                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="optiongroup_title">옵션그룹명</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" id="optiongroup_title" name="optiongroup_title" value="" />
                          </div>
                        </div>
              
                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="optiongroup_choice">선택항목</label>
                          <div class="col-sm-4">
                            <div class="form-check mt-2">
                              <input class="form-check-input" type="checkbox" value="" id="optiongroup_choice" name="optiongroup_choice" />
                            </div>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="optiongroup_maxium">최대입력수량</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="optiongroup_maxium" name="optiongroup_maxium" value="" onkeydown="onlyNumber(this)" />
                          </div>
                        </div>
              
                        <div class="row mb-3">
                          <div class="col-sm-12 text-center">
                            <a href="#" class="btn btn-primary col-sm-2">옵션 저장</a>
                          </div>
                        </div>

                      </div>

                      <div id="option_set" class="settings">

                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="ogtitle">옵션그룹</label>
                          <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="ogtitle" name="ogtitle" value="ㅅㄷㄴㅅ" />
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="option_title">옵션명</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" id="option_title" name="option_title" value="" />
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="option_price">금액</label>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="option_price" name="option_price" value="" onkeydown="onlyNumber(this)" />
                          </div>
                        </div>

                        <div class="row mb-3">
                          <div class="col-sm-12 text-center">
                            <a href="#" class="btn btn-primary col-sm-2">옵션 저장</a>
                          </div>
                        </div>

                      </div>

                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-sm-12 text-center">
                  <a href="#" class="btn btn-primary col-sm-2">메뉴 저장</a>
                </div>
              </div>
            
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>

</div>
<!-- / Content -->

<script>
  function fncPageReady() {
    setMenuTree();

    $('#useoption').on('click', function(e) {
      if($(this).is(':checked')) {
        $('#optionlist').show();
      }
      else {
        $('#optionlist').hide();
      }
    });
  }

  function setMenuTree() {
    $.ajax({
      type: "POST",
      url: '/admin/menu/ajaxGetMenus',
      data: 'sid=<?=$_request->getGet('sid')?>',
      success: function (result) {
				eval("var result="+result);

				if(result.status=="OK") {
          resultVal = JSON.stringify(result.list);
				}
				else {
				}
      },
			error:function(request, status, error){
				alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
			},
      complete: function() {

        $('#menus').treeview({
          data: resultVal,
          levels: 1,
          expandIcon: 'bx bx-plus',
          collapseIcon: 'bx bx-minus',
        });
        
        $('#menus').on('nodeSelected', function(event, node) {      
          $('.settings').hide();

          if(node.tag == "category-add") {
            $('#category_set').show();
          }
          else {
            var tmptag = node.tag.split('|');

            if(tmptag[0] == "category") {
              loadCategory(tmptag[1], 1);
            }
            else if(tmptag[0] == "menu-add") {
              loadCategory(tmptag[1], 2);
              $('#menu_set').show();
            }
          }
        });
      }
    });
  }

  function prcCategory() {
    if(!$('#category_title').val()) {
      alert("카테고리명을 입력해주세요.");
      return;
    }

    $('#categoryForm').submit();
  }

  function loadCategory(inCid, inType) {
    $.ajax({
      type: "POST",
      url: '/admin/menu/ajaxLoadCategory',
      data: 'sid=<?=$_request->getGet('sid')?>'+
            '&cid=' + inCid,
      success: function (result) {
        eval("var result="+result);

        if(result.status=="OK") {
          if(inType == "1") {
            $('#categoryForm #opt').val('u');
            $('#categoryForm #cid').val(inCid);
            $('#category_title').val(result.data.title);

            if(result.data.menu_cnt<1) {
              $('.btn-danger').remove();
              $('#categoryForm .btn-primary').after('<a href="javascript:delConfirm(\'/admin/menu/delCategory?sid=<?=$_request->getGet('sid')?>&cid='+inCid+'&ccid='+result.data.ccid+'&\')" class="btn btn-danger col-sm-1 offset-sm-1">삭제</a>');
            }

            $('#category_set').show();
          }
          else if(inType == "2") {
            $('#menuForm #cid').val(inCid);
            $('#menuForm #ctitle').val(result.data.title);
          }
        }
      },
      error:function(request, status, error){
        alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
      }
    });
  }
</script>

<?php
$_Link = "page=".$_request->getGet('page');
?>
<!-- Content -->
<style>
  .settings { display: none; }
  .add_btn { margin-left: 10px; }
  .add_btn a:hover { color: #ffffff; }
</style>

<div class="container-fluid flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4">메뉴 관리<button type="button" class="btn btn-primary btn-xs btn-help" data-bs-toggle="modal" data-bs-target="#menuHelp">Help</button></h4>

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
              <input type="hidden" name="sid" id="sid" value="<?=$_request->getGet('sid')?>">
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
              <input type="hidden" name="opt" id="opt" value="">
              <input type="hidden" name="sid" id="sid" value="<?=$_request->getGet('sid')?>">
              <input type="hidden" name="mid" id="mid" value="">
              <input type="hidden" name="oldcid" id="oldcid" value="">

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="cid">카테고리</label>
                <div class="col-sm-4">
                  <select id="cid" name="cid" class="form-select">
                  </select>
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
                    <input class="form-check-input" type="checkbox" value="1" id="shop_view" name="shop_view" checked />
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
                    <input class="form-check-input" type="checkbox" value="1" id="takeout_view" name="takeout_view" checked />
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="soldout">품절</label>
                <div class="col-sm-4">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="1" id="soldout" name="soldout" />
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

              <div class="row mb-3" id="registeImg">
                <label class="col-sm-2 col-form-label" for="imagefile">등록이미지</label>
                <div class="col-sm-3">
                  <img src="" height="200">
                </div>
                <div class="row col-sm-6">
                  <label class="col-sm-3 col-form-label" for="delimage">이미지삭제</label>
                  <div class="col-sm-4">
                    <div class="form-check mt-2">
                      <input class="form-check-input" type="checkbox" value="1" id="delimage" name="delimage" />
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row mb-3">
                <label class="col-sm-2 col-form-label" for="use_option">옵션사용</label>
                <div class="col-sm-4">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="1" id="use_option" name="use_option" checked />
                  </div>
                </div>
              </div>
              
              <div class="row card shadow-none bg-transparent border border-secondary mb-3" id="optionlist">
                <div class="col-sm-12">
                  <div class="row card-body">
                    <div id="base_ment">
                      * 옵션 항목은 메뉴 저장 후 입력해주세요.
                    </div>
                    <div class="col-sm-4">
                      <div id="options"></div>
                    </div>
                    <div class="col-sm-8">

                      <div id="optiongroup_set" class="settings optionsettings">
                        <input type="hidden" name="opt" id="opt" value="">
                        <input type="hidden" name="sid" id="sid" value="<?=$_request->getGet('sid')?>">
                        <input type="hidden" name="mid" id="mid" value="">
                        <input type="hidden" name="ogid" id="ogid" value="">

                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="optiongroup_title">옵션그룹명</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control" id="optiongroup_title" name="optiongroup_title" value="" />
                          </div>
                        </div>
              
                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="optiongroup_choice">필수항목</label>
                          <div class="col-sm-4">
                            <div class="form-check mt-2">
                              <input class="form-check-input" type="checkbox" value="1" id="optiongroup_choice" name="optiongroup_choice" />
                            </div>
                          </div>
                        </div>

                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="optiongroup_maxium">최대입력수량</label>
                          <div class="col-sm-6">
                            <input type="text" class="form-control" id="optiongroup_maxium" name="optiongroup_maxium" value="0" onkeydown="onlyNumber(this)" />
                            <br>* 없을경우 0으로 입력해주세요. 필수항목에만 적용됩니다.
                          </div>
                        </div>
              
                        <div class="row mb-3">
                          <div class="col-sm-12 text-center">
                            <a href="javascript:prcOptiongroup()" class="btn btn-primary col-sm-2">옵션 저장</a>
                          </div>
                        </div>

                      </div>

                      <div id="option_set" class="settings optionsettings">
                        <input type="hidden" name="opt" id="opt" value="">
                        <input type="hidden" name="sid" id="sid" value="<?=$_request->getGet('sid')?>">
                        <input type="hidden" name="mid" id="mid" value="">
                        <input type="hidden" name="oid" id="oid" value="">

                        <div class="row mb-3">
                          <label class="col-sm-3 col-form-label" for="ogtitle">옵션그룹</label>
                          <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" id="ogtitle" name="ogtitle" value="" />
                            <input type="hidden" readonly class="form-control-plaintext" id="ogid" name="ogid" value="" />
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
                            <input type="text" class="form-control" id="option_price" name="option_price" value="0" onkeydown="onlyNumber(this)" />
                          </div>
                        </div>

                        <div class="row mb-3">
                          <div class="col-sm-12 text-center">
                            <a href="javascript:prcOption()" class="btn btn-primary col-sm-2">옵션 저장</a>
                          </div>
                        </div>

                      </div>

                    </div>
                  </div>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-sm-12 text-center">
                  <a href="javascript:prcMenu()" class="btn btn-primary col-sm-2">메뉴 저장</a>
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

  $('#use_option').on('click', function(e) {
    if($(this).is(':checked')) {
      $('#optionlist').show();
    }
    else {
      $('#optionlist').hide();
    }
  });
}

var selectMenuNode;
var selectOptionNode;

function setMenuTree(selectnode) {
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
        highlightSearchResults: false,
      });
      
      $('#menus').on('nodeSelected', function(event, node) {
        selectMenuNode = node;
        $('.settings').hide();

        if(node.tag == "category-add") {
          $('#categoryForm #opt').val('');
          $('#categoryForm #cid').val('');
          $('#categoryForm #category_title').val('');
          $('#categoryForm .btn-danger').remove();

          $('#category_set').show();
        }
        else {
          var tmptag = node.tag.split('|');

          if(tmptag[0] == "category") {
            loadCategory(tmptag[1]);
          }
          else if(tmptag[0] == "menu-add") {
            loadCategoryList(tmptag[1]);

            $('#menuForm #opt').val('');
            $('#menuForm #mid').val('');
            $('#menuForm #title').val('');
            $('#menuForm #price').val('');
            $('#menuForm #takeoutprice').val('');
            $('#menuForm #description').val('');

            $('#menuForm #shop_view').prop("checked", true);
            $('#menuForm #takeout_view').prop("checked", true);
            $('#menuForm #use_option').prop("checked", true);

            $('#optionlist').show();
            $('#optionlist #base_ment').show();
            $('#options').treeview('remove');
            $('#registeImg').hide();

            $('#menu_set').show();
          }
          else if(tmptag[0] == "menu") {
            loadMenu(tmptag[1]);
          }
        }
      });

      if(selectnode) {
        /*
        $('#menus').treeview('expandNode', $('#menus').treeview('getParent', selectnode));
        $('#menus').treeview('selectNode', selectnode.nodeId);
        */        
        var searchnode = $('#menus').treeview('search',[selectnode.text, {ignoreCase: true, exactMatch: true, revealResults: true}]);
        
        for (var i = 0; i <searchnode.length; i++) {
          if(selectnode.tag == searchnode[i].tag) {
            $('#menus').treeview('selectNode', searchnode[i].nodeId);
          }
        };
      }
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

function loadCategory(inCid) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/ajaxLoadCategory',
    data: 'sid=<?=$_request->getGet('sid')?>'+
          '&cid=' + inCid,
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        $('#categoryForm #opt').val('u');
        $('#categoryForm #cid').val(inCid);
        $('#categoryForm #category_title').val(result.data.title);

        $('#categoryForm .add_btn').remove();
        if(result.data.menu_cnt<1) {
          $('#categoryForm .btn-primary:last').after('<a href="javascript:delConfirm(\'/admin/menu/delCategory?sid=<?=$_request->getGet('sid')?>&cid='+inCid+'&ccid='+result.data.ccid+'&\')" class="btn btn-danger col-sm-1 offset-sm-1 add_btn">삭제</a>');
        }
        $('#categoryForm .btn-primary:last').after('<div class="btn-group col-sm-2 add_btn" role="group" aria-label="Basic outlined example"><a class="btn btn-outline-primary" href="javascript:reordercategory('+inCid+', 1);" onfocus="blur">↑</a><a class="btn btn-outline-primary" href="javascript:reordercategory('+inCid+', 2);" onfocus="blur">↓</a></div>');

        $('#category_set').show();
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    }
  });
}

function reordercategory(incid, inset) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/prcReOrderCategory',
    data: 'sid=<?=$_request->getGet('sid')?>' +
          '&cid=' + incid +
          '&set=' + inset,
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        setMenuTree(selectMenuNode);
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    },
    complete: function() {
    }
  });
}

function loadCategoryList(inCid) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/ajaxLoadCategoryList',
    data: 'sid=<?=$_request->getGet('sid')?>',
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        $('#menuForm #cid').html('');

        for(i=0; i<result.categoryList.length; i++) {
          $('#menuForm #cid').append('<option value="'+result.categoryList[i].id+'">'+result.categoryList[i].title+'</option>');
        }

        $('#menuForm #cid').val(inCid);
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    }
  });
}

function prcMenu() {
  if(!$('#title').val()) {
    alert("메뉴명을 입력해주세요.");
    return;
  }

  $('#menuForm').submit();
}

function loadMenu(inMid) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/ajaxLoadMenu',
    data: 'sid=<?=$_request->getGet('sid')?>'+
          '&mid=' + inMid,
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {console.log(result.data);
        loadCategoryList(result.data.category_id);

        $('#menuForm #oldcid').val(result.data.category_id);
        $('#menuForm #opt').val('u');
        $('#menuForm #mid').val(inMid);
        //$('#menuForm #ctitle').val(result.data.category_title);
        //$('#menuForm #cid').val(result.data.category_id);
        $('#menuForm #title').val(result.data.title);
        $('#menuForm #price').val(result.data.price);
        $('#menuForm #takeoutprice').val(result.data.takeoutprice);
        $('#menuForm #description').val(result.data.description);

        if(result.data.shopview == "1") {
          $('#menuForm #shop_view').prop("checked", true);
        }
        else {
          $('#menuForm #shop_view').prop("checked", false);
        }

        if(result.data.takeoutview == "1") {
          $('#menuForm #takeout_view').prop("checked", true);
        }
        else {
          $('#menuForm #takeout_view').prop("checked", false);
        }

        if(result.data.soldout == "1") {
          $('#menuForm #soldout').prop("checked", true);
        }
        else {
          $('#menuForm #soldout').prop("checked", false);
        }

        if(result.data.useoption == "1") {
          $('#menuForm #use_option').prop("checked", true);
          $('#optionlist').show();
        }
        else {
          $('#menuForm #use_option').prop("checked", false);
          $('#optionlist').hide();
        }

        $('#optionlist #base_ment').hide();
        setOptionTree(inMid);

        if(result.data.imgpath) {
          $('#registeImg').show();
          $('#registeImg img').attr('src', result.data.imgpath);
        }
        else {
          $('#registeImg').hide();
        }

        $('#menuForm .add_btn').remove();
        $('#menuForm .btn-primary:last').after('<a href="javascript:delConfirm(\'/admin/menu/delMenu?sid=<?=$_request->getGet('sid')?>&mid='+inMid+'&cmid='+result.data.cmid+'&\')" class="btn btn-danger col-sm-1 add_btn">삭제</a>');
        $('#menuForm .btn-primary:last').after('<a href="javascript:copyMenu(\'/admin/menu/copyMenu?sid=<?=$_request->getGet('sid')?>&mid='+inMid+'&cmid='+result.data.cmid+'&cid='+result.data.category_id+'\')" class="btn btn-info col-sm-1 add_btn">복사</a>');
        $('#menuForm .btn-primary:last').after('<div class="btn-group add_btn" role="group" aria-label="Basic outlined example"><a class="btn btn-outline-primary" href="javascript:reordermenu('+result.data.category_id+', '+inMid+', 1);" onfocus="blur">↑</a><a class="btn btn-outline-primary" href="javascript:reordermenu('+result.data.category_id+', '+inMid+', 2);" onfocus="blur">↓</a></div>');

        $('#menu_set').show();
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    }
  });
}

function reordermenu(incid, inmid, inset) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/prcReOrderMenu',
    data: 'sid=<?=$_request->getGet('sid')?>' +
          '&cid=' + incid +
          '&mid=' + inmid +
          '&set=' + inset,
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        setMenuTree(selectMenuNode);
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    },
    complete: function() {
    }
  });
}

function copyMenu(url) {
  location.href=url;
}

function setOptionTree(mid, selectnode) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/ajaxGetOptions',
    data: 'sid=<?=$_request->getGet('sid')?>'+
          '&mid=' + mid,
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

      $('#options').treeview({
        data: resultVal,
        levels: 1,
        expandIcon: 'bx bx-plus',
        collapseIcon: 'bx bx-minus',
        highlightSearchResults: false,
      });
      
      $('#options').on('nodeSelected', function(event, node) {    
        selectOptionNode = node;  
        $('.optionsettings').hide();

        if(node.tag == "optiongroup-add") {
          $('#optiongroup_set #opt').val('');
          $('#optiongroup_set #mid').val(mid);
          $('#optiongroup_set #ogid').val('');
          $('#optiongroup_set #optiongroup_title').val('');
          $('#optiongroup_set #optiongroup_maxium').val('0');
          $('#optiongroup_set .btn-danger').remove();

          $('#optiongroup_set #optiongroup_choice').prop("checked", true);

          $('#optiongroup_set').show();
        }
        else {
          var tmptag = node.tag.split('|');

          if(tmptag[0] == "optiongroup") {
            loadOptiongroup(tmptag[1], 1);
          }
          else if(tmptag[0] == "option-add") {
            loadOptiongroup(tmptag[1], 2);

            $('#option_set #opt').val('');
            $('#option_set #mid').val(mid);
            $('#option_set #oid').val('');
            $('#option_set #option_title').val('');
            $('#option_set #option_price').val('0');

            $('#option_set').show();
          }
          else if(tmptag[0] == "option") {
            loadOption(tmptag[1]);
          }
        }
      });

      if(selectnode) {
        var searchnode = $('#options').treeview('search', [selectnode.text, {ignoreCase: true, exactMatch: true, revealResults: true}]);
        
        for (var i = 0; i <searchnode.length; i++) {
          if(selectnode.tag == searchnode[i].tag) {
            $('#options').treeview('selectNode', searchnode[i].nodeId);
          }
        };
      }
    }
  });
}

function prcOptiongroup() {
  if(!$('#optiongroup_title').val()) {
    alert("옵션그룹명을 입력해주세요.");
    return;
  }
  
  $.ajax({
    type: "POST",
    url: '/admin/menu/prcOptiongroup',
    data: 'opt=' + $('#optiongroup_set #opt').val() +
          '&sid=' + $('#optiongroup_set #sid').val() +
          '&mid=' + $('#optiongroup_set #mid').val() +
          '&ogid=' + $('#optiongroup_set #ogid').val() +
          '&optiongroup_title=' + $('#optiongroup_set #optiongroup_title').val() +
          '&optiongroup_choice=' + ($('#optiongroup_set #optiongroup_choice').is(":checked")?"1":"") +
          '&optiongroup_maxium=' + $('#optiongroup_set #optiongroup_maxium').val(),
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        $('.optionsettings').hide();
        setOptionTree($('#optiongroup_set #mid').val());
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    },
    complete: function() {
    }
  });
}

function loadOptiongroup(inOgid, inType) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/ajaxLoadOptiongroup',
    data: 'sid=<?=$_request->getGet('sid')?>'+
          '&ogid=' + inOgid,
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        if(inType == "1") {
          $('#optiongroup_set #opt').val('u');
          $('#optiongroup_set #mid').val(result.data.menu);
          $('#optiongroup_set #ogid').val(inOgid);
          $('#optiongroup_set #optiongroup_title').val(result.data.title);
          $('#optiongroup_set #optiongroup_maxium').val(result.data.maxium);

          if(result.data.choice == "1") {
            $('#optiongroup_set #optiongroup_choice').prop("checked", true);
          }
          else {
            $('#optiongroup_set #optiongroup_choice').prop("checked", false);
          }

          $('#optiongroup_set .add_btn').remove();
          if(result.data.option_cnt<1) {            
            $('#optiongroup_set .btn-primary:last').after('<a href="javascript:delOptiongroup(\''+inOgid+'\')" class="btn btn-danger col-sm-2 add_btn">삭제</a>');
          }
          $('#optiongroup_set .btn-primary:last').after('<div class="btn-group add_btn" role="group" aria-label="Basic outlined example"><a class="btn btn-outline-primary" href="javascript:reorderoptiongroup('+result.data.menu+', '+inOgid+', 1);" onfocus="blur">↑</a><a class="btn btn-outline-primary" href="javascript:reorderoptiongroup('+result.data.menu+', '+inOgid+', 2);" onfocus="blur">↓</a></div>');

          $('#optiongroup_set').show();
        }
        else if(inType == "2") {
          $('#option_set #ogid').val(inOgid);
          $('#option_set #ogtitle').val(result.data.title);
        }
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    }
  });
}

function reorderoptiongroup(inmid, inogid, inset) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/prcReOrderOptiongroup',
    data: 'sid=<?=$_request->getGet('sid')?>' +
          '&mid=' + inmid +
          '&ogid=' + inogid +
          '&set=' + inset,
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        setOptionTree(inmid, selectOptionNode);
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    },
    complete: function() {
    }
  });
}

function delOptiongroup(inOgid) {
  if(confirm("정말 삭제하시겠습니까?")) {
    $('#optiongroup_set #opt').val('d');
    prcOptiongroup();
  }
}

function prcOption() {
  if(!$('#option_title').val()) {
    alert("옵션명을 입력해주세요.");
    return;
  }

  if(!$('#option_price').val()) {
    alert("금액을 입력해주세요.");
    return;
  }
  
  $.ajax({
    type: "POST",
    url: '/admin/menu/prcOption',
    data: 'opt=' + $('#option_set #opt').val() +
          '&sid=' + $('#option_set #sid').val() +
          '&mid=' + $('#option_set #mid').val() +
          '&oid=' + $('#option_set #oid').val() +
          '&ogid=' + $('#option_set #ogid').val() +
          '&option_title=' + $('#option_set #option_title').val() +
          '&option_price=' + $('#option_set #option_price').val(),
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        $('.optionsettings').hide();
        setOptionTree($('#option_set #mid').val());
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    },
    complete: function() {
    }
  });
}

function loadOption(inOid) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/ajaxLoadOption',
    data: 'sid=<?=$_request->getGet('sid')?>'+
          '&oid=' + inOid,
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        $('#option_set #opt').val('u');
        $('#option_set #oid').val(inOid);
        $('#option_set #ogtitle').val(result.data.optiongroup_title);
        $('#option_set #ogid').val(result.data.optiongroup_id);
        $('#option_set #option_title').val(result.data.title);
        $('#option_set #option_price').val(result.data.price);
                
        $('#option_set .add_btn').remove();
        $('#option_set .btn-primary:last').after('<a href="javascript:delOption(\''+inOid+'\')" class="btn btn-danger col-sm-2 add_btn">삭제</a>');
        $('#option_set .btn-primary:last').after('<div class="btn-group add_btn" role="group" aria-label="Basic outlined example"><a class="btn btn-outline-primary" href="javascript:reorderoption('+result.data.optiongroup_id+', '+inOid+', 1);" onfocus="blur">↑</a><a class="btn btn-outline-primary" href="javascript:reorderoption('+result.data.optiongroup_id+', '+inOid+', 2);" onfocus="blur">↓</a></div>');

        $('#option_set').show();
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    }
  });
}

function reorderoption(inogid, inoid, inset) {
  $.ajax({
    type: "POST",
    url: '/admin/menu/prcReOrderOption',
    data: 'sid=<?=$_request->getGet('sid')?>' +
          '&ogid=' + inogid +
          '&oid=' + inoid +
          '&set=' + inset,
    success: function (result) {
      eval("var result="+result);

      if(result.status=="OK") {
        setOptionTree($('#option_set #mid').val(), selectOptionNode);
      }
    },
    error:function(request, status, error){
      alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
    },
    complete: function() {
    }
  });
}

function delOption(inOid) {
  if(confirm("정말 삭제하시겠습니까?")) {
    $('#option_set #opt').val('d');
    prcOption();
  }
}
</script>

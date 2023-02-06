<!doctype html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KIOSK API</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
      html {
        font-family: "굴림";
      }

      td {
        font-size: 12px;
        line-height: 140%;
      }
    </style>

  </head>

  <body>
  <br>
  <div class="container">
    <div class="accordion" id="accordionExample">
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading1">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
            수정이력
          </button>
        </h2>
        <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading1" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            
            <table class="table table-bordered" width="100%">
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  2023-02-06
                </td>
                <td>
                  최초 작성
                </td>
              </tr>
            </table>

          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="heading2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
            매장 API
          </button>
        </h2>
        <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading2" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            
            <table class="table table-bordered" width="100%">
              <tr>
                <th colspan="2">매장 로그인 <small>(last update : 2023-02-06)</small></th>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  URL
                </td>
                <td>
                  /api/shop/shop_login
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Method
                </td>
                <td>
                  post
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Data
                </td>
                <td>
                  id : 매장아이디
                  <br>
                  password : 패스워드
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Success Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;100 : 요청 성공
                  <br><br>
                  [&nbsp;Content&nbsp;]&nbsp;{"id":"매장아이디", "title":"매장명"}
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Error Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;500 : 입력된 매장아이디 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;501 : 입력된 패스워드 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;510 : 등록되지 않은 매장아이디
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;511 : 일치하지 않은 패스워드
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;599 : 기타
                </td>
              </tr>
            </table>
            
            <table class="table table-bordered" width="100%">
              <tr>
                <th colspan="2">매장 정보 <small>(last update : 2023-02-06)</small></th>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  URL
                </td>
                <td>
                  /api/shop/shop_info
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Method
                </td>
                <td>
                  get
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Data
                </td>
                <td>
                  id : 매장아이디
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Success Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;100 : 요청 성공
                  <br><br>
                  [&nbsp;Content&nbsp;]&nbsp;{"id":"매장아이디", "title":"매장명", "representative":"대표자아이디", "tel":"전화번호", "zipcode":"우편번호", "address1":"기본주소", "address2":"상세주소", "representativename":"대표자이름"}
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Error Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;500 : 입력된 매장아이디 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;510 : 등록되지 않은 아이디
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;599 : 기타
                </td>
              </tr>
            </table>

          </div>
        </div>
      </div>
      
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading3">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
            키오스크 API (테이블오더 포함)
          </button>
        </h2>
        <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading3" data-bs-parent="#accordionExample">
          <div class="accordion-body">
            
            <table class="table table-bordered" width="100%">
              <tr>
                <th colspan="2">키오스크 로그인 <small>(last update : 2023-02-06)</small></th>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  URL
                </td>
                <td>
                  /api/kiosk/kiosk_login
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Method
                </td>
                <td>
                  post
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Data
                </td>
                <td>
                  id : 키오스크아이디
                  <br>
                  password : 패스워드
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Success Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;100 : 요청 성공
                  <br><br>
                  [&nbsp;Content&nbsp;]&nbsp;{"id":"키오스크아이디", "kiosk_type":"장비타입", "shop_id":"매장아이디", "shop_title":"매장명"}
                  <br><br>
                  [&nbsp;Discription&nbsp;]&nbsp;kiosk_type : 1 - 키오스크, 2 - 테이블오더
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Error Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;500 : 입력된 키오스크아이디 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;501 : 입력된 패스워드 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;510 : 등록되지 않은 키오스크아이디
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;511 : 일치하지 않은 패스워드
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;599 : 기타
                </td>
              </tr>
            </table>
            
            <table class="table table-bordered" width="100%">
              <tr>
                <th colspan="2">1차메뉴 정보 <small>(last update : 2023-02-06)</small></th>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  URL
                </td>
                <td>
                  /api/kiosk/menu1
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Method
                </td>
                <td>
                  get
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Data
                </td>
                <td>
                  id : 매장아이디
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Success Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;100 : 요청 성공
                  <br><br>
                  [&nbsp;Content&nbsp;]&nbsp;{"menulist":[{"menuid":"메뉴아이디", "menutitle":"메뉴명", "nextmenu":"하위메뉴여부", "price":"가격", "imagename":"이미지명", "imagepath":"전체이미지경로"},...]}
                  <br><br>
                  [&nbsp;Discription&nbsp;]&nbsp;nextmenu : true / false
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Error Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;500 : 입력된 매장아이디 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;510 : 등록되지 않은 매장아이디
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;599 : 기타
                </td>
              </tr>
            </table>
            
            <table class="table table-bordered" width="100%">
              <tr>
                <th colspan="2">2차메뉴 정보 <small>(last update : 2023-02-06)</small></th>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  URL
                </td>
                <td>
                  /api/kiosk/menu2
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Method
                </td>
                <td>
                  get
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Data
                </td>
                <td>
                  id : 매장아이디
                  <br>
                  menu1 : 1단계 메뉴 아이디
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Success Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;100 : 요청 성공
                  <br><br>
                  [&nbsp;Content&nbsp;]&nbsp;{"menulist":[{"menuid":"메뉴아이디", "menutitle":"메뉴명", "nextmenu":"하위메뉴여부", "price":"가격", "imagename":"이미지명", "imagepath":"전체이미지경로"},...]}
                  <br><br>
                  [&nbsp;Discription&nbsp;]&nbsp;nextmenu : true / false
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Error Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;500 : 입력된 매장아이디 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;501 : 입력된 1단계 메뉴아이디 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;510 : 등록되지 않은 매장아이디
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;511 : 등록되지 않은 1단계 메뉴아이디
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;599 : 기타
                </td>
              </tr>
            </table>
            
            <table class="table table-bordered" width="100%">
              <tr>
                <th colspan="2">3차메뉴 정보 <small>(last update : 2023-02-06)</small></th>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  URL
                </td>
                <td>
                  /api/kiosk/menu3
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Method
                </td>
                <td>
                  get
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Data
                </td>
                <td>
                  id : 매장아이디
                  <br>
                  menu2 : 2단계 메뉴 아이디
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Success Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;100 : 요청 성공
                  <br><br>
                  [&nbsp;Content&nbsp;]&nbsp;{"menulist":[{"menuid":"메뉴아이디", "menutitle":"메뉴명", "price":"가격", "imagename":"이미지명", "imagepath":"전체이미지경로"},...]}
                </td>
              </tr>
              <tr>
                <td style="width: 150px; background: #fafafa;">
                  Error Response
                </td>
                <td>
                  [&nbsp;Code&nbsp;]&nbsp;500 : 입력된 매장아이디 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;501 : 입력된 2단계 메뉴아이디 없음
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;510 : 등록되지 않은 매장아이디
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;511 : 등록되지 않은 2단계 메뉴아이디
                  <br>
                  [&nbsp;Code&nbsp;]&nbsp;599 : 기타
                </td>
              </tr>
            </table>

          </div>
        </div>
      </div>
      
      <div class="accordion-item">
        <h2 class="accordion-header" id="heading4">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
            주문 API
          </button>
        </h2>
        <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading4" data-bs-parent="#accordionExample">
          <div class="accordion-body">

          </div>
        </div>
      </div>

    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  </body>
</html>


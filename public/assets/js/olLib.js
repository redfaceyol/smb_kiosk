function delConfirm(redirectUrl)
{
	if(confirm("정말 삭제하시겠습니까?")) {
		location.href=redirectUrl;
	}
}

function pad(n, width) {
	n = n + '';
	return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}

function onlyNumber(obj) {
	$(obj).keyup(function(){
		$(this).val($(this).val().replace(/[^0-9]/g,""));
	}); 
}

function onlyNumber2(obj) {
	$(obj).keyup(function(){
		$(this).val($(this).val().replace(/[^0-9\-]/g,""));
	}); 
}

function isCellPhone(p) {
	p = p.split('-').join('');
	var regPhone = /^((01[1|6|7|8|9])[1-9]+[0-9]{6,7})|(010[1-9][0-9]{7})$/;
	return regPhone.test(p);
}

function viewToast(cls, title, msg) {
  trgToast = $('.toast-placement-ex');
  trgToast.addClass('bg-'+cls);
  trgToast.addClass('top-0');
  trgToast.addClass('end-0');
  $('.toast-placement-ex .toast-header div').text(title);
  $('.toast-placement-ex .toast-body').text(msg);
  toastPlacement = new bootstrap.Toast(trgToast);
  toastPlacement.show();
}

function findAddress(zipcode, address1, address2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(zipcode).value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById(address1).value = fullAddr;

			// 커서를 상세주소 필드로 이동한다.
			document.getElementById(address2).focus();
		}
	}).open();
}

function checkCorporateRegistrationNumber(value) {
	var valueMap = value.replace(/-/gi, '').split('').map(function(item) {
			return parseInt(item, 10);
	});

	if (valueMap.length === 10) {
			var multiply = new Array(1, 3, 7, 1, 3, 7, 1, 3, 5);
			var checkSum = 0;

			for (var i = 0; i < multiply.length; ++i) {
					checkSum += multiply[i] * valueMap[i];
			}

			checkSum += parseInt((multiply[8] * valueMap[8]) / 10, 10);
			return Math.floor(valueMap[9]) === ((10 - (checkSum % 10)) % 10);
	}

	return false;
}

$().ready(function() {
	fncPageReady();
});
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
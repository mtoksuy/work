//���[���I�[�o�[
/*------------------------------------------------------------
	Standards Compliant Rollover Script
	Author : Daniel Nolan
	http://www.bleedingego.co.uk/webdev.php
 *------------------------------------------------------------
 * �}�E�X�I�[�o�[���̉摜�؂�ւ�
 * ���[���I�[�o�[��ݒ肷��摜�ɃN���X���uimgover�v���w��
 * ���[���I�[�o�[���ɕ\�����邽�߂̉摜�t�@�C�������Ɂu_o�v������
/*------------------------------------------------------------*/

function initRollovers() {
	if (!document.getElementById) return

	var aPreLoad = new Array();
	var sTempSrc;
	var aImages = document.getElementsByTagName('img');

	for (var i = 0; i < aImages.length; i++) {		
		if (aImages[i].className == 'imgover') {
			var src = aImages[i].getAttribute('src');
			var ftype = src.substring(src.lastIndexOf('.'), src.length);
			var hsrc = src.replace(ftype, '_o'+ftype);

			aImages[i].setAttribute('hsrc', hsrc);

			aPreLoad[i] = new Image();
			aPreLoad[i].src = hsrc;

			aImages[i].onmouseover = function() {
				sTempSrc = this.getAttribute('src');
				this.setAttribute('src', this.getAttribute('hsrc'));
			}	

			aImages[i].onmouseout = function() {
				if (!sTempSrc) sTempSrc = this.getAttribute('src').replace ('_o'+ftype, ftype);
				this.setAttribute('src', sTempSrc);
			}
		}
	}
}

try{
	window.addEventListener("load",initRollovers,false);
}catch(e){
	window.attachEvent("onload",initRollovers);
}

//�R�s�[���C�g�̔N�������J�E���g
//------------------------------------------------------------
function year() {  
var data = new Date();  
var now_year = data.getFullYear();  
document.write(now_year);  
}  

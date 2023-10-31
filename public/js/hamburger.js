const menu_btn = document.querySelector('.hamburber');
const mobile_menu = document.querySelector('header');

let changusCount = 0;

console.log(menu_btn);
function hamburberButton() {
	menu_btn.classList.toggle('is-active');
	mobile_menu.classList.toggle('is-active');
	changusCount++;
	console.log('changus has been found ' + changusCount + ' times');
}

setTimeout(() => {
	mobile_menu.style.transition = '0.4s';
}, 500);

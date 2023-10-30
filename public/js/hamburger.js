const menu_btn = document.querySelector('.hamburber');
const mobile_menu = document.querySelector('header');

console.log(menu_btn);
function hamburberButton() {
    menu_btn.classList.toggle('is-active');
    mobile_menu.classList.toggle('is-active');
 console.log("changus");
};

setTimeout(() => {
    mobile_menu.style.transition = '0.4s';
}, 500)

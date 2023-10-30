const menu_btn = document.querySelector('.hamburber');
const mobile_menu = document.querySelector('header');

menu_btn.addEventListener('click', function () {
    menu_btn.classList.toggle('is-active');
    mobile_menu.classList.toggle('is-active');

});

setTimeout(() => {
    mobile_menu.style.transition = '0.4s';
}, 500)

console.log(menu_btn);
console.log(mobile_menu);
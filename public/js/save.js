
const Saglabat = document.querySelectorAll('.Saglabat');
Saglabat.forEach(Saglabat => {
  Saglabat.addEventListener('click', save);
});
console.log(Saglabat);


function save(event){
    event.preventDefault();
}

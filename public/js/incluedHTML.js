function includeHTML () {
    const elements = document.querySelectorAll('[include]');
    for(elem of elements) {
        fetch(elem.getAttribute('include'))
        .then(response => response.text())
        .then(data => {
          elem.innerHTML = data;
        })
        .catch(err => console.error(err));
    }
}
includeHTML();
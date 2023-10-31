document.addEventListener('DOMContentLoaded', includeHTML);

async function includeHTML() {
  const elements = document.querySelectorAll('[include]');
  for (elem of elements) {
    try {
      const response = await fetch(elem.getAttribute('include'));
      const data = await response.text();
      elem.innerHTML = data;
    } catch (err) {
      console.error(err);
    }
  }
}
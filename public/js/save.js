document.addEventListener('DOMContentLoaded', () => {
	console.log('should work');

	const Saglabat = document.querySelectorAll('button.Saglabat');
	console.log(Saglabat);
	Saglabat.forEach(Saglabat => {
		Saglabat.addEventListener('click', save);
	});
});

// Create a new Mutation Observer
const observer = new MutationObserver(function (mutationsList, observer) {
  const Saglabat = document.querySelectorAll('button.Saglabat');
	console.log(Saglabat);
	Saglabat.forEach(Saglabat => {
		Saglabat.addEventListener('click', save);
	});
});

// Define the options for the observer
const config = {
	childList: true, // Watch for changes to the child nodes (new elements)
	subtree: true, // Watch for changes in the entire subtree
};

// Start observing the DOM with the specified configuration
observer.observe(document.body, config); // You can observe any DOM element, not just document.body

function save(event) {
	event.preventDefault();
}

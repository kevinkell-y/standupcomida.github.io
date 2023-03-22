// function openContactForm() {
//   window.open('/contact.html', 'Contact Form', 'width=600,height=400');
// }
//

// Get the contact button and overlay elements
const contactBtn = document.getElementById('contact-btn');
const overlay = document.querySelector('.overlay');

// Get the close button and contact form elements
const closeBtn = overlay.querySelector('.close-button');
const contactForm = overlay.querySelector('#contact-form');

// Show the overlay and contact form when the contact button is clicked
contactBtn.addEventListener('click', () => {
  overlay.style.display = 'block';
});

// Hide the overlay and contact form when the close button is clicked
closeBtn.addEventListener('click', () => {
  overlay.style.display = 'none';
});

// Hide the overlay and contact form when the user clicks outside of the form
window.addEventListener('click', (e) => {
  if (e.target === overlay) {
    overlay.style.display = 'none';
  }
});

// Clear the form inputs when the form is submitted successfully
contactForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(contactForm);

  // Validate the form inputs before submitting the form
  if (!validateForm(formData)) {
    return;
  }

  const response = await fetch(contactForm.action, {
    method: 'POST',
    body: formData
  });

  if (response.ok) {
    alert('Your message has been sent!');
    contactForm.reset();
    overlay.style.display = 'none';
  } else {
    alert('Something went wrong. Please try again later.');
  }
});

// Validate the form inputs before submitting the form
function validateForm(formData) {
  const name = formData.get('name');
  const email = formData.get('email');
  const message = formData.get('message');

  if (name.trim() === '') {
    alert('Please enter your name.');
    return false;
  }

  if (email.trim() === '') {
    alert('Please enter your email address.');
    return false;
  }

  if (message.trim() === '') {
    alert('Please enter your message.');
    return false;
  }

  return true;
}

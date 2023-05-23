// Get the text element
const textElement = document.getElementById('mailIcon');

// Get the original full text
const fullText = textElement.textContent;

// Function to handle the text truncation
function truncateText() {
  // Get the available width
  const availableWidth = textElement.clientWidth;

  // Set the full text as the default text
  textElement.textContent = fullText;

  // Check if the text overflows
  if (textElement.scrollWidth > availableWidth) {
    // Calculate the maximum number of characters to display
    let maxChars = fullText.length;
    while (textElement.scrollWidth > availableWidth && maxChars > 0) {
      maxChars--;
      textElement.textContent = fullText.substring(0, maxChars) + '..';
    }
  }
}

// Call the function initially
truncateText();

// Call the function whenever the window is resized
window.addEventListener('resize', truncateText);
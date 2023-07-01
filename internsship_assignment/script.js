document.getElementById('healthReportForm').addEventListener('submit', function(event) {
  event.preventDefault();
  
  const formData = new FormData(this);

  // If you want to do any client-side validation, you can add it here before submitting the form.

  // Submit the form data to the server using fetch API.
  fetch('insert_user_details.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    console.log(data);
    // Optionally, you can show a success message or redirect the user to another page after successful submission.
    // After successful submission, reload the table to display the updated user data.
    location.reload();
  })
  .catch(error => {
    console.error('Error:', error);
    // Handle the error here if something goes wrong with the submission.
  });
});

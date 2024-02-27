$(document).ready(function() {
    // Check if the loan has already been accepted
    var isLoanAccepted = "<?php echo $data['action_taken']; ?>";
  
    // Function to process the loan action
    function processLoanAction(loanNo, action, dueDate, buttonContainer) {
      $.ajax({
        type: 'POST',
        url: '/admin/memberLoan/api/processLoanAction.php',
        data: { loanNo: loanNo, action: action, dueDate: dueDate }, // Include dueDate in the data sent
        success: function(response) {       
          // Redirect to /Admin/MemberLoan/loan.php
          window.location.href = '/admin/memberLoan/loan.php';
        },
        error: function(xhr, status, error) {
          // Handle errors, if any
        }
      });
    }
  
    // Hide or show the accept and reject buttons based on the loan acceptance status
    if (isLoanAccepted == 'Accepted' || isLoanAccepted == 'Rejected') {
      $('.accept-btn, .reject-btn').hide();
    } else {
      $('.accept-btn, .reject-btn').show();
    }
  
    // Add event listeners to the accept and reject buttons
    $('.accept-btn').click(function() {
      var loanNo = <?php echo $data['loanNo']; ?>;
      var account_number = <?php echo $data['account_number']; ?>;
      var action = 'Accepted';
      var dueDate = $('input[name="dueDate"]').val(); // Get the dueDate from the form
      var buttonContainer = $(this);
      processLoanAction(loanNo, action, dueDate, account_number, buttonContainer); // Include dueDate in the function call
    });
  
    $('.reject-btn').click(function() {
      var loanNo = <?php echo $data['loanNo']; ?>;
      var action = 'Rejected';
      var account_number = <?php echo $data['account_number']; ?>;
      var dueDate = $('input[name="dueDate"]').val(); // Get the dueDate from the form
      var buttonContainer = $(this);
      processLoanAction(loanNo, action, dueDate, account_number, buttonContainer); // Include dueDate in the function call
    });
  });
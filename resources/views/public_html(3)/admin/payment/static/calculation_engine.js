$(document).ready(function() {
    
    $('form').on('focus', 'input[type=number]', function (e) {
      $(this).on('wheel.disableScroll', function (e) {
        e.preventDefault()
      })
    })
    $('form').on('blur', 'input[type=number]', function (e) {
      $(this).off('wheel.disableScroll')
    })
    
  var checkLoanBalance = setInterval(function() {
    var $loanBalance = $("#loanBalance");
    var loanBalanceValue = $loanBalance.val();

    if (loanBalanceValue) {
      clearInterval(checkLoanBalance); // Stop checking once the value is set
      initializeBalances(); // Initialize the balances
    }
  }, 100); // Check every 100ms

  // Initialize the form elements and balances
  function initializeBalances() {
    $("#remarks").prop("disabled", false);

    var $loanBalance = $("#loanBalance");
    var $currentBalance = $("#currentBalance");
    var $balance = $("#balance");
    var $totalBalance = $("#totalBalance");
    var $remarks = $("#remarks");
    var $updatedBalance = $("#updated-balance");

    // console.log("Loan Balance field value:", $loanBalance.val());
    var originalLoanBalance = parseFloat($loanBalance.val()) || 0;
    var originalCurrentBalance = parseFloat($currentBalance.val()) || 0;

    // Function to update balances
    function updateBalances(amount) {
      var newLoanBalance = Math.max(originalLoanBalance - amount, 0);
      var newCurrentBalance = Math.max(originalCurrentBalance - amount, 0);

      $totalBalance.val(newLoanBalance.toFixed(2));
      $currentBalance.val(newCurrentBalance.toFixed(2));
      $updatedBalance.val(newCurrentBalance.toFixed(2)); // Set the updated balance
      $remarks.val(newLoanBalance === 0 ? "Paid" : "Unpaid");

    //   console.log("New Loan Balance:", newLoanBalance);
    //   console.log("New Current Balance:", newCurrentBalance);
    }

    // Attach the input event listener to the balance field
    $balance.on("input", function() {
      var amount = parseFloat($(this).val());
    //   console.log("Amount entered:", amount);

      if (isNaN(amount) || amount <= 0) {
        $totalBalance.val(originalLoanBalance.toFixed(2));
        $currentBalance.val(originalCurrentBalance.toFixed(2));
        $remarks.val("Unpaid");
        toastr.warning("Invalid input. Please enter a valid positive number.");
      } else {
        updateBalances(amount);
      }
    });
  }

  // Form submission handler
  $("#payment-form").on("submit", function(event) {
    var $updatedBalance = $("#updated-balance");
    if (!$updatedBalance.val()) {
      event.preventDefault(); // Prevent the form from being submitted
      toastr.warning("Please enter a valid payment amount.");
    }
  });
});
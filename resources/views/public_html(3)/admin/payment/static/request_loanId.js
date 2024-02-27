$(document).ready(function() {
  if ($('#loanNo').val()) {
    $('#loanNo').trigger('change');
  }
});

$('#loanNo').on('change', function() {
  var loanNo = $(this).val();
  $.ajax({
    url: '/admin/payment/api/getLoanData.php',
    data: { loanNo: loanNo },
    type: 'GET',
    success: function(response) {
      response = JSON.parse(response);
      if (response.remarks && typeof response.balance !== 'undefined') {
        if (parseFloat(response.balance) === 0 && response.remarks === "Paid") {
          $('#loanBalance').val('0.00');
          $('#totalBalance').val('0.00');
          $('#remarks').val('Paid');
        } else {
          $('#loanBalance').val(response.balance);
          $('#totalBalance').val(response.balance);
        }
      }
    }
  });
});
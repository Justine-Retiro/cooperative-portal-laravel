// function validateForm() {
//     // Get the values of the required fields
//     var name = document.getElementById("name").value;
//     var college = document.getElementById("college").value;
//     var number = document.getElementById("contact").value;
//     var amount = document.getElementById("amount_before").value;
//     var dateofApplication = document.getElementById("doa").value;
//     var amount_after = document.getElementById("amount_after").value;
//     var signature = document.getElementById("signature").value;
//     var homepay_receipt = document.getElementById("homepay_receipt").value;
//     // Check if the required fields are empty
//     if (
//         name === "" ||
//         college === "" ||
//         number === "" ||
//         amount === "" ||
//         dateofApplication === "" ||
//         amount_after === "" ||
        
//         homepay_receipt === isNaN

//     ) {
//         alert("Please fill out all required fields.");
//         return false; // Prevent form submission
//     }
//     return true
// }

document.getElementById('loan_term_Type').onchange = function() {
var loanTermField = document.getElementById('time_pay');
loanTermField.disabled = this.value === "" ? true : false;
if (!loanTermField.disabled) {
    loanTermField.oninput = calculateInterest;
}
calculateInterest();
}

$('form').on('focus', 'input[type=number]', function (e) {
  $(this).on('wheel.disableScroll', function (e) {
    e.preventDefault()
  })
})
$('form').on('blur', 'input[type=number]', function (e) {
  $(this).off('wheel.disableScroll')
})

function calculateInterest() {
const principal = parseFloat($('#amount_before').val());
const rate = 1;
const timeType = $('#loan_term_Type').val();
let tTime = parseFloat($('#time_pay').val());

// Convert time to years if it's in months
if (timeType === "month/s") {
    time = tTime / 12;
}

if (!isNaN(principal) && !isNaN(rate) && !isNaN(time)) {
    const interest = (principal * rate * time) / 100;
    const totalAmount = principal + interest;
            
    $('#amount_after').val(totalAmount.toFixed(2));
    $('#interestRate').val(rate);

    $('#amount_after').on('input', function() {
        $(this).val(parseFloat($(this).val()).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
    });
    // Output the summary
    const summaryElement = $('#summary');
    summaryElement.html(`
        <h5>Loan amount: ${principal.toLocaleString('en-US', {style: 'currency', currency: 'PHP'})}</h5>
        <span>Interest rate: ${rate}%</span> 
        <br>
        <span>Time to pay: ${tTime} ${timeType}</span>
        <h4>Total amount: ${totalAmount.toLocaleString('en-US', {style: 'currency', currency: 'PHP'})}</h4>
    `);
} else {
    $('#amount_after').val('');
    $('#summary').html('');
}
}

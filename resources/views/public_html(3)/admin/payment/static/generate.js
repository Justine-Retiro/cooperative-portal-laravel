$(document).ready(function(){
    $("#add-mem").click(function(){
        $.ajax({
            url: "/admin/repositories/new/generate_account.php",
            type: "get",
            success: function(result){
                alert("Account Number: " + result.account_number + "\nPassword: " + result.password);
            }
        });
    });
});
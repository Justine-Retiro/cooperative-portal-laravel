$.ajax({
    url: '/admin/repositories/api/fetchData.php', // URL of your PHP script
    type: 'GET',
    dataType: 'json',
    success: function(data) {
        // Insert the data into the HTML
        $('#lastNameText').text(data.last_name);
        $('#middleNameText').text(data.middle_name);
        $('#firstNameText').text(data.first_name);
        $('#citizenshipText').text(data.citizenship);
        $('#cityAddressText').text(data.city_address);
        $('#contactAddressText').text(data.contactAddress);
        $('#workPositionText').text(data.workPosition);
        $('#provincialAddressText').text(data.provincial_address);
        $('#mailingAddressText').text(data.mailing_address);
        $('#placeOfBirthText').text(data.birth_place);
        $('#natureOfWork').text(data.natureOf_work);
        $('#spouseNameText').text(data.spouse_name);
        $('#taxIdentificationNumberText').text(data.taxID_num);
        $('#dateOfBirthText').text(data.birth_date);
        $('#amountOfshares').text(data.amountOf_share);
    },
    error: function(jqXHR, textStatus, errorThrown) {
        // Handle any errors
        console.log(textStatus, errorThrown);
    }
});
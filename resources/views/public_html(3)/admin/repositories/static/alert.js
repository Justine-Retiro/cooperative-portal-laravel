function validateForm() {
    // Get the values of the required fields
    var firstName = document.getElementById("first_name").value;
    var lastName = document.getElementById("last_name").value;
    var citizenship = document.getElementById("citizenship").value;
    var cityAddress = document.getElementById("city_address").value;
    var phoneNumber = document.getElementById("phone_num").value;
    var position = document.getElementById("position").value;

    // Check if the required fields are empty
    if (
      firstName === "" ||
      lastName === "" ||
      citizenship === "" ||
      cityAddress === "" ||
      phoneNumber === "" ||
      position === ""
    ) {
      alert("Please fill out all required fields.");
      return false; // Prevent form submission
    }
  }
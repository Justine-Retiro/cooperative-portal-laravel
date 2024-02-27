function validateForm() {
    // Get the values of the required fields
    var name = document.getElementById("name").value;
    var college = document.getElementById("college").value;
    var number = document.getElementById("contact").value;
    var amount = document.getElementById("amount").value;
    var dateofApplication = document.getElementById("doa").value;
    var signature = document.getElementById("signature").value;

    // Check if the required fields are empty
    if (
      name === "" ||
      college === "" ||
      number === "" ||
      amount === "" ||
      dateofApplication === "" ||
      signature === ""
    ) {
      alert("Please fill out all required fields.");
      return false; // Prevent form submission
    }
  }
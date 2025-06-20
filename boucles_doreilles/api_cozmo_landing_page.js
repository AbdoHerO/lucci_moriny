$("#formInfo").submit(function (event) {
  // show loading icon and disable the button
  $("#save_guest_order").prop("disabled", true);
  $("#span_loading").show();

  // Prevent the default form submission
  event.preventDefault();

// Get the updated data from the form
  var fullname = $('#formInfo input[name="fullname"]').val();
  var phone = $('#formInfo input[name="phone"]').val();
  var adresse = $('#formInfo input[name="adresse"]').val();


  var variant = $('#formInfo select[name="tier_variante"]').val();
  // var number_tier = $('#formInfo input[name="number_tier"]').val();
  var price = $('#formInfo input[name="price_tiers"]').val();

// Create the data object for SheetDB
  var sheetDBData = {
    name: "boucles_doreilles",
    date: new Date().toString(),
    customer_name: fullname,
    phone: phone,
    city: "-",
    address: adresse,
    quantity: "",
    price: variant,
    product_notice: "",
    notice: "",
    status: "pending",
    fees_shipping: "",
	  size: "",
  };

  console.log("sheetDBData", sheetDBData);

  // Insert into SheetDB API
  fetch("https://sheetdb.io/api/v1/x9wpmex7r46yh", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": "Bearer p1ojgyhaj2m9jxty1tf0qo4i3n2qh9rgxrpyp5su"
    },
    body: JSON.stringify({ data: sheetDBData }),
  })
    .then(function (response) {
      console.log("response", response);
      console.log("sent");
      if (response.ok) {
        // Handle successful response from SheetDB
        console.log("Order added to SheetDB successfully");

        // To track the purchase event using Facebook Pixel
        fbq("track", "Purchase", {
          value: 50,
          currency: "USD",
          content_name:
            "boucles_doreilles",
          content_type: "Home & Kitchen",
          product_id: "1127",
        });

        document.location.href = "/boucles_doreilles/order_success.html";
        // To track the purchase event using Snap Pixel
        // snaptr("track", "PURCHASE", { value: 132, currency: "USD" });
      } else {
        // Handle error response from SheetDB
        console.log("Failed to add order to SheetDB");
        $("#save_guest_order").prop("disabled", false);
        $("#span_loading").hide();
        console.log("Error :", error);
      // // Display an error message if the update fails
      // alert("وقع حطأ اثناء الطب , يرجى المحاولة لاحقا ");
        // throw new Error("Failed to add order to SheetDB");
      }
    })
    .catch(function (error) {
      console.log("NOT sent");
      console.log("Error:", error);
      // Display an error message if the request fails
      // alert("Failed to add order to SheetDB. Please try again later.");
    });
});

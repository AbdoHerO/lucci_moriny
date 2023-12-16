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
    name: "Multiple Storage Rack",
    date: new Date().toString(),
    customer_name: fullname,
    phone: phone,
    city: "-",
    address: adresse,
    quantity: "1",
    price: price + " Dh",
    product_notice: variant,
    notice: "",
    status: "pending",
    fees_shipping: "",
  };

  console.log("sheetDBData", sheetDBData);

  // Insert into SheetDB API
  fetch("https://sheetdb.io/api/v1/dy7qxfwlse762", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
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
            "mar_4_5_tiers_stainless _steel_collapsible_shelving_organizer",
          content_type: "Home & Kitchen",
          product_id: "1127",
        });

        // To track the purchase event using Snap Pixel
        // snaptr("track", "PURCHASE", { value: 132, currency: "USD" });
      } else {
        // Handle error response from SheetDB
        console.log("Failed to add order to SheetDB");
        // throw new Error("Failed to add order to SheetDB");
      }
    })
    .catch(function (error) {
      console.log("NOT sent");
      console.log("Error:", error);
      // Display an error message if the request fails
      // alert("Failed to add order to SheetDB. Please try again later.");
    });

  // Send an AJAX request to insert the order record
  $.ajax({
    url: "https://noxeva.com/api/ordervisite",
    type: "POST",
    headers: {
      "Access-Control-Allow-Origin": "*",
    },
    cors: true,
    data: {
      first_name: fullname,
      last_name: "",
      phone: phone,
      city: "",
      adresse: adresse,
      id_product: "1132",
      name_product:
        "mar_4_5_tiers_stainless _steel_collapsible_shelving_organizer",
      unit_price: price,
      quantite: "1",
      variant: variant,
      from_landing_page: true,
    },
    success: function (response) {
      // // To track the purchase event using Facebook Pixel
      // fbq("track", "Purchase", {
      //   value: 49.99,
      //   currency: "USD",
      //   content_name: "mar_4_5_tiers_stainless _steel_collapsible_shelving_organizer",
      //   content_type: "Home & Kitchen",
      //   product_id: "1127",
      // });

      document.location.href =
        "/order_success.html";
      // hide loading icon and enable the button
      //   $("#save_guest_order").prop("disabled", false);
      //   $("#span_loading").hide();
      console.log("response", response);

      // swal({
      //   title: "ØªÙ…Øª Ø§Ù„Ø·Ù„Ø¨ÙŠØ© Ø¨Ù†Ø¬Ø§Ø­!",
      //   text: "Ø³ÙŠØªØµÙ„ Ø¨Ùƒ ÙØ±ÙŠÙ‚Ù†Ø§ Ù„ØªØ£ÙƒÙŠØ¯ Ø§Ù„Ø·Ù„Ø¨ÙŠØ©",
      //   icon: "success",
      //   buttons: {
      //     confirm: {
      //       className: "btn btn-success",
      //     },
      //   },
      // });
    },
    error: function (xhr, status, error) {
      // hide loading icon and enable the button
      $("#save_guest_order").prop("disabled", false);
      $("#span_loading").hide();
      console.log("Error :", error);
      // // Display an error message if the update fails
      // alert("ÙˆÙ‚Ø¹ Ø­Ø·Ø£ Ø§Ø«Ù†Ø§Ø¡ Ø§Ù„Ø·Ù„Ø¨ , ÙŠØ±Ø¬Ù‰ Ø§Ù„Ù…Ø­Ø§ÙˆÙ„Ø© Ù„Ø§Ø­Ù‚Ø§ "); 

      document.location.href =
        "/order_success.html";
    },
  });
});
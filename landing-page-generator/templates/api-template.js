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
  var product_color = $('#formInfo select[name="product_color"]').val();
  var price = $('#formInfo input[name="price_tiers"]').val();
  var product_size = $('#formInfo select[name="product_size"]').val();

  // Create the data object for SheetDB
  {{CUSTOM_DATA_FORMAT}}

  console.log("sheetDBData", sheetDBData);

  // Insert into SheetDB API
  fetch("https://sheetdb.io/api/v1/{{SHEETDB_API}}", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": "{{SHEETDB_TOKEN}}"
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
          content_name: "{{PRODUCT_NAME}}",
          content_type: "Product",
          product_id: "{{PRODUCT_ID}}",
        });

        // To track the purchase event using TikTok Pixel
        ttq.track('CompletePayment', {
          content_id: '{{PRODUCT_ID}}',
          content_type: 'product',
          value: price,
          currency: 'MAD'
        });

        document.location.href = "{{SUCCESS_URL}}";
      } else {
        // Handle error response from SheetDB
        console.log("Failed to add order to SheetDB");
        $("#save_guest_order").prop("disabled", false);
        $("#span_loading").hide();
        console.log("Error :", error);
      }
    })
    .catch(function (error) {
      console.log("NOT sent");
      console.log("Error:", error);
      // Display an error message if the request fails
      $("#save_guest_order").prop("disabled", false);
      $("#span_loading").hide();
    });
});

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
  // var number_tier = $('#formInfo input[name="number_tier"]').val();
  var price = $('#formInfo input[name="price_tiers"]').val();
  var product_size = $('#formInfo select[name="product_size"]').val();

// Create the data object for SheetDB
  var sheetDBData = {
    name: "casual-pant-montoni",
    date: new Date().toString(),
    customer_name: fullname,
    phone: phone,
    city: "-",
    address: adresse,
    quantity: variant,
    price: price,
    product_notice: "",
    notice: "Color: " + product_color,
    status: "pending",
    fees_shipping: "",
	size: product_size,
  };

  console.log("sheetDBData", sheetDBData);

  // Insert into SheetDB API
  fetch("https://sheetdb.io/api/v1/1torsnrc4ndad", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Authorization": "Bearer 7r1osfye3822uciijcpi89y8gveyn9d3ruogcgcj"
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
            "casual-pant-classe",
          content_type: "Home & Kitchen",
          product_id: "1127",
        });

        document.location.href = "/casual-pant-classe/order_success.html";
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

  // Send an AJAX request to insert the order record
//   $.ajax({
//     url: "https://noxeva.com/api/ordervisite",
//     type: "POST",
//     headers: {
//       "Access-Control-Allow-Origin": "*",
//     },
//     cors: true,
//     data: {
//       first_name: fullname,
//       last_name: "",
//       phone: phone,
//       city: "",
//       adresse: adresse,
//       id_product: "1132",
//       name_product:
//         "mar_4_5_tiers_stainless _steel_collapsible_shelving_organizer",
//       unit_price: price,
//       quantite: "1",
//       variant: variant,
//       from_landing_page: true,
//     },
//     success: function (response) {
//       // // To track the purchase event using Facebook Pixel
//       // fbq("track", "Purchase", {
//       //   value: 49.99,
//       //   currency: "USD",
//       //   content_name: "mar_4_5_tiers_stainless _steel_collapsible_shelving_organizer",
//       //   content_type: "Home & Kitchen",
//       //   product_id: "1127",
//       // });

//       document.location.href =
//         "/lp/order_success.html";
//       // hide loading icon and enable the button
//       //   $("#save_guest_order").prop("disabled", false);
//       //   $("#span_loading").hide();
//       console.log("response", response);

//       // swal({
//       //   title: "تمت الطلبية بناح!",
//       //   text: "سيصل بك فريقنا لتأكيد الطلبية",
//       //   icon: "success",
//       //   buttons: {
//       //     confirm: {
//       //       className: "btn btn-success",
//       //     },
//       //   },
//       // });
//     },
//     error: function (xhr, status, error) {
//       // hide loading icon and enable the button
//       $("#save_guest_order").prop("disabled", false);
//       $("#span_loading").hide();
//       console.log("Error :", error);
//       // // Display an error message if the update fails
//       // alert("وقع حطأ اثناء الطب , يرجى المحاولة لاحقا "); 

//       document.location.href =
//         "/lp/order_success.html";
//     },
//   });
});

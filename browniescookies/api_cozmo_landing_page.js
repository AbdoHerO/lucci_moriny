$("#formInfo").submit(async function (event) {
  // show loading icon and disable the button
  $("#save_guest_order").prop("disabled", true);
  $("#span_loading").show();

  // Prevent the default form submission
  event.preventDefault();

  // Get the updated data from the form
  var fullname = $('#formInfo input[name="fullname"]').val();
  var phone = $('#formInfo input[name="phone"]').val();
  var adresse = $('#formInfo input[name="adresse"]').val();

  // Create the data object for SheetDB
  var sheetDBData = {
    name: "borwnies_cookies",
    date: new Date().toString(),
    customer_name: fullname,
    phone: phone,
    city: adresse,
    address: adresse,
    quantity: "1",
    price: "---- MAD",
    product_notice: "",
    notice: "",
    status: "pending",
    fees_shipping: "",
  };

  try {
    // Insert into SheetDB API
    const response = await fetch("https://sheetdb.io/api/v1/7fx0hvcoif8ln", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ data: sheetDBData }),
    });

    if (response.ok) {
      // Handle successful response from SheetDB
      console.log("Order added to SheetDB successfully");

      // To track the purchase event using Facebook Pixel
      fbq("track", "Purchase", {
        value: 10,
        currency: "USD",
        content_name: "Borwnies & Cookies",
        content_type: "Cooking",
      });

      // Create the WhatsApp message
      const whatsappMessage = `New order received:\nName: ${fullname}\nPhone: ${phone}\nAddress: ${adresse}`;

      // Construct the WhatsApp URL
      const whatsappUrl = `https://api.whatsapp.com/send?phone=+212612167034&text=${encodeURIComponent(whatsappMessage)}`;

      // Open the WhatsApp chat in a new tab
      window.open(whatsappUrl, '_blank');

      // Redirect to the thank you page
      document.location.href = "/order_success.html";
    } else {
      // Handle error response from SheetDB
      console.log("Failed to add order to SheetDB");
      // throw new Error("Failed to add order to SheetDB");
    }
  } catch (error) {
    console.log("Error:", error);
    // Display an error message if the request fails
    // alert("Failed to add order to SheetDB. Please try again later.");
  }

  // Continue with the rest of your code if needed
});

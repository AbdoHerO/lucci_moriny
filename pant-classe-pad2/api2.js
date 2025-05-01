document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('formInfo');
    const packHeaders = document.querySelectorAll('.pack-header');
    const packDetails = document.querySelectorAll('.pack-details');
    let selectedPack = 1; // Par défaut, le Pack 1 est sélectionné

    // Afficher le Pack 1 par défaut
    packDetails[0].style.display = 'block';

    packHeaders.forEach((header, index) => {
        header.addEventListener('click', function () {
            const packDetail = packDetails[index];
            
            // Fermer tous les autres packs
            packDetails.forEach((detail, i) => {
                if (i !== index) {
                    detail.style.display = 'none';
                    // Supprimer l'attribut required des champs des packs non sélectionnés
                    removeRequiredFromPack(i + 1);
                }
            });

            // Basculer la visibilité du pack cliqué
            if (packDetail.style.display === 'block') {
                packDetail.style.display = 'none';
                removeRequiredFromPack(index + 1);
                selectedPack = 0;
            } else {
                packDetail.style.display = 'block';
                addRequiredToPack(index + 1);
                selectedPack = index + 1;
            }

            // Mettre à jour la valeur du champ caché 'qte'
            document.getElementById('qte').value = selectedPack;
        });
    });

    function addRequiredToPack(packNumber) {
        const colorSelects = document.querySelectorAll(`[id^="product_color_${packNumber}"]`);
        const sizeSelects = document.querySelectorAll(`[id^="product_size_${packNumber}"]`);
        
        colorSelects.forEach(select => select.required = true);
        sizeSelects.forEach(select => select.required = true);
    }

    function removeRequiredFromPack(packNumber) {
        const colorSelects = document.querySelectorAll(`[id^="product_color_${packNumber}"]`);
        const sizeSelects = document.querySelectorAll(`[id^="product_size_${packNumber}"]`);
        
        colorSelects.forEach(select => select.required = false);
        sizeSelects.forEach(select => select.required = false);
    }

    // Ajouter l'attribut required au Pack 1 par défaut
    addRequiredToPack(1);

    // Gérer la soumission du formulaire
    form.addEventListener('submit', function(e) {
        if (selectedPack === 0) {
            e.preventDefault();
            alert('Veuillez sélectionner un pack avant de soumettre le formulaire.');
        }
    });

    $("#formInfo").submit(function (event) {
        event.preventDefault();

        var trafic_name = "";
        function hasFbclidParameter() {
            url = window.location.href;
            url.indexOf('fbclid') !== -1 ? trafic_name = 'Facebook' : '';
            url.indexOf('ttclid') !== -1 ? trafic_name = 'Tiktok' : '';
            url.indexOf('gclid') !== -1 ? trafic_name = 'Google Ads' : '';
        }
        hasFbclidParameter();

        $("#save_guest_order").prop("disabled", true);
        $("#span_loading").show();

        const formData = new FormData(this);
        let colors = [];
        let sizes = [];
        let total = 0;
        let quantity = 0;

        formData.forEach((value, key) => {
            if (key.startsWith('product_color') && value !== "") {
                colors.push(value);
            } else if (key.startsWith('product_size') && value !== "") {
                sizes.push(value);
            }
        });

        if (document.querySelector('.pack-details[style*="display: block"]')) {
            const packDetailsVisible = document.querySelector('.pack-details[style*="display: block"]');
            const packHeader = packDetailsVisible.previousElementSibling.querySelector('.title').textContent;

            switch (packHeader) {
                case 'واحد ب 249 درهم':
                    total = 249;
                    quantity = 1;
                    break;
                case 'إثنان ب 459 فقط':
                    total = 459;
                    quantity = 2;
                    break;
                case 'ثلاثة سراويل ب 629 درهم فقط':
                    total = 629;
                    quantity = 3;
                    break;
                default:
                    total = 0;
            }
        }

        const fullname = $('#formInfo input[name="fullname"]').val();
        const phone = $('#formInfo input[name="phone"]').val();
        const adresse = $('#formInfo input[name="adresse"]').val();

        const dateObject = new Date();
        const formattedDate = dateObject.toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        }).replace(',', ' à');

        const colorsString = colors.join(', ');
        const sizesString = sizes.join(', ');

        // // cathedis
        // var sheetDBData = {
        //   marchandise : "Pant Classe",
        //   client : fullname,
        //   tlphone : phone,
        //   ville : "-",
        //   secteur : "Centre Ville",
        //   adresse : adresse,
        //   type : "Livraison CRBT",
        //   mode_paiement : "ESPECES",
        //   montant : totalPrice.substring(0, 3),
        //   caution : "0",
        //   n_cmd : "",
        //   poids : "Entre 1.2 Kg et 5 Kg",
        //   fragile : "0",
        //   declare_value_dh : "",
        //   valeur_du_poids_kg : "",
        //   profondeur_en_cm : "",
        //   longueur_en_cm : "",
        //   largeur_en_cm : "",
        //   commentaire : "",
        //   autoriser_ouverture : "1",
        //   nbre_de_colis : "1",
        //   numero_tel_a_notifier : "",
        //   tag : "",
        //   quantity: quantity,
        //   color: colorsString,
        //   size: sizesString,
        //   createdAt: formattedDate,
        //   status: "en cours",
        //   trafic: trafic_name,
        // };


        // atlas livraison
        var sheetDBData = {
            date: new Date().toString(),
            destinataire: fullname,
            telephone: phone.toString(),
            ville: "-", // or set a default
            adresse: adresse,
            prix: total.toString(),
            produit: "Pant-"+colorsString+"-"+sizesString,
            id_intern: "",
            change: "0",
            ouvrir_colis: "1",
            essayage: "1",
            quantity: quantity,
            color: colorsString,
            size: sizesString,
            createdAt: formattedDate,
            montant : total.toString(),
            status: "en cours",
            trafic: trafic_name,
        };

        console.log("sheetDBData", sheetDBData);

        fetch("https://sheetdb.io/api/v1/0tjsq029vh1s9", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Authorization": "Bearer c4pbl6r3lwr8r0bossphcnv02tpic1dqlp40ifla"
            },
            body: JSON.stringify({ data: sheetDBData }),
          })
        .then(function (response) {
            console.log("response", response);
            if (response.ok) {
                console.log("Order added to SheetDB successfully");
                fbq("track", "Purchase", {
                    value: total,
                    currency: "MAD",
                    content_name: "Pantalon",
                    content_type: "Home & Kitchen",
                    product_id: "1127",
                });
                document.location.href = "order_success.html";
            } else {
                console.log("Failed to add order to SheetDB");
                $("#save_guest_order").prop("disabled", false);
                $("#span_loading").hide();
            }
        })
        .catch(function (error) {
            console.log("Error:", error);
            $("#save_guest_order").prop("disabled", false);
            $("#span_loading").hide();
        });
    });
});
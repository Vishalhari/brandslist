<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alphabetical Index</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
</head>

<body>

    <div class="container my-5">
        <!-- Title -->
        <h1 class="text-center mb-4">Alphabetical Index</h1>

        <!-- Alphabet Buttons -->
        <div class="alphabet d-flex flex-wrap justify-content-center mb-4">
            <!-- Buttons will be generated dynamically -->
        </div>

        <!-- Items Section -->
        <div class="items row">
            <!-- Items will be displayed dynamically -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");

        // Generate alphabet buttons with an "All" button
        const alphabetContainer = document.querySelector('.alphabet');

        // "All" button
        const allButton = document.createElement('button');
        allButton.className = "btn btn-outline-dark m-1"; // Bootstrap classes
        allButton.textContent = "All";
        allButton.addEventListener('click', () => fetchItems("All"));
        alphabetContainer.appendChild(allButton);

        // Alphabet buttons
        alphabet.forEach(letter => {
            const button = document.createElement('button');
            button.className = "btn btn-outline-primary m-1"; // Bootstrap classes
            button.textContent = letter;
            button.addEventListener('click', () => fetchItems(letter));
            alphabetContainer.appendChild(button);
        });

        // Fetch items from API
        function fetchItems(letter) {
            const itemsContainer = document.querySelector('.items');
            itemsContainer.innerHTML = ""; // Clear previous items

            const apiUrl = letter === "All" ?
                "{{ url('get_allbrands') }}" // Endpoint to get all items
                :
                `{{ url('allbrandsby_alphebet/${letter}') }}`; // Filtered items by letter

            // Use fetch API to get data from the server
            $.ajax({
                url: apiUrl,
                dataType: "json",
                type: "get",
                success: function(res) {
                    if (res.data.length > 0)
                        console.log(res);
                    renderItems(res.data, itemsContainer);
                }
            })
        }

        // Render items in a grid layout
        function renderItems(items, container) {
            var url = window.location.origin;

            items.forEach(item => {
                const itemBlock = document.createElement('div');
                itemBlock.className = "col-md-3 mb-4"; // Bootstrap column classes
                var imgurl = url + '/' + item.logo;

                const card = `
      <div class="card h-100">
        <img src="${imgurl}" class="card-img-top" alt="${item.brandname}">
        <div class="card-body text-center">
          <h5 class="card-title">${item.brandname}</h5>
        </div>
      </div>
    `;

                itemBlock.innerHTML = card;
                container.appendChild(itemBlock);
            });
        }

        // Select "All" by default on page load
        document.addEventListener('DOMContentLoaded', () => {
            fetchItems("All");
        });
    </script>

</body>

</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipment Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1, h2 {
            color: #333;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        #bookingResult, #trackingResult {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
        }

        #bookingResult {
            background-color: #d4edda;
            color: #155724;
        }

        #trackingResult {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
    <script>
        async function bookShipment() {
            const formData = {
                customer_name: document.getElementById('customer_name').value,
                address: document.getElementById('address').value,
                city: document.getElementById('city').value,
                state: document.getElementById('state').value,
                pincode: document.getElementById('pincode').value,
                phone: document.getElementById('phone').value,
                weight: document.getElementById('weight').value
            };

            const response = await fetch('shipment_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();
            document.getElementById('bookingResult').innerText = result.message + (result.status === 'success' ? ` AWB Number: ${result.awb_number}` : '');
        }

        async function trackShipment() {
            const awbNumber = document.getElementById('track_awb_number').value;

            const response = await fetch(`track_order.php?awb_number=${awbNumber}`);
            const result = await response.json();

            if (result.status === 'success') {
                const shipment = result.data;
                document.getElementById('trackingResult').innerText = `Status: ${shipment.status}, Customer Name: ${shipment.customer_name}, Address: ${shipment.address}`;
            } else {
                document.getElementById('trackingResult').innerText = result.message;
            }
        }
    </script>
</head>
<body>
    <h1>Shipment Booking</h1>
    <form id="bookingForm" onsubmit="event.preventDefault(); bookShipment();">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" required><br>

        <label for="address">Address:</label>
        <textarea id="address" required></textarea><br>

        <label for="city">City:</label>
        <input type="text" id="city" required><br>

        <label for="state">State:</label>
        <input type="text" id="state" required><br>

        <label for="pincode">Pincode:</label>
        <input type="text" id="pincode" required><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" required><br>

        <label for="weight">Weight (kg):</label>
        <input type="number" id="weight" step="0.01" required><br>

        <button type="submit">Book Shipment</button>
    </form>
    <div id="bookingResult"></div>

    <h2>Track Shipment</h2>
    <label for="track_awb_number">Enter AWB Number:</label>
    <input type="text" id="track_awb_number" required>
    <button onclick="trackShipment()">Track Shipment</button>
    <div id="trackingResult"></div>
</body>
</html>
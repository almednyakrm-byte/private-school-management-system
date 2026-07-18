**edit_الفواتير.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/الفواتير.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$invoice_number = $data['invoice_number'];
$invoice_date = $data['invoice_date'];
$customer_name = $data['customer_name'];
$invoice_total = $data['invoice_total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-lg font-bold mb-4">Edit Invoice</h1>
        <form id="edit-invoice-form">
            <div class="mb-4">
                <label for="invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                <input type="text" id="invoice_number" name="invoice_number" value="<?php echo $invoice_number; ?>" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="invoice_date" class="block text-sm font-medium text-gray-700">Invoice Date</label>
                <input type="date" id="invoice_date" name="invoice_date" value="<?php echo $invoice_date; ?>" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                <input type="text" id="customer_name" name="customer_name" value="<?php echo $customer_name; ?>" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="invoice_total" class="block text-sm font-medium text-gray-700">Invoice Total</label>
                <input type="number" id="invoice_total" name="invoice_total" value="<?php echo $invoice_total; ?>" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Invoice</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-invoice-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/الفواتير.php',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'list_الفواتير.php';
                        } else {
                            alert('Error updating invoice');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/الفواتير.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo 'Error: ID not set';
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch existing record details
$query = "SELECT * FROM الفواتير WHERE id = '$id'";
$result = $conn->query($query);

// Fetch data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'Error: No record found';
}

// Close connection
$conn->close();
?>
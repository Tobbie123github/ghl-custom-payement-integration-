<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

   
    $transactionId = $_POST['transactionId'];
    $amount = $_POST['amount'];
    $email = $_POST['email'];

    
    $apiUrl = ""; //Replace with your actual api endpoint url
    $accessToken = ''; // Replace with your actual access token

    
    $data = [
        'Identifier' => $transactionId,
        'Amount' => $amount,            
        'EmailAddress' => $email,       
        'redirectUrl' => '' 
    ];

    
    $ch = curl_init($apiUrl);

   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $accessToken, 
        'Content-Type: application/x-www-form-urlencoded'
    ]);

   
    $response = curl_exec($ch);


    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        
        $responseData = json_decode($response, true);
        
      
        if ($responseData && isset($responseData['data']['redirectionUrl'])) {
            $redirectionUrl = $responseData['data']['redirectionUrl'];
            echo '
            <style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                }
                .payment-button {
                    background-color: #4CAF50; /* Green */
                    border: none;
                    color: white;
                    padding: 15px 32px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 4px 2px;
                    cursor: pointer;
                    border-radius: 12px;
                    transition: background-color 0.3s ease;
                }
                .payment-button:hover {
                    background-color: #45a049;
                }
            </style>
            <button class="payment-button" onclick="window.location.href=\'' . $redirectionUrl . '\'">Proceed to Payment</button>';
        } else {
            echo 'Failed to get redirection URL';
        }
    }

    // Close the cURL session
    curl_close($ch);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Integration</title>
</head>
<body>
    <script>
    // This is the start of the JavaScript section
    window.addEventListener('message', function(event) {
        try {
            const data = event.data;
            let data2= JSON.parse(data)
        //    console.log(data2);

            if (data2.type === 'payment_initiate_props') {
            
                const transactionId = data2.transactionId;
                const amount = data2.amount;
                const email = data2.contact.email;

                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'payment.php';

                // Append form data
                form.appendChild(createInputElement('transactionId', transactionId));
                form.appendChild(createInputElement('amount', amount));
                form.appendChild(createInputElement('email', email));

               
                document.body.appendChild(form);
                form.submit();

                
            }
        } catch (error) {
            console.error('Error processing the message event:', error);
        }
    });

    function createInputElement(name, value) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value;
        return input;
    }

window.parent.postMessage(JSON.stringify({
  type: 'custom_provider_ready',
  loaded: true
}), '*');




    </script>
</body>
</html>

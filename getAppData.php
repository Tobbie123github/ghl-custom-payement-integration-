<?php
$configFile = 'config.json';
if (isset($configFile)) {

    if (file_exists($configFile)) {

        $configData = json_decode(file_get_contents($configFile), true);

        if ($configData === null) {
            die('Error decoding JSON in config file.');
        }

    } else {
        die('Config file not found.');
    }

    if (isset($configData)) {

        $options = [

            'requestType' => 'code',

            'redirectUri' => 'https://localhost/V2/getAccessToken.php',

            'clientId' => $configData['clientId'],

            'scopes' => [
                'products.readonly',
                'products/prices.readonly',
                'payments/transactions.readonly',
                'payments/subscriptions.readonly',
                'payments/orders.readonly',
                'payments/orders.write',
                'payments/custom-provider.readonly',
                'payments/custom-provider.write'


            ]

        ];

        $redirectUrl = $configData['baseUrl'] . '/oauth/chooselocation?' . http_build_query([
            'response_type' => $options['requestType'],
            'redirect_uri' => $options['redirectUri'],
            'client_id' => $options['clientId'],
            'scope' => implode(' ', $options['scopes'])
        ]);

        header("Location: $redirectUrl");

    } else {
        echo "ERROR: config Data is not found";
    }
} else {
    echo "ERROR: please insert config file";
}
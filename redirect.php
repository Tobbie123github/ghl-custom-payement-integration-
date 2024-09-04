<?php

$chargeId = isset($_GET['chargeId']) ? $_GET['chargeId'] : null;
$errorDescription = isset($_GET['error']) ? $_GET['error'] : null;
$isCanceled = isset($_GET['canceled']) && $_GET['canceled'] == 'true';

// Determine which event to send back to GHL based on the response

if ($chargeId) {
    // Payment was successful
    echo "<script>
            window.parent.postMessage(JSON.stringify({
                type: 'custom_element_success_response',
                chargeId: '$chargeId'
            }), '*');
          </script>";

} elseif ($errorDescription) {
    // Payment failed
    echo "<script>
            window.parent.postMessage(JSON.stringify({
                type: 'custom_element_error_response',
                error: {
                    description: '$errorDescription'
                }
            }), '*');
          </script>";

} elseif ($isCanceled) {
    // Payment was canceled by the user
    echo "<script>
            window.parent.postMessage(JSON.stringify({
                type: 'custom_element_close_response'
            }), '*');
          </script>";

} else {
    // Handle unexpected cases or missing parameters
    echo "<script>
            window.parent.postMessage(JSON.stringify({
                type: 'custom_element_error_response',
                error: {
                    description: 'Unexpected response. Please try again.'
                }
            }), '*');
          </script>";
}
?>

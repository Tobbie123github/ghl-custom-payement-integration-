# Custom Payment Integration with Premium Trust in GoHighLevel

This repository contains the custom payment integration setup for GoHighLevel (GHL) using the Premium Trust payment gateway. The integration covers retrieving access tokens, processing payments, and handling payment outcomes efficiently.


## Prerequisites

Before you begin, ensure you have the following:

- PHP 7.3 or higher
- Access to GoHighLevel (GHL) marketplace app with custom payment integration enabled
- Premium Trust API credentials (API key, Access token, etc.)


## File Structure

The repository is structured as follows:


## Installation

1. **Clone the repository:**



1. Retrieving Access Tokens
Register as a developer in gohighlevel marketplace app. Create a new app. Add your redirect URL pointing to Accesstoken.php. Add the clientid, clientsecrete, sos, in the config.json
When the getappdata.php script is executed, it redirects to getaccesstoken.php to retrieve the access token, refresh token, user ID, and other necessary user information.

getappdata.php: This script initiates the OAuth flow or retrieves the necessary app data before proceeding to payment.

getaccesstoken.php: This handles the retrieval of access and refresh tokens from Premium Trust, and saves the user info for later use.

2. Payment Initiation
payment.php: This script is responsible for initiating the payment with Premium Trust. It collects data such as transaction ID, email, and amount, and sends it to Premium Trust’s API.
3. Payment Verification
query.php: After a payment is processed, GHL sends a verification request to this script. It verifies the payment status by querying Premium Trust’s API and returns the result to GHL.
4. Payment Success/Failure Handling
redirect.php: This script handles the redirection after the payment is completed. It triggers the success or error event based on the outcome of the payment.
Handling Payment Events
Events are crucial for handling payment outcomes within the iframe. Here’s how you can handle these:

Ready Event: Triggered when the iframe is fully loaded and ready to process the payment data.

Payment Success: Upon successful payment, a custom_element_success_response event is dispatched.

Payment Failure: If the payment fails, a custom_element_error_response event is dispatched.

Payment Canceled: When the user cancels the payment, a custom_element_close_response event is dispatched.


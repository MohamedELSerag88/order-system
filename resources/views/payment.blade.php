<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
<form id="payment-form">
    <div id="card-element"></div>
    <button id="submit">Pay</button>
    <div id="payment-result"></div>
</form>

<script>
    const stripe = Stripe("pk_test_51KRL3HDkjUGwfEHHOt7AwO5bwzjMhIOIHY1UVWtF6z18QVr7nBIr7Pc2aRw7pgghL4YE69INcIcI00jI29jZ0CAA007CnOBvUu");
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            document.getElementById('payment-result').innerText = error.message;
        } else {
            // Send the token to your server
            const response = await fetch('/api/v1/payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    stripeToken: paymentMethod.id,
                    amount: 10,
                }),
            });

            const result = await response.json();
            document.getElementById('payment-result').innerText = result.status;
        }
    });
</script>
</body>
</html>

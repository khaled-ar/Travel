<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    // This is your test publishable API key.
    const stripe = Stripe("{{ config('services.stripe.publishable_key') }}");

    let elements;
    initialize();
    handleSubmit();

    let emailAddress = '';
    // Fetches a payment intent and captures the client secret
    async function initialize() {
        const {
            clientSecret
        } = "{{ $client_secret }}"

        elements = stripe.elements({
            clientSecret
        });
    }

    async function handleSubmit(e) {
        e.preventDefault();

        const {
            error
        } = await stripe.confirmPayment({
            elements,
            confirmParams: {
                // Make sure to change this to your payment completion page
                return_url: "{{ route('stripe.confirm') }}",
                receipt_email: emailAddress,
            },
        });

        // This point will only be reached if there is an immediate error when
        // confirming the payment. Otherwise, your customer will be redirected to
        // your `return_url`. For some payment methods like iDEAL, your customer will
        // be redirected to an intermediate site first to authorize the payment, then
        // redirected to the `return_url`.
        if (error.type === "card_error" || error.type === "validation_error") {
            return json(error.message);
        } else {
            return json("An unexpected error occurred.");
        }
    }
</script>

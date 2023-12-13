<form action="{{ route('booking.payment', $book) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="card-element">Credit or debit card</label>
        <div id="card-element"></div>
    </div>
    <button type="submit" class="btn btn-primary">Submit Payment</button>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
    var stripe = Stripe('{{ config('services.stripe.publishable_key') }}');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    try {

        var form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    console.log(result);
                } else {
                    // Submit payment details to API endpoint
                    fetch("{{ route('booking.payment', $book) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            stripeToken: result.token.id,
                        })
                    }).then(function(response) {
                        if (response.ok) {
                            console.log("success");
                        } else {
                            console.log("failed");
                        }
                    });
                }
            });
        });
    } catch (err) {
        console.log(err);
    }
</script>

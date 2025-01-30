<div class="container mt-3 mb-3">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card p-4">
                <h2 class="text-center mb-4">Add Funds</h2>

                @if (session()->has('success'))
                    <div class="alert alert-success text-center">
                        {{ session('success') }}
                        <a href="/"><button class="btn-sm btn-outline-success mt-2">Back Home</button></a>
                    </div>
                @elseif(session()->has('error'))
                    <div class="alert alert-danger text-center">
                        {{ session('error') }}
                        <a href="/"><button class="btn-sm btn-danger mt-2">Cancel Payment</button></a>
                    </div>
                @endif

                <form wire:submit.prevent="makePayment">
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="text" wire:model="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Enter Amount">
                        @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="cardNumber">Card Number:</label>
                        <input type="text" wire:model="cardNumber" class="form-control @error('cardNumber') is-invalid @enderror" id="cardNumber" placeholder="Enter Card Number">
                        @error('cardNumber') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>Expiration Date:</label>
                        <div class="d-flex gap-2">
                            <select class="form-control" wire:model="cardExpiryMonth">
                                <option value="" selected disabled>Month</option>
                                @foreach(range(1,12) as $month)
                                    <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}</option>
                                @endforeach
                            </select>

                            <select class="form-control" wire:model="cardExpiryYear">
                                <option value="" selected disabled>Year</option>
                                @foreach(range(date('Y'), date('Y') + 10) as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cardCVC">CVC:</label>
                        <input type="text" wire:model="cardCVC" class="form-control @error('cardCVC') is-invalid @enderror" id="cardCVC" placeholder="CVC">
                        @error('cardCVC') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5">Pay Now</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

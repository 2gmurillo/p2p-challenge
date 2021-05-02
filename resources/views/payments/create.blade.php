@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column">
        <h1 class="alert alert-primary">Total a pagar: ${{$totalAmount}} USD</h1>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif
        @error('payment_method_name', 'description')
        @enderror
        <form action="{{route('payments.store', ['total_amount' => $totalAmount])}}" method="POST"
              class="p-3 bg-dark text-white rounded">
            @csrf
            <h2 class="text-center">Seleccione el medio de pago</h2>
            <div class="form-group d-flex justify-content-center" id="toggler">
                <div class="btn-group btn-group-toggle d-flex flex-column" data-toggle="buttons">
                    @foreach (config('payment-methods') as $paymentMethod)
                        <label
                            class="btn btn-outline-secondary rounded m-2 p-1"
                            data-target="#{{ $paymentMethod['name'] }}Collapse"
                            data-toggle="collapse">
                            <input type="radio" name="payment_method_name" value="{{ $paymentMethod['name'] }}"/>
                            <img style="width: 200px" class="img-thumbnail h-100"
                                 src="{{ asset($paymentMethod['image']) }}"/>
                        </label>
                        <div id="{{ $paymentMethod['name'] }}Collapse" class="collapse" data-parent="#toggler">
                            @includeIf('components.' . strtolower($paymentMethod['name']) . '-collapse')
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="d-flex justify-content-center ">
                <button class="btn btn-success mr-1" type="submit">Pagar</button>
                <a href="/" class="btn btn-danger ml-1">Volver</a>
            </div>
        </form>
    </div>
@endsection

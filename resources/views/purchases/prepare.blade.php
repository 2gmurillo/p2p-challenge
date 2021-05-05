@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column">
        <h2 class="alert alert-success text-center">
            <span>Medio de transporte: <span class="text-uppercase text-secondary">{{$shippingMethod}}</span></span>
            <br/>
            <span class="mb-0">Total a pagar: <span class="text-secondary">${{$purchaseAmount}} USD</span></span>
        </h2>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif
        @error('payment_method', 'description')
        @enderror
        <form
            action="{{route('purchases.pay', ['purchase_amount' => $purchaseAmount, 'shipping_method' => $shippingMethod])}}"
            method="POST"
            class="p-3 bg-dark text-white rounded">
            @csrf
            <h2 class="text-center">Seleccione el medio de pago</h2>
            <div class="form-group d-flex justify-content-center" id="toggler">
                <div class="btn-group btn-group-toggle d-flex flex-column" data-toggle="buttons">
                    @foreach (config('payment-methods') as $paymentMethodName => $paymentMethodValues)
                        <label
                            class="btn btn-outline-secondary rounded m-2 p-1"
                            data-target="#{{ $paymentMethodName }}Collapse"
                            data-toggle="collapse">
                            <input type="radio" name="payment_method" value="{{ $paymentMethodName }}"/>
                            <img style="width: 200px" class="img-thumbnail h-100"
                                 src="{{ asset($paymentMethodValues['image']) }}" alt="{{ $paymentMethodName }}"/>
                        </label>
                        <div id="{{ $paymentMethodName }}Collapse" class="collapse" data-parent="#toggler">
                            @includeIf('components.' . strtolower($paymentMethodName) . '-collapse')
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="d-flex justify-content-center ">
                <button class="btn btn-success mr-1" type="submit">Pagar</button>
                <a href="javascript:history.back()" class="btn btn-danger ml-1">Volver</a>
            </div>
        </form>
    </div>
@endsection

@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column">
        <form style="min-width: 300px" action="{{route('payments.prepare')}}" method="GET"
              class="p-3 bg-dark text-white rounded">
            @csrf
            <div class="form-group">
                <label for="formGroupExampleInput">Precio de los productos (USD)</label>
                <input name="amount" type="number" class="form-control" id="formGroupExampleInput"
                       placeholder="Dólares (USD)" step="0.1" value="{{old('amount', 12)}}"/>
                @error('amount')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput3">Peso de los productos (kg)</label>
                <input name="distance" type="number" class="form-control" id="formGroupExampleInput3"
                       placeholder="Kilogramos (kg)" step="0.1" value="{{old('distance', 4)}}"/>
                @error('distance')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="formGroupExampleInput2">Distancia del envío (km)</label>
                <input name="weight" type="number" class="form-control" id="formGroupExampleInput2"
                       placeholder="Kilómetros (km)" step="0.1" value="{{old('weight', 5)}}"/>
                @error('weight')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <fieldset class="form-group d-flex flex-column">
                <label>Medio de transporte</label>
                <div>
                    @foreach(\App\Http\Controllers\Concerns\Constants\ShippingMethods::toArray() as $shippingMethod)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="shipping_method"
                                   value="{{ $shippingMethod }}"
                                   @if(\App\Http\Controllers\Concerns\Constants\ShippingMethods::BASIC === $shippingMethod) checked @endif/>
                            <label class="form-check-label text-capitalize">
                                {{$shippingMethod}}
                            </label>
                        </div>
                    @endforeach
                </div>
            </fieldset>
            <button class="btn btn-success d-flex mx-auto" type="submit">Ir al proceso de pago</button>
        </form>
    </div>
@endsection

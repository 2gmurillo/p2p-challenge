@extends('layouts.app')
@section('content')
    <div class="d-flex flex-column">
        <h1 class="alert alert-danger">Lo sentimos, no encontramos lo que estás buscando</h1>
        <a href="{{route('welcome')}}" class="btn btn-success d-flex mx-auto">Volver al inicio</a>
    </div>
@endsection

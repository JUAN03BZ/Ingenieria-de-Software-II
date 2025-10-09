@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Clientes</h2>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary mb-3">Agregar Nuevo Cliente</a>
    <div class="alert alert-info">
        <p>No hay clientes registrados aún. Crea uno desde el botón arriba.</p>
    </div>
    {{-- Aquí iría una tabla con @foreach($clientes as $cliente) cuando tengas datos --}}
</div>
@endsection
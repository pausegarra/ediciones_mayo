@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>Textos</h2>
            </div>
            <div class="col text-end">
                <text-form></text-form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <text-list></text-list>
                </div>
            </div>
        </div>
    </div>
@endsection
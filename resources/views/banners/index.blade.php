@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>Banners</h2>
            </div>
            <div class="col text-end">
                <banner-form></banner-form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <banner-list></banner-list>
                </div>
            </div>
        </div>
    </div>
@endsection
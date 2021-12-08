@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>Sub Banners</h2>
            </div>
            <div class="col text-end">
                <subbanner-form></subbanner-form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <subbanner-list></subbanner-list>
                </div>
            </div>
        </div>
    </div>
@endsection
@extends('errors.error.master')
@section('title', 'Withdrawal Error')
@section('content')
    <section class="container">
        <div class="d-flex justify-contents-center align-items-center" style="height: 100vh">
            <div class="col-sm-12 col-md-6 align-self-center">
                <h2>Error!</h2>
                <div>You are not allowed to withdraw more than your allowed limit.</div>
                <a href="/" class="btn btn-dark">go back to home </a>
            </div>
        </div>
    </section>
@endsection

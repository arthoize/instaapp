@extends('layouts.app')

@section('css')
<style>
    .like-btn:hover {
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    @if($user->isNotEmpty())
        @foreach($user as $x)
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2 row">
                        <div class="col-2">
                            <img src="https://ui-avatars.com/api/?name={{ $x->name }}" alt="" class="w-100 rounded-circle">
                        </div>
                        <div class="col-10">
                            <h4 class="mb-0">
                                <a href="{{ url('user') . '/' . $x->id }}">{{ $x->name }}</a>
                                @if($x->is_private ==1)
                                <span><i class="fa fa-lock"></i></span>
                                @endif
                            </h4>
                            <p>{{ '@'.$x->username }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                <h4>User tidak ditemukan</h4>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
@endsection


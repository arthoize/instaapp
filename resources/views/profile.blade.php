@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-2">
            <a href="{{ url('user') . '/' . $user->id }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profile</div>
                <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                    <form action="{{ url('profile/save') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
                        </div>
                        <div class="form-group">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" value="{{ $user->username }}">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="text" class="form-control" name="email" id="email" value="{{ $user->email }}">
                        </div>
                        <div class="form-group">
                            <label for="profile_description" class="form-label">Bio</label>
                            <input type="text" class="form-control" name="profile_description" id="profile_description" value="{{ $user->profile_description }}">
                        </div>
                        <div class="form-group">
                            <label for="is_private" class="form-label">Kunci Akun</label>
                            <select name="is_private" id="is_private" class="form-control">
                                <option value="1" @if($user->is_private == 1) {{'selected'}} @endif>Ya</option>
                                <option value="0" @if($user->is_private == "0") {{'selected'}} @endif>Tidak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary w-100">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
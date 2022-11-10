@extends('layouts.app')

@section('css')
<style>
    .photo-profile {
        max-width: 100px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-2">
        <a href="{{ url('/') }}" class="btn btn-primary">Kembali</a>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Post</div>
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
                    <form action="{{ url('post/save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            @if(isset($post) && $post->photo)
                            <div>
                                <img src="{{ asset('pictures/post') }}/{{ $post->photo }}" class="photo-profile">
                            </div>
                            @endif
                            <div>
                                <div class="form-group">
                                    <img id="uploaded_photo" src="#" alt="your image" style="display: none" class="w-100"/>
                                    <input type="file" name="photo" id="photo" class="file-form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="caption" class="form-label">Caption</label>
                            <input type="text" class="form-control" name="caption" id="caption" value="{{ $post->caption ?? null }}">
                            <input type="hidden" class="form-control" name="id" id="id" value="{{ $post->id ?? null }}">
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

@section('js')
<script>
    function readURL(input) {
        console.log(input.id);
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#uploaded_photo').attr('src', e.target.result);
                $('#uploaded_photo').css('display', 'block');
            }
            
            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#photo").change(function() {
        readURL(this);
    });
    
</script>
@endsection
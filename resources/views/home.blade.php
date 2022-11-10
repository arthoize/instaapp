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
        <div class="col-md-8 mb-2">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-success">
                {{ session('error') }}
            </div>
        @endif
        </div>
    @auth
        <div class="col-md-8 mb-4">
            <a href="{{ url('post') }}" class="btn btn-primary shadow w-100"><i class="fa fa-paper-plane"></i> Buat Postingan</a>
        </div>
    @endauth
    @if($post->isNotEmpty())
        @foreach($post as $x)
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2 row">
                        <div class="col-2">
                            <img src="https://ui-avatars.com/api/?name={{ $x->name }}" alt="" class="w-100 rounded-circle">
                        </div>
                        <div class="col-10">
                            <div class="row">
                                <div class="col-8">
                                    <h4 class="mb-0">
                                        <a href="{{ url('user') . '/' . $x->user_id }}">{{ $x->name }}</a>
                                        @if($x->is_private == 1)
                                        <span><i class="fa fa-lock"></i></span>
                                        @endif
                                    </h4>
                                    <p>{{ '@'.$x->username }}</p>
                                </div>
                                <div class="col-4">
                                    <span class="float-right text-muted" id="date-post-{{ $x->id }}"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3 shadow">
                        <img src="{{ asset('pictures/post') . '/' . $x->photo }}" alt="" class="image w-100">
                    </div>
                    <div class="mb-3">
                        @auth
                            <span class="like-btn" data-id="{{$x->id}}" >
                            @if( $x->like->isNotEmpty() && $x->like->where('user_id', Auth::user()->id)->count() > 0 )
                                <i class="fa fa-heart text-danger fa-2x mx-2"></i>
                            @else
                                <i class="far fa-heart fa-2x mx-2"></i>
                            @endauth
                            </span>
                        @else
                        @endif
                        <p><b><span id="like-{{$x->id}}">{{ $x->like->count() }}</span> Like(s)</b></p>
                    </div>
                    <!-- Caption & Comments -->
                    <div>
                        <a href="{{ url('user') . '/' . $x->user_id }}"><h5><b>{{ $x->name }}</b></h5></a>
                        <p>{{ $x->caption }}</p>
                        <hr>
                        <small>
                            <ul class="list-comment-{{$x->id}}">
                            @if($x->comment->isNotEmpty())
                                @foreach($x->comment->sortBy('created_at') as $c)
                                    <li><b>{{ $c->user->name }}</b> - {{ $c->text }}</li>
                                @endforeach
                            @endif
                            </ul>
                        </small>
                    </div>

                    @guest
                    <a href="{{ url('login') }}" class="btn btn-primary">Silahkan login untuk berkomentar</a>
                    @else
                        <form action="{{ url('comment/save') . '/' . $x->id }}" data-id="{{$x->id}}" class="form-submit" method="POST">
                            <div class="form-group">
                                @csrf
                                <input type="text" class="form-control" name="comment" placeholder="Tulis komentar...">
                            </div>
                            <button type="submit" class="btn btn-primary btn-submit-comment"><span class="loading" style="display: none;"><i class="fas fa-spinner fa-spin"></i></span> Kirim Komentar</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                <h4>Belum ada postingan</h4>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
@endsection

@section('js')
<script>
    moment.locale('id');

    // POST DATE
    var posts = <?= json_encode($post) ?>;

    console.log('posts',posts)
    if(posts){
        posts.forEach(function(e){
            // var post_date = moment(e.created_at, 'YYYY-MM-DD HH:mm:ss').format('dddd, Do MMMM YYYY');
            var post_date = moment(e.tgl_post, 'YYYY-MM-DD HH:mm:ss').fromNow();
            console.log(e.tgl_post, post_date);
            $('#date-post-' + e.id).html(post_date);
        })
    }

    $('.like-btn').click(function(e){
        $(this).toggle('hide');
        $(this).find('i').toggleClass('fa far');
        var id = $(this).attr('data-id');
        console.log(id);
        var el = $(this);
        $.ajax({
            url: '{{ url("like") }}' + '/' + id, 
            success: function(result){
                console.log(result);
                el.find('i').toggleClass('text-danger text-black');
                el.toggle('show');
                $('#like-' + id).html(result.like);
                alert(result.message);
            }
        })
    });

    // SUBMIT
    $(".form-submit").submit(function(e) {
        e.preventDefault();

        var form = $(this);
        var url = form.attr('action');
        var id = form.attr('data-id');
        var comment = form.find('input[name=comment]').val();

        form.find('.loading').css('display', 'inline');
        form.find('.btn-submit-comment').attr('disabled', 'disabled');
        
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(data)
            {
                if(data.success){
                    $('.list-comment-' + id).append('<li><b>'+data.data.user+'</b> - '+data.data.comment+'</li>');

                    alert(data.message);
                } else {
                    alert(data.message);
                }
            }, 
            complete: function(){
                form.find('input[name=comment]').val('');
                form.find('.loading').css('display', 'none');
                form.find('.btn-submit-comment').removeAttr('disabled', 'disabled');
            }
        });

    });

</script>
@endsection

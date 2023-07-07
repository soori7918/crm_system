
<div id="messageContainer">
    @if((session('toast') == true) || ($toast ?? false))
        @if (session('success'))
            @include('components.toast',[
                'title' => 'پیام موفقیت',
                'type' => 'success',
                'message' => session('success')
            ])
        @endif
        @if ($success ?? false)
            @include('components.toast',[
                'title' => 'پیام موفقیت',
                'type' => 'success',
                'message' => $success
            ])
        @endif
        @if (count($errors->all()))
            @include('components.toast',[
                'title' => 'پیام خطا',
                'type' => 'danger',
                'messages' => $errors->all()
            ])
        @endif
    @else
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if ($success ?? false)
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $success }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('danger') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($danger ?? false)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $danger }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($warning ?? false)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ $warning }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (count($errors->all()))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="list-unstyled m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @endif
</div>






    


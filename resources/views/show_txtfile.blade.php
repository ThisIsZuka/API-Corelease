@extends('layout.home')

@section('content')
    <div class="container">
        <div class="row mt-3">
            <h1>NCB RAWFILES</h1>
        </div>
        <div class="row mb-3">

        </div>
        @foreach ($files as $keys => $items)
            <div class="row">
            @foreach ($items as $key => $item)
                <div class="col">
                    <div class="btn download-btn" data-filepath="{{$item->getFileInfo()}}" alt='dowload file'>
                        <img src="{{URL::asset('/images/txtfile-icon.png')}}" alt='text file' height='auto' width="50%"/>
                        <p id='file-label-{{$keys + $key}}' class='file-label'>{{$item->getFilename()}}</p>
                    </div>
                </div>
            @endforeach
            </div>
        @endforeach
    </div>
    <script>
        $(document).ready(function () {
            $('div.download-btn').click(function () {
                //download file
                window.location.replace('/download?path=' + $(this).data('filepath'));
            });
        });
    </script>
@endsection
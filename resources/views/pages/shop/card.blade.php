<!DOCTYPE html>
    <html lang="en">

    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Itim&family=Manuale:ital,wght@0,300..800;1,300..800&family=Margarine&display=swap"
            rel="stylesheet">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- navbar -->
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Delius+Unicase&family=Rancho&display=swap"
            rel="stylesheet">
        <link
            href="https://fonts.googleapis.com/css2?family=Radley:ital@0;1&family=Rancho&family=Ravi+Prakash&family=Recursive:wght@300&family=Redressed&family=Reggae+One&display=swap"
            rel="stylesheet">
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <title>ChocoScript</title>

        <link
            href="https://fonts.googleapis.com/css2?family=Rancho&family=Ravi+Prakash&family=Recursive:wght@300&family=Redressed&family=Reggae+One&display=swap"
            rel="stylesheet">

        <link rel="stylesheet" href="../../css/navbar.css">
        <link rel="stylesheet" href="../../css/card/card.css">

        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> --}}
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body>
      
        @include('navbar.nav')

        <div class="container">
            <div class="container1">
                <p class="container1-titile">
                    {{$barang->title}}
                </p>
                <div id="main">
                    <div class="container0-1">
                        <img id="image" src="{{ asset('images/' . $barang->img) }}" alt="{{ $barang->title }}"
                            class="img-fluid">
                        {{-- <img src="../img/shop/Chocolate.png" id="image" /> --}}
                    </div>
                    <div id="container">
                        <div id="title">
                            <p>IDR {{$barang->harga}} K</p>
                            <p id="card-deskripsi">{{$barang->deskripsi}}</p>
                        </div>
                    </div>
                </div>
                <div class="container2">
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                        <div class="form-group">
                            <label class="jumlah_barang">jumlah barang</label>
                            <input type="number" name="quantity" class="input_barang" id="quantity" class="form-control"
                                min="1" required>
                        </div>
                        <div class="card-custom-btn1">
                            <button class=" card-custom-btn card-btn-12"><span>Click!</span><span>tambah Cart</span></button>
                        </div>
                        {{-- <button type="submit" class="btnbtn-primary">tambah Cart</button> --}}
                    </form>
                </div>
            </div>
    </body>

    </html>
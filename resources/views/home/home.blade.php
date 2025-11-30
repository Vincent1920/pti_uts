 @extends('index')
    @section('container-home')
    <div class="container ">
        <img src="img/Hero_Web_desk_2024_19 1.jpg" alt="">

        <div class="container1">
            <p>
                Premium Chocolate
            </p>

            <h3>
                Packed with natural ingredients, 30% protein and no artificial sweeteners
            </h3>
            <div class="container2">
                <div class="container1-shop ">
                    <button class="index_custom-btn index_btn-15">
                        <a class="bob" href="{{route('shop')}}">


                            <h2 class="Redressed">
                                Shop Here
                            </h2>
                        </a>
                    </button>
                </div>
                <div class="container1-shop1 ">
                    <button class="index1_custom-btn index1_btn-15">
                        <a href="{{route('learn')}}" class="bob1 bob">
                            <h2 class="Redressed">
                                learn here
                            </h2>
                        </a>
                    </button>

                </div>
            </div>
        </div>
    </div>

    <br />
    <div class="content-text">

        <h1 class="content-text-text-text">
            protein alami
        </h1>
    </div>
    <br />

    <div class="container-ke2">
        <div class="container_ke2-card">
            <div class="container_ke2-card1">
                            <img src="../img/navbar/Chocolate.png" alt="">

            </div>
            <br />
            <b>
                <p>IDR 10.000</p>
            </b>
            <br />
            <p> Baratti Milano</p>
        </div>
        <div class="container_ke2-card">
            <div class="container_ke2-card1 container_ke2-card2 ">
                                 <img src="../img/navbar/dark_22.png" alt="">

            </div>
            <br />
            <b>
                <p>IDR 80.500</p>
            </b>
            <br />
            <p> Dark Choco</p>
        </div>
        <div class="container_ke2-card">
            <a href="">

                <div class="container_ke2-card3">
                                <img src="../img/navbar/Tongan_vanilla.png" alt="">

                </div>
            </a>
            <br />
            <b>
                <p>IDR 43.200</p>
            </b>
            <br />
            <p> Milk Chocolate Single Bar</p>
        </div>
    </div>
    <div class="container-ke3">
        <br>
        <br>
        <h1>
            Semuanya alami
        </h1>
        <br>
        <p>
            Di ChocoScript kami hanya menggunakan bahan-bahan alami, dan kami jauh melampaui bubuk sintetis dan aditif
            buatan. Kami mungkin sedikit pilih-pilih dalam hal bahan-bahan kami. Kami hanya menggunakan alam terbaik
            yang tersedia - rasa dan kualitas berarti segalanya bagi kami. Selalu. Kami menghabiskan banyak waktu untuk
            menemukan rasa baru dan menarik, dan kami akan berusaha keras untuk pilihan inovatif baru, tetapi kami tidak
            pernah berkompromi dengan selera yang baik. Ini adalah cinta kami untuk
        </p>

    </div>

    <div class="container-ke4">
        <br>
        <h1>Hadiah untuk setiap kesempatan</h1>
        <br>
        <div class="container-ke4_dispay">
            <div class="container-ke4_card">
                <img src="img/shop/hersheys-removebg-preview.png" alt="">
                <div class="container-ke4_card1">
                    <a class="bob" href="{{route('shop')}}">

                        <p>Shop Here</p>
                    </a>

                </div>
            </div>
            <div class="container-ke4_card">
                <img src="img/shop/delivered_nationwide-removebg-preview.png" alt="">
                <div class="container-ke4_card1">
                    <a class="bob" href="{{route('shop')}}">

                        <p>Shop Here</p>
                    </a>

                </div>
            </div>
            <div class="container-ke4_card">
                <img src="img/shop/candy_boxes-removebg-preview.png" alt="">
                <div class="container-ke4_card1">
                    <a class="bob" href="{{route('shop')}}">

                        <p>Shop Here</p>
                    </a>

                </div>
            </div>
        </div>
    </div>

    @endsection
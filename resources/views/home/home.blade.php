@extends('index')

@section('container-home')

<style>
    @keyframes slideInLeft {
        0% { transform: translateX(-90%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }
    .animate-slideInLeft {
        animation: slideInLeft 1s ease-out forwards;
    }
</style>

<div class="relative w-full h-[600px] overflow-hidden bg-[#9c725b] flex">
    <img src="img/Hero_Web_desk_2024_19 1.jpg" alt="" class="w-full h-full object-cover">

    <div class="absolute top-[15%] left-[5%] w-1/2 h-[250px] animate-slideInLeft z-10">
        <p class="mt-2.5 ml-[60px] font-['Rancho'] text-[60px] text-white leading-tight">
            Premium Chocolate
        </p>

        <h3 class="ml-2.5 font-['Rancho'] text-[20px] font-light text-white">
            Packed with natural ingredients, 30% protein and no artificial sweeteners
        </h3>

        <div class="flex mt-[30px] ml-[40px] gap-5">
            
            <div class="border-2 border-[#ededed] rounded-xl transform transition duration-900 origin-top hover:scale-100">
                <button class="group relative w-[130px] h-[44px] font-['Lato'] font-medium rounded-[10px] bg-transparent overflow-hidden transition-all duration-300 outline-none">
                    <a href="{{route('shop')}}" class="block w-full h-full relative z-20 flex items-center justify-center text-decoration-none">
                        <span class="absolute inset-0 w-0 bg-[#ededed] transition-all duration-300 ease-out group-hover:w-full -z-10"></span>
                        
                        <h2 class="font-['Redressed'] text-white group-hover:text-[#7F4D3E] text-xl transition-colors duration-300">
                            Shop Here
                        </h2>
                    </a>
                </button>
            </div>

            <div class="border-2 border-[#3C2622] rounded-xl transform transition duration-900 origin-top hover:bg-transparent">
                <button class="group relative w-[130px] h-[44px] font-['Lato'] font-medium rounded-[10px] bg-transparent overflow-hidden transition-all duration-300 outline-none">
                    <a href="{{route('learn')}}" class="block w-full h-full relative z-20 flex items-center justify-center text-decoration-none">
                         <span class="absolute inset-0 w-0 bg-[#3C2622] transition-all duration-300 ease-out group-hover:w-full -z-10"></span>

                        <h2 class="font-['Redressed'] text-white group-hover:text-[#ededed] text-xl transition-colors duration-300">
                            learn here
                        </h2>
                    </a>
                </button>
            </div>
        </div>
    </div>
</div>

<br />

<div class="w-full h-[100px] flex items-center justify-center">
    <h1 class="font-['Rancho'] uppercase text-4xl sm:text-6xl text-transparent bg-clip-text bg-gradient-to-t from-[#B15716] via-[#CF9877] to-[#CF9877]">
        protein alami
    </h1>
</div>

<br />

<div class="w-full min-h-[450px] flex flex-wrap justify-around p-12 gap-8">
    
    <div class="w-full md:w-[30%] h-[390px] flex flex-col items-center">
        <div class="w-full h-[75%] bg-[#f0f8ff] flex justify-center items-center overflow-hidden">
            <img src="../img/navbar/Chocolate.png" alt="" class="w-full h-full object-cover">
        </div>
        <div class="mt-4 text-center">
            <b><p class="font-['Rancho'] text-[1.5rem] text-[#584123]">IDR 10.000</p></b>
            <p class="font-['Rancho'] text-[1.5rem] text-[#584123]">Baratti Milano</p>
        </div>
    </div>

    <div class="w-full md:w-[30%] h-[390px] flex flex-col items-center">
        <div class="w-full h-[75%] bg-[#f0f8ff] flex justify-center items-center overflow-hidden">
            <img src="../img/navbar/dark_22.png" alt="" class="w-[90%] h-[90%] object-cover">
        </div>
        <div class="mt-4 text-center">
            <b><p class="font-['Rancho'] text-[1.5rem] text-[#584123]">IDR 80.500</p></b>
            <p class="font-['Rancho'] text-[1.5rem] text-[#584123]">Dark Choco</p>
        </div>
    </div>

    <div class="w-full md:w-[30%] h-[390px] flex flex-col items-center">
        <a href="" class="w-full h-[75%] block">
            <div class="w-full h-full bg-[#f0f8ff] flex justify-center items-center">
                <img src="../img/navbar/Tongan_vanilla.png" alt="" class="h-[90%] w-[70%] object-contain">
            </div>
        </a>
        <div class="mt-4 text-center">
            <b><p class="font-['Rancho'] text-[1.5rem] text-[#584123]">IDR 43.200</p></b>
            <p class="font-['Rancho'] text-[1.5rem] text-[#584123]">Milk Chocolate Single Bar</p>
        </div>
    </div>

</div>

<div class="w-full h-auto py-12 text-center">
    <h1 class="text-[#DDBEA9] font-['Rancho'] text-[3rem] mb-6">
        Semuanya alami
    </h1>
    <p class="font-['Rancho'] text-[2rem] text-[#Cb997E] w-[80%] mx-auto leading-relaxed">
        Di ChocoScript kami hanya menggunakan bahan-bahan alami, dan kami jauh melampaui bubuk sintetis dan aditif
        buatan. Kami mungkin sedikit pilih-pilih dalam hal bahan-bahan kami. Kami hanya menggunakan alam terbaik
        yang tersedia - rasa dan kualitas berarti segalanya bagi kami. Selalu. Kami menghabiskan banyak waktu untuk
        menemukan rasa baru dan menarik, dan kami akan berusaha keras untuk pilihan inovatif baru, tetapi kami tidak
        pernah berkompromi dengan selera yang baik. Ini adalah cinta kami untuk
    </p>
</div>

<div class="w-full h-[500px] bg-[#efefef] font-['Rancho'] py-8">
    <h1 class="text-[#7F4D3E] text-center text-4xl mb-8">Hadiah untuk setiap kesempatan</h1>
    
    <div class="h-full flex justify-around items-center">
        
        <div class="relative w-[400px] h-[80%] flex justify-center group">
            <img src="../img/index/orange_milk_chocolate-removebg-preview.png" alt="" class="object-contain h-full w-full">
            <div class="absolute top-[35%] border-2 border-[#f4f4f4] w-[150px] h-[50px] flex items-center justify-center rounded-[10%] cursor-pointer transition-colors duration-300 hover:bg-[#daca bf]/60 bg-white/20 backdrop-blur-sm">
                <a class="no-underline" href="{{route('shop')}}">
                    <p class="text-[2rem] text-[#934b18]">Shop Here</p>
                </a>
            </div>
        </div>

        <div class="relative w-[400px] h-[80%] flex justify-center group">
            <img src="../img/navbar/Chocolate.png"alt="" class="object-contain h-full w-full">
            <div class="absolute top-[35%] border-2 border-[#f4f4f4] w-[150px] h-[50px] flex items-center justify-center rounded-[10%] cursor-pointer transition-colors duration-300 hover:bg-[#dacabf]/60 bg-white/20 backdrop-blur-sm">
                <a class="no-underline" href="{{route('shop')}}">
                    <p class="text-[2rem] text-[#934b18]">Shop Here</p>
                </a>
            </div>
        </div>

        <div class="relative w-[400px] h-[80%] flex justify-center group">
            <img src="img/index/chocolate_maker-removebg-preview.png" alt="" class="object-contain h-full w-full">
            <div class="absolute top-[35%] border-2 border-[#f4f4f4] w-[150px] h-[50px] flex items-center justify-center rounded-[10%] cursor-pointer transition-colors duration-300 hover:bg-[#dacabf]/60 bg-white/20 backdrop-blur-sm">
                <a class="no-underline" href="{{route('shop')}}">
                    <p class="text-[2rem] text-[#934b18]">Shop Here</p>
                </a>
            </div>
        </div>

    </div>
</div>

@endsection
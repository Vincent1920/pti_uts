@extends('index')

@section('container-home')

{{-- DATA MAHASISWA (Hardcode) --}}
@php
    $team_members = [
        [
            'name' => 'Vincent Luhulima',
            'nim'  => '10123309',
            'img'  => 'vincent.png',
            'percent' => 100
        ],
        [
            'name' => 'Salsabila Nurhaliza',
            'nim'  => '10123306',
            'img'  => 'caca.jpg',
            'percent' => 100
        ],
        [
            'name' => 'Aditya Khoerul Tammi',
            'nim'  => '10123310',
            'img'  => 'adet.jpg',
            'percent' => 100
        ],
        [
            'name' => 'Abdul Malik Febrian Zulkifli',
            'nim'  => '10123308',
            'img'  => 'rian.jpg',
            'percent' => 100
        ],
        [
            'name' => 'Alfa Riza Maftu Eka Sakti',
            'nim'  => '10123327',
            'img'  => 'fais.jpg',
            'percent' => 100
        ]
    ];
@endphp

<style>
    @keyframes loadBar {
        from { width: 0; }
        to { width: var(--width); }
    }
    .animate-bar {
        animation: loadBar 1.5s ease-out forwards;
    }
</style>

{{-- HERO SECTION --}}
<div class="relative w-full h-[400px] bg-gray-800 flex justify-center items-center overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-40"></div>
    <div class="relative z-10 text-center p-6">
        <h2 class="text-[#F8DEC3] font-['Ribeye_Marrow',serif] text-6xl animate-pulse drop-shadow-lg tracking-wide mb-4">
            Meet the Experts
        </h2>
        <p class="text-white font-['Quintessential',cursive] text-2xl">
            Tim Hebat di Balik ChocoScript
        </p>
    </div>
</div>

{{-- CONTENT SECTION --}}
<div class="container mx-auto px-4 py-16 max-w-7xl">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 justify-items-center">

        @foreach($team_members as $member)
        <div class="w-full max-w-sm group">

            <div class="relative bg-white rounded-[20px] shadow-lg border border-[#e4a877]/30 
                        overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 
                        p-6 flex flex-col items-center cursor-pointer pointer-events-auto">

                {{-- Foto Profil --}}
                <div class="w-40 h-40 rounded-full border-4 border-[#F8DEC3] shadow-md overflow-hidden mb-6">
                    <img 
                        onclick="openPhoto('{{ asset('aboutAS/' . $member['img']) }}')"
                        class="w-full h-full object-cover object-center cursor-pointer 
                               group-hover:scale-110 transition-transform duration-500 pointer-events-auto"
                        src="{{ asset('aboutAS/' . $member['img']) }}" 
                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($member['name']) }}&background=F8DEC3&color=a44b03'"
                        alt="{{ $member['name'] }}">
                </div>

                {{-- Nama & NIM --}}
                <div class="text-center w-full mb-6">
                    <h3 class="text-[#5c4033] font-['Rancho'] text-3xl font-bold mb-1 leading-tight">
                        {{ $member['name'] }}
                    </h3>
                    <span class="inline-block px-4 py-1 bg-[#F8DEC3]/40 text-[#a44b03] rounded-full text-lg font-['Lato'] font-bold">
                        {{ $member['nim'] }}
                    </span>
                </div>

                {{-- Bar Kontribusi --}}
                <div class="w-full mt-auto">
                    <div class="flex justify-between items-end mb-1">
                        <span class="text-sm font-bold text-gray-500 uppercase tracking-wider">Kontribusi</span>
                        <span class="text-lg font-bold text-[#DD751F]">{{ $member['percent'] }}%</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-[#DD751F] to-[#a44b03] h-4 rounded-full animate-bar" 
                             style="--width: {{ $member['percent'] }}%; width: 0;"></div>
                    </div>
                </div>

            </div>

        </div>
        @endforeach

    </div>
</div>

{{-- FOTO POPUP MODAL --}}
<div id="photoModal" 
     class="fixed inset-0 bg-black/70 hidden z-50 flex justify-center items-center p-4">

    <div class="relative max-w-full max-h-full flex justify-center items-center">

        <img id="photoPreview" 
             class="max-w-[90vw] max-h-[90vh] object-contain rounded-xl shadow-2xl">

    </div>
</div>

<script>
function openPhoto(src) {
    const modal = document.getElementById('photoModal');
    const preview = document.getElementById('photoPreview');

    preview.src = src;
    modal.classList.remove('hidden');
}

document.getElementById('photoModal').addEventListener('click', () => {
    document.getElementById('photoModal').classList.add('hidden');
});
</script>


@endsection

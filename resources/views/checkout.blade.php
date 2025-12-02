@extends('index')

@section('container-home')

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        choco: '#463126',
                        choco_light: '#654e42',
                        cream: '#f3eae5', // Background utama
                        gold: '#c88a5b',
                        alice: '#f0f8ff',
                    },
                    fontFamily: {
                        qwigley: ['Qwigley', 'cursive'],
                        kotta: ['Kotta One', 'serif'],
                        manuale: ['Manuale', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #463126; }
    </style>

    <div class="w-full min-h-screen bg-cream flex justify-center items-center py-10 px-4">

        <div class="flex flex-col lg:flex-row w-full max-w-6xl bg-white shadow-2xl rounded-xl overflow-hidden">
            
            <div class="w-full lg:w-[58%] bg-white px-6 py-8 lg:px-12 lg:py-12 order-2 lg:order-1 border-r border-gray-200">
{{-- 
                <div class="mb-8 text-center lg:text-left">
                    <a href="/" class="text-5xl font-qwigley text-black hover:text-choco transition">ChocoScript</a>
                </div> --}}

                <div class="mb-8">
                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <h2 class="text-xl font-kotta text-gray-900 mb-4 border-b pb-2">Alamat Pengiriman</h2>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 ml-1">Nama Penerima</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition" required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 ml-1">Email</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm bg-gray-50" readonly>
                                </div>
                                <div class="relative">
                                    <input type="text" name="address" placeholder="Alamat Lengkap" class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition" required>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="postal_code" placeholder="Kode Pos" class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition" required>
                                    <input type="text" name="city" placeholder="Kota / Kabupaten" class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition" required>
                                </div>
                                <div class="relative">
                                    <input type="tel" name="phone" placeholder="Telepon" class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition" required>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <h2 class="text-xl font-kotta text-gray-900 mb-4 border-b pb-2">Metode Pembayaran</h2>
                            <div class="space-y-3">
                                <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-alice transition has-[:checked]:border-choco has-[:checked]:bg-alice">
                                    <input type="radio" name="payment_method" value="bank_transfer" class="mt-1 accent-choco" onchange="togglePayment('bank')" required>
                                    <div class="ml-3">
                                        <span class="block font-bold text-gray-800">Transfer Bank</span>
                                        <span class="text-xs text-gray-500">Upload bukti transfer manual</span>
                                    </div>
                                </label>

                                <div id="bank-info" class="hidden ml-8 p-4 bg-gray-50 rounded-md border border-gray-200 text-sm">
                                    <p class="font-bold text-gray-700 mb-2">Silakan transfer ke:</p>
                                    <ul class="list-disc pl-4 mb-4 text-gray-600">
                                        <li><strong>BCA:</strong> 123-456-7890 (a.n ChocoScript)</li>
                                        <li><strong>Mandiri:</strong> 098-765-4321 (a.n ChocoScript)</li>
                                    </ul>
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Upload Bukti Transfer</label>
                                    <input type="file" name="payment_proof" id="proof-input" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-choco file:text-white hover:file:bg-choco_light cursor-pointer">
                                    <p class="text-[10px] text-red-500 mt-1">* Wajib upload foto (jpg/png)</p>
                                </div>

                                <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-alice transition has-[:checked]:border-choco has-[:checked]:bg-alice">
                                    <input type="radio" name="payment_method" value="cod" class="mt-1 accent-choco" onchange="togglePayment('cod')">
                                    <div class="ml-3">
                                        <span class="block font-bold text-gray-800">Cash on Delivery (COD)</span>
                                        <span class="text-xs text-gray-500">Bayar ditempat saat kurir datang</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-between items-center gap-4 mt-8 pt-4 border-t">
                            <a href="{{ route('cart') }}" class="text-choco hover:text-gray-800 text-sm flex items-center transition">
                                <i class="bi bi-chevron-left text-xs mr-2"></i> Kembali
                            </a>
                            <button type="submit" class="w-full sm:w-auto bg-choco hover:bg-choco_light text-white font-bold py-3 px-8 rounded-md transition duration-300 text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="w-full lg:w-[42%] bg-gray-50 border-l border-gray-200 px-6 py-8 lg:px-10 lg:py-12 order-1 lg:order-2">
                <div class="sticky top-6"> <h2 class="text-lg font-bold text-gray-800 mb-6 lg:hidden">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-4 mb-6 pb-6 border-b border-gray-200 max-h-[400px] overflow-y-auto pr-2">
                        @foreach($cartItems as $item)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="relative border border-gray-200 rounded-lg w-16 h-16 bg-white p-1 shadow-sm">
                                    <img src="{{ asset('images/' . $item->barang->img) }}" alt="Product" class="w-full h-full object-cover rounded-md">
                                    <span class="absolute -top-0 -right-2 bg-gray-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full shadow-md">
                                        {{ $item->quantity }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-sm group-hover:text-choco transition">{{ $item->barang->title }}</h3>
                                    <p class="text-xs text-gray-500">{{ $item->barang->berat_barang }}g</p>
                                </div>
                            </div>
                            <div class="text-sm font-medium text-gray-800">
                                IDR {{ number_format($item->barang->harga * $item->quantity, 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-2 pb-6 border-b border-gray-200 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-medium text-gray-900">IDR {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        @if($discountAmount > 0)
                        <div class="flex justify-between text-green-700">
                            <span>Diskon ({{ $namaDiskon }})</span>
                            <span>- IDR {{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pt-6">
                        <span class="text-lg font-medium text-gray-800">Total</span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-xs text-gray-500">IDR</span>
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($finalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function togglePayment(type) {
            const bankInfo = document.getElementById('bank-info');
            const proofInput = document.getElementById('proof-input');
            if (type === 'bank') {
                bankInfo.classList.remove('hidden');
                proofInput.setAttribute('required', 'required');
            } else {
                bankInfo.classList.add('hidden');
                proofInput.removeAttribute('required');
                proofInput.value = '';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#463126', confirmButtonText: 'OK', color: '#48311B' });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#d33', confirmButtonText: 'Coba Lagi' });
        @endif
        @if($errors->any())
            Swal.fire({ icon: 'warning', title: 'Periksa Inputan!', html: "{!! implode('<br>', $errors->all()) !!}", confirmButtonColor: '#f6d59e', confirmButtonText: 'OK', color: '#48311B' });
        @endif
    </script>
@endsection
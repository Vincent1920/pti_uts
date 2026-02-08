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
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #463126;
    }
</style>

<div class="w-full min-h-screen bg-cream flex justify-center items-center py-10 px-4">

    <div class="flex flex-col lg:flex-row w-full max-w-6xl bg-white shadow-2xl rounded-xl overflow-hidden">

        <div class="w-full lg:w-[58%] bg-white px-6 py-8 lg:px-12 lg:py-12 order-2 lg:order-1 border-r border-gray-200">
            {{-- 
                <div class="mb-8 text-center lg:text-left">
                    <a href="/" class="text-5xl font-qwigley text-black hover:text-choco transition">ChocoScript</a>
                </div> --}}

            <div class="mb-8">
                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="final_price" value="{{ $finalPrice }}">

                    <div class="space-y-8">
                        <div>
                            <h2 class="text-xl font-kotta text-gray-900 mb-4 border-b pb-2">Alamat Pengiriman</h2>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 ml-1">Nama Penerima</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition"
                                        required>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-gray-500 ml-1">Email</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email }}"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm bg-gray-50 cursor-not-allowed"
                                        readonly>
                                </div>
                                <div class="relative">
                                    <input type="text" name="address" placeholder="Alamat Lengkap"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition"
                                        required>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="city" placeholder="Kota / Kabupaten"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition"
                                        required>
                                    <input type="text" name="postal_code" placeholder="Kode Pos"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition"
                                        required>
                                </div>
                                <div class="relative">
                                    <input type="tel" name="phone" placeholder="Nomor Telepon (WhatsApp)"
                                        class="w-full border border-gray-300 rounded-md px-4 py-3 text-sm focus:outline-none focus:border-choco transition"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <h2 class="text-xl font-kotta text-gray-900 mb-4 border-b pb-2">Metode Pembayaran</h2>
                            <div class="space-y-3">

                                <div class="relative">
                                    <label
                                        class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-alice transition has-[:checked]:border-choco has-[:checked]:bg-alice shadow-sm">
                                        <input type="radio" name="payment_method" value="midtrans"
                                            class="mt-1 accent-choco" required>

                                        <div class="ml-3 w-full">
                                            <span class="block font-bold text-gray-800">Pembayaran Online</span>

                                            <span class="text-xs text-gray-500">
                                                Bayar otomatis via Transfer Bank (VA), QRIS, atau E-Wallet.
                                            </span>
                                        </div>
                                    </label>
                                </div>

                                <label
                                    class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-alice transition has-[:checked]:border-choco has-[:checked]:bg-alice shadow-sm">
                                    <input type="radio" name="payment_method" value="cod" class="mt-1 accent-choco"
                                        onchange="toggleMidtransOptions(false)">
                                    <div class="ml-3">
                                        <span class="block font-bold text-gray-800">Cash on Delivery (COD)</span>
                                        <span class="text-xs text-gray-500">Bayar tunai ke kurir saat barang
                                            sampai</span>
                                    </div>
                                </label>

                            </div>
                        </div>

                        <div
                            class="flex flex-col-reverse sm:flex-row justify-between items-center gap-4 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('cart') }}"
                                class="text-choco_light hover:text-choco text-sm flex items-center transition font-medium">
                                <i class="bi bi-chevron-left text-xs mr-2"></i> Kembali ke Keranjang
                            </a>
                            <button type="submit" id="pay-button"
                                class="w-full sm:w-auto bg-choco hover:bg-choco_light text-white font-bold py-4 px-10 rounded-lg transition duration-300 text-sm shadow-lg transform hover:-translate-y-1 active:scale-95">
                                Konfirmasi & Buat Pesanan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div
            class="w-full lg:w-[42%] bg-gray-50 border-l border-gray-200 px-6 py-8 lg:px-10 lg:py-12 order-1 lg:order-2">
            <div class="sticky top-6">
                <h2 class="text-lg font-bold text-gray-800 mb-6 lg:hidden">Ringkasan Pesanan</h2>

                <div class="space-y-4 mb-6 pb-6 border-b border-gray-200 max-h-[400px] overflow-y-auto pr-2">
                    @foreach($cartItems as $item)
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <div class="relative border border-gray-200 rounded-lg w-16 h-16 bg-white p-1 shadow-sm">
                                <img src="{{ asset('images/' . $item->barang->img) }}" alt="Product"
                                    class="w-full h-full object-cover rounded-md">
                                <span
                                    class="absolute -top-0 -right-2 bg-gray-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full shadow-md">
                                    {{ $item->quantity }}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm group-hover:text-choco transition">
                                    {{ $item->barang->title }}</h3>
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
                        <span
                            class="text-2xl font-bold text-gray-900">{{ number_format($finalPrice, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<<<<<<< HEAD
<script>
    function toggleMidtransOptions(isMidtrans) {
        const container = document.getElementById('midtrans-dropdown-container');
        if (isMidtrans) {
            container.classList.remove('hidden');
            // Menambah animasi fade in sederhana
            container.style.opacity = '0';
            setTimeout(() => {
                container.style.transition = 'opacity 0.3s ease';
                container.style.opacity = '1';
            }, 10);
        } else {
            container.classList.add('hidden');
        }
    }
</script>

=======
>>>>>>> bb8fc8355ad3eedea55c976641db0750c36280f6
<style>
    /* Tambahkan sedikit animasi agar dropdown tidak kaku */
    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<<<<<<< HEAD
=======
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#463126',
        confirmButtonText: 'OK',
        color: '#48311B'
    });
    @endif
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: "{{ session('error') }}",
        confirmButtonColor: '#d33',
        confirmButtonText: 'Coba Lagi'
    });
    @endif
    @if($errors -> any())
    Swal.fire({
        icon: 'warning',
        title: 'Periksa Inputan!',
        html: "{!! implode('<br>', $errors->all()) !!}",
        confirmButtonColor: '#f6d59e',
        confirmButtonText: 'OK',
        color: '#48311B'
    });
    @endif
</script>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

<script>
    const checkoutForm = document.querySelector('form[action="{{ route("checkout.process") }}"]');
    const payButton = document.getElementById('pay-button');

    checkoutForm.addEventListener('submit', async function (e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (paymentMethod === 'midtrans') {
            e.preventDefault(); 

            payButton.disabled = true;
            payButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';

            try {
                const formData = new FormData(checkoutForm);
                const response = await fetch("{{ route('checkout.process') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            Swal.fire('Berhasil!', 'Pembayaran Anda telah diterima.', 'success')
                                .then(() => window.location.href = "{{ route('orders.index') }}");
                        },
                        onPending: function (result) {
                            Swal.fire('Pending', 'Segera selesaikan pembayaran Anda.', 'info')
                                .then(() => window.location.href = "{{ route('orders.index') }}");
                        },
                        onError: function (result) {
                            Swal.fire('Gagal', 'Pembayaran gagal diproses.', 'error');
                            payButton.disabled = false;
                            payButton.innerHTML = "Konfirmasi & Buat Pesanan";
                        },
                        onClose: function () {
                            // Handler saat user menutup popup Midtrans
                            Swal.fire({
                                title: 'Batal Bayar?',
                                text: "Pesanan ini akan tetap tersimpan. Ingin membatalkannya sekarang?",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#463126',
                                confirmButtonText: 'Ya, Batalkan Pesanan',
                                cancelButtonText: 'Nanti saja (Simpan)'
                            }).then(async (result) => {
                                if (result.isConfirmed) {
                                    // Panggil route cancelTransaction
                                    try {
                                        const cancelRes = await fetch(`/checkout/cancel/${data.invoice_code}`, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                                'Accept': 'application/json'
                                            }
                                        });
                                        const cancelData = await cancelRes.json();
                                        
                                        if(cancelData.success) {
                                            Swal.fire('Dibatalkan', 'Pesanan dibatalkan dan stok dikembalikan.', 'success')
                                                .then(() => window.location.reload());
                                        }
                                    } catch (err) {
                                        console.error("Gagal membatalkan:", err);
                                    }
                                } else {
                                    // Jika pilih simpan, arahkan ke daftar transaksi
                                    window.location.href = "{{ route('orders.index') }}";
                                }
                            });

                            payButton.disabled = false;
                            payButton.innerHTML = "Konfirmasi & Buat Pesanan";
                        }
                    });
                } else {
                    Swal.fire('Stok Habis', data.message, 'error');
                    payButton.disabled = false;
                    payButton.innerHTML = "Konfirmasi & Buat Pesanan";
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                payButton.disabled = false;
                payButton.innerHTML = "Konfirmasi & Buat Pesanan";
            }
        }
    });
</script>
>>>>>>> bb8fc8355ad3eedea55c976641db0750c36280f6
@endsection
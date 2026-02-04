@extends('index')

@section('container-home')
<div class="w-full min-h-screen bg-cream flex justify-center items-center">
    <div class="bg-white p-10 rounded-2xl shadow-2xl text-center border-t-4 border-choco max-w-sm">
        <div class="mb-6">
            <i class="bi bi-wallet2 text-5xl text-choco"></i>
        </div>
        <h2 class="text-2xl font-kotta text-gray-900 mb-2">Selesaikan Pembayaran</h2>
        <p class="text-sm text-gray-500 mb-8">Silakan klik tombol di bawah jika jendela pembayaran aman tidak muncul otomatis.</p>
        
        <button id="pay-button" class="w-full bg-choco hover:bg-choco_light text-white font-bold py-4 rounded-xl transition shadow-lg transform hover:-translate-y-1">
            Bayar Sekarang
        </button>
    </div>
</div>

<script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="Mid-client-U32ikRzP-WQGbNGj">
</script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    
    // Fungsi untuk membuka Snap
    function openSnap() {
        // Cek apakah snap sudah terload
        if (window.snap) {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) { 
                    window.location.href = "{{ route('home') }}?status=success"; 
                },
                onPending: function(result) { 
                    window.location.href = "{{ route('home') }}?status=pending"; 
                },
                onError: function(result) { 
                    console.log(result);
                    alert("Pembayaran Gagal!"); 
                },
                onClose: function() { 
                    alert('Anda menutup jendela pembayaran sebelum menyelesaikan transaksi.'); 
                }
            });
        } else {
            alert('Midtrans Snap belum siap. Silakan refresh halaman.');
        }
    }

    payButton.onclick = function(e) {
        e.preventDefault();
        openSnap();
    };

    // Auto open (opsional)
    window.onload = function() {
        setTimeout(openSnap, 500);
    };
</script>
@endsection
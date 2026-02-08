@extends('index')

@section('container-home')

<<<<<<< HEAD
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
=======
<div class="container text-center">
    <h2>Konfirmasi Pembayaran</h2>
    <p>Pesanan Anda telah dicatat. Silakan klik tombol di bawah untuk membayar.</p>
    
    <button id="pay-button" class="btn btn-primary">Bayar Sekarang</button>
</div>
@endsection

@push('scripts') 
{{-- Gunakan @push agar script berada di bawah setelah library lain --}}

<script type="text/javascript" 
        src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('services.midtrans.clientKey') }}">
</script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        
        payButton.onclick = function(e) {
            e.preventDefault();
            
            // Variabel $snapToken ini dipasok oleh Controller
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) { 
                    window.location.href = "{{ route('home') }}"; 
                },
                onPending: function(result) { 
                    alert("Menunggu Pembayaran..."); 
                },
                onError: function(result) { 
                    alert("Pembayaran Gagal!"); 
                },
                onClose: function() {
                    alert('Anda menutup popup sebelum membayar.');
                }
            });
        };
    });
>>>>>>> bb8fc8355ad3eedea55c976641db0750c36280f6
</script>
@endpush
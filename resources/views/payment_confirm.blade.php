@extends('index')

@section('container-home')

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
</script>
@endpush
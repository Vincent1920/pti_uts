<script type="text/javascript" 
        src="https://app.sandbox.midtrans.com/snap/snap.js" 
        data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    payButton.onclick = function(e) {
        e.preventDefault();
        // Memanggil token yang dikirim dari controller
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) { window.location.href = "{{ route('home') }}"; },
            onPending: function(result) { alert("Menunggu Pembayaran"); },
            onError: function(result) { alert("Gagal!"); }
        });
    };
</script>
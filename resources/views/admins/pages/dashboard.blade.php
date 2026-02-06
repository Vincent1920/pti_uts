@extends('admins.index')

@section('admin')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm italic">Total Volume (Bulan Ini)</p>
            <h2 class="text-3xl font-bold text-gray-800 mt-2">Rp {{ number_format($totalVolume, 0, ',', '.') }}</h2>
            <p class="text-blue-500 text-xs font-semibold mt-1">Total Penjualan</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <p class="text-gray-500 text-sm italic">Total Transaksi (Bulan Ini)</p>
            <h2 class="text-4xl font-bold text-gray-800 mt-1">{{ $totalTransaction }}</h2>
            <p class="text-gray-400 text-xs mt-1">Transaksi Berhasil</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-green-100">
            <p class="text-gray-500 text-sm italic">Total Potongan Diskon</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">Rp {{ number_format($totalHemat, 0, ',', '.') }}</h2>
            <p class="text-xs text-gray-400 mt-1">Dari {{ $transaksiDiskon }} transaksi didiskon</p>
        </div>
    </div>

    <div class="mb-10 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <h2 class="text-sm font-semibold text-gray-600 mb-4 border-l-4 border-choco pl-3">Tren Penjualan (7 Hari Terakhir)</h2>
        <div style="height: 300px;">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Stok Barang Tersedia</h2>
            <div style="height: 300px;">
                <canvas id="barangChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-sm font-semibold text-gray-700 mb-4">Persentase Transaksi Diskon</h2>
            <div style="height: 300px;">
                <canvas id="diskonChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // --- 1. SCRIPT GRAFIK PENJUALAN (LINE) ---
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: {!! json_encode($salesData->pluck('date')) !!},
                datasets: [{
                    label: 'Volume Penjualan',
                    data: {!! json_encode($salesData->pluck('total')) !!},
                    fill: true,
                    backgroundColor: 'rgba(255, 230, 170, 0.4)',
                    borderColor: 'rgba(210, 180, 140, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') }
                    }
                }
            }
        });

        // --- 2. SCRIPT GRAFIK STOK (BAR) ---
        const ctxBarang = document.getElementById('barangChart').getContext('2d');
        new Chart(ctxBarang, {
            type: 'bar',
            data: {
                labels: {!! json_encode($dataBarang->pluck('title')) !!},
                datasets: [{
                    label: 'Jumlah Stok',
                    data: {!! json_encode($dataBarang->pluck('jumlah_barang')) !!},
                    backgroundColor: 'rgba(139, 69, 19, 0.7)',
                    borderColor: 'rgba(139, 69, 19, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // --- 3. SCRIPT GRAFIK DISKON (PIE) ---
        const ctxDiskon = document.getElementById('diskonChart').getContext('2d');
        new Chart(ctxDiskon, {
            type: 'pie',
            data: {
                labels: ['Dengan Diskon', 'Tanpa Diskon'],
                datasets: [{
                    data: [{{ $transaksiDiskon }}, {{ $perbandinganDiskon['Tanpa Diskon'] }}],
                    backgroundColor: ['#10B981', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
@endsection
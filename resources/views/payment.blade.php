<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - Loka Loka</title>
    <style>
        /* CSS UMUM */
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&family=Roboto:wght@400;700&family=Open+Sans:wght@400&display=swap');
        :root {
            --primary-color: #5c6641;
            --background-color: #e3d8c2;
            --surface-color: #ffffff;
            --text-primary: #333333;
            --text-secondary: #666666;
            --border-color: #e0e0e0;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--background-color);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
            margin: 0;
            color: var(--text-primary);
            box-sizing: border-box;
        }
        
        /* CSS KHUSUS Halaman QRIS */
        .qris-container {
            background-color: var(--surface-color);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 360px;
            text-align: center;
        }
        .qris-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .qris-header h1 { 
            font-family: 'Roboto', sans-serif; 
            font-size: 1.8em; 
            margin: 0 0 5px 0;
        }
        .payment-status {
            font-size: 0.9em;
            color: var(--text-secondary);
            margin: 0;
        }
        .qr-code img {
            width: 100%; 
            max-width: 200px;
            height: auto;
            border-radius: 10px; 
            margin: 0 auto 20px auto; 
            display: block;
        }
        .payment-details {
            border-top: 1px solid var(--border-color);
            padding-top: 20px; display: flex; flex-direction: column; gap: 15px;
        }
        .detail-row { display: flex; justify-content: space-between; align-items: center; }
        .label { font-size: 0.9em; color: var(--text-secondary); }
        .amount { font-size: 1.2em; font-weight: 700; color: var(--primary-color); }
        .powered-by { margin-top: 30px; }
        .powered-by img { 
            width: 120px; 
            height: auto;
            opacity: 0.9;
        }
    </style>
</head>
<body>

    <div class="qris-container">
        <div class="qris-header">
            <h1>QRIS</h1>
            <p class="payment-status">Menunggu Pembayaran</p>
        </div>
        
        <div class="qr-code">
            <img src="https://i.ibb.co/C07Bf5L/sample-qris.png" alt="QRIS Code Loka Loka">
        </div>
        <div class="payment-details">
            <div class="detail-row">
                <span class="label">Due on</span>
                <span>{{ now()->addDay()->format('H:i, d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Payment amount</span>
                <span class="amount" id="payment-amount">Loading...</span>
            </div>
        </div>
        <div class="powered-by">
            <img src="{{ asset('images/logo.png') }}" alt="Loka Loka">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ambil total dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const total = parseInt(urlParams.get('total')) || 0;

            // Format menjadi Rupiah
            const formattedTotal = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(total);

            // Tampilkan di halaman
            document.getElementById('payment-amount').innerText = formattedTotal;
        });
    </script>

</body>
</html>
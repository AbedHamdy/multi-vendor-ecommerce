<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Special Coupon</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="20" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="60" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="80" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="10" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 15s ease-in-out infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .coupon-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            position: relative;
            margin-bottom: 30px;
        }

        .coupon-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .coupon-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 4s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .coupon-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
            position: relative;
            z-index: 2;
        }

        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0,-15px,0); }
            70% { transform: translate3d(0,-7px,0); }
            90% { transform: translate3d(0,-2px,0); }
        }

        .coupon-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }

        .coupon-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .coupon-body {
            padding: 40px;
        }

        .code-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 3px dashed #ff6b6b;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .code-section::before {
            content: '✂️';
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 10px;
            font-size: 1.5rem;
        }

        .code-label {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 15px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .coupon-code {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            font-size: 3rem;
            font-weight: 800;
            padding: 20px 30px;
            border-radius: 15px;
            display: inline-block;
            letter-spacing: 5px;
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .coupon-code:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 107, 107, 0.6);
        }

        .copy-hint {
            margin-top: 15px;
            font-size: 0.9rem;
            color: #6c757d;
            font-style: italic;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .detail-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
            padding: 25px;
            border-radius: 20px;
            border-left: 5px solid;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(255,255,255,0.3), transparent);
            border-radius: 50%;
            transform: translate(50%, -50%);
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .detail-card.discount {
            border-left-color: #28a745;
        }

        .detail-card.type {
            border-left-color: #007bff;
        }

        .detail-card.minimum {
            border-left-color: #ffc107;
        }

        .detail-card.usage {
            border-left-color: #6f42c1;
        }

        .detail-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.8;
        }

        .detail-card.discount .detail-icon { color: #28a745; }
        .detail-card.type .detail-icon { color: #007bff; }
        .detail-card.minimum .detail-icon { color: #ffc107; }
        .detail-card.usage .detail-icon { color: #6f42c1; }

        .detail-label {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .detail-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .detail-description {
            font-size: 0.85rem;
            color: #6c757d;
            line-height: 1.4;
        }

        .expiry-section {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 2px solid #ffc107;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .expiry-icon {
            font-size: 3rem;
            color: #856404;
            margin-bottom: 15px;
            animation: tick 1s infinite;
        }

        @keyframes tick {
            0%, 50% { transform: scale(1); }
            25% { transform: scale(1.1); }
        }

        .expiry-title {
            font-size: 1.5rem;
            color: #856404;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .expiry-date {
            font-size: 2rem;
            color: #dc3545;
            font-weight: 800;
            margin: 15px 0;
            text-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }

        .countdown {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px 20px;
            border-radius: 15px;
            text-align: center;
            min-width: 80px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .countdown-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: #dc3545;
            display: block;
        }

        .countdown-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        .action-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .action-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .action-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 2;
        }

        .action-description {
            font-size: 1.1rem;
            margin-bottom: 30px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .shop-btn {
            background: white;
            color: #28a745;
            padding: 18px 40px;
            border: none;
            border-radius: 50px;
            font-size: 1.3rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 2;
        }

        .shop-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
            color: #28a745;
        }

        .usage-progress {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            height: 10px;
            overflow: hidden;
            margin-top: 10px;
        }

        .usage-bar {
            background: linear-gradient(135deg, #6f42c1, #8e44ad);
            height: 100%;
            border-radius: 10px;
            transition: width 0.8s ease;
        }

        .copy-notification {
            position: fixed;
            top: 30px;
            right: 30px;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
            transform: translateX(400px);
            transition: all 0.5s ease;
            z-index: 1000;
            font-weight: 600;
        }

        .copy-notification.show {
            transform: translateX(0);
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .coupon-header {
                padding: 30px 20px;
            }

            .coupon-title {
                font-size: 2rem;
            }

            .coupon-code {
                font-size: 2rem;
                padding: 15px 20px;
                letter-spacing: 3px;
            }

            .details-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .countdown {
                gap: 10px;
            }

            .countdown-item {
                min-width: 60px;
                padding: 10px 15px;
            }

            .expiry-date {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Copy Notification -->
        <div class="copy-notification" id="copyNotification">
            <i class="fas fa-check-circle"></i> Code copied to clipboard!
        </div>

        <!-- Main Coupon Card -->
        <div class="coupon-card">
            <!-- Header -->
            <div class="coupon-header">
                <div class="coupon-icon">🎁</div>
                <h1 class="coupon-title">Special Offer!</h1>
                <p class="coupon-subtitle">Your exclusive {{ $coupon->type }} coupon is ready</p>
            </div>

            <!-- Body -->
            <div class="coupon-body">
                <!-- Coupon Code Section -->
                <div class="code-section">
                    <div class="code-label">Your Coupon Code</div>
                    <div class="coupon-code" onclick="copyCode('{{ $coupon->code }}')">
                        {{ $coupon->code }}
                    </div>
                    <div class="copy-hint">
                        <i class="fas fa-copy"></i> Click to copy code
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="details-grid">
                    <div class="detail-card discount">
                        <div class="detail-icon">
                            @if($coupon->discount_type == 'percent')
                                <i class="fas fa-percentage"></i>
                            @else
                                <i class="fas fa-money-bill-wave"></i>
                            @endif
                        </div>
                        <div class="detail-label">Your Discount</div>
                        <div class="detail-value">
                            {{ $coupon->value }}{{ $coupon->discount_type == 'percent' ? '%' : '$' }} OFF
                        </div>
                        <div class="detail-description">
                            {{ $coupon->discount_type == 'percent' ? 'Percentage discount' : 'Fixed amount discount' }}
                        </div>
                    </div>

                    <div class="detail-card type">
                        <div class="detail-icon">
                            @switch($coupon->type)
                                @case('welcome')
                                    <i class="fas fa-hand-wave"></i>
                                    @break
                                @case('loyalty')
                                    <i class="fas fa-heart"></i>
                                    @break
                                @case('event')
                                    <i class="fas fa-calendar-star"></i>
                                    @break
                                @default
                                    <i class="fas fa-gift"></i>
                            @endswitch
                        </div>
                        <div class="detail-label">Coupon Type</div>
                        <div class="detail-value">{{ ucfirst($coupon->type) }}</div>
                        <div class="detail-description">
                            @switch($coupon->type)
                                @case('welcome')
                                    Welcome bonus for new customers
                                    @break
                                @case('loyalty')
                                    Reward for loyal customers
                                    @break
                                @case('event')
                                    Special event promotion
                                    @break
                                @default
                                    General promotional offer
                            @endswitch
                        </div>
                    </div>

                    @if($coupon->min_order_amount)
                    <div class="detail-card minimum">
                        <div class="detail-icon"><i class="fas fa-shopping-cart"></i></div>
                        <div class="detail-label">Minimum Order</div>
                        <div class="detail-value">{{ number_format($coupon->min_order_amount, 2) }} $</div>
                        <div class="detail-description">Minimum purchase amount required</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Expiry Section -->
        @if($coupon->end_date)
        <div class="expiry-section">
            <div class="expiry-icon">⏰</div>
            <div class="expiry-title">Hurry Up! Limited Time Offer</div>
            <div class="expiry-date">Expires: {{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}</div>
        </div>
        @endif

        <!-- Action Section -->
        <div class="action-section">
            <h2 class="action-title">Ready to Save Money?</h2>
            <p class="action-description">
                Use this coupon code at checkout and enjoy amazing savings on your next purchase!
            </p>
            <a href="{{ url('http://127.0.0.1:8000/all-products') }}" class="shop-btn">
                <i class="fas fa-shopping-bag"></i>
                Start Shopping
            </a>
        </div>
    </div>
</body>
</html>

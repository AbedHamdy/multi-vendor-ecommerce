@extends('Client.layouts.app')
@section('title', 'My Orders')

@push('styles')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            margin-bottom: 30px;
        }

        .orders-container {
            min-height: 70vh;
        }

        .order-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #f1f3f4;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .order-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px 25px;
            border-bottom: 2px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-info-header {
            flex: 1;
        }

        .order-id {
            font-size: 18px;
            font-weight: 700;
            color: #495057;
            margin: 0;
        }

        .order-date {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }

        .current-status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            color: #d63031;
        }

        .status-paid {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
        }

        .status-shipped {
            background: linear-gradient(135deg, #55a3ff 0%, #003d82 100%);
            color: white;
        }

        /* Order Timeline */
        .order-timeline {
            padding: 30px 25px;
            background: #f8f9fa;
        }

        .timeline-wrapper {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline-vertical {
            position: relative;
            padding-left: 40px;
        }

        /* Vertical Line */
        .timeline-line {
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #dee2e6;
        }

        .timeline-progress {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: linear-gradient(180deg, #11998e 0%, #38ef7d 100%);
            transition: height 1.5s ease-in-out;
        }

        /* Timeline Items */
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .timeline-item.completed {
            border-left-color: #11998e;
        }

        .timeline-item.active {
            border-left-color: #38ef7d;
            box-shadow: 0 4px 20px rgba(17, 153, 142, 0.15);
            background: linear-gradient(135deg, #f0fff4 0%, #ffffff 100%);
        }

        .timeline-item:hover {
            transform: translateX(5px);
        }

        /* Timeline Dot */
        .timeline-dot {
            position: absolute;
            left: -42px;
            top: 25px;
            width: 16px;
            height: 16px;
            background: #dee2e6;
            border: 4px solid #fff;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .timeline-item.completed .timeline-dot {
            background: #11998e;
        }

        .timeline-item.active .timeline-dot {
            background: #38ef7d;
            animation: pulse 2s infinite;
            transform: scale(1.2);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(56, 239, 125, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(56, 239, 125, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(56, 239, 125, 0);
            }
        }

        /* Timeline Content */
        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .timeline-title {
            font-size: 16px;
            font-weight: 600;
            color: #495057;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .timeline-time {
            font-size: 12px;
            color: #6c757d;
            font-weight: 500;
        }

        .timeline-description {
            font-size: 14px;
            color: #6c757d;
            line-height: 1.5;
            margin: 0;
        }

        .timeline-icon {
            font-size: 18px;
            color: #11998e;
        }

        .timeline-item.active .timeline-icon {
            color: #38ef7d;
        }

        /* Order Details */
        .order-details {
            padding: 25px;
            background: white;
        }

        .order-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .info-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
        }

        .info-value {
            font-size: 16px;
            font-weight: 700;
            color: #495057;
            margin-bottom: 5px;
        }

        .info-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .total-amount {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .empty-orders {
            text-align: center;
            padding: 100px 0;
        }

        .empty-orders i {
            font-size: 120px;
            color: #dee2e6;
            margin-bottom: 30px;
        }

        .btn-start-shopping {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-start-shopping:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(17, 153, 142, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .timeline-vertical {
                padding-left: 30px;
            }

            .timeline-dot {
                left: -32px;
            }

            .order-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .timeline-header {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h2 mb-0">My Orders</h1>
                    <p class="mb-0 opacity-75">Track your order timeline</p>
                </div>
                {{-- <div class="col-md-6 text-md-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end mb-0" style="background: transparent;">
                            <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item text-white" aria-current="page">My Orders</li>
                        </ol>
                    </nav>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="container orders-container">
        @if($orders && $orders->count() > 0)
            <!-- Orders List -->
            @foreach($orders as $order)
                @php
                    $num = 1;
                @endphp
                <div class="order-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div class="order-info-header">
                            <h4 class="order-id">Order #{{ $num++ }}</h4>
                            <p class="order-date">Placed on {{ $order->created_at->format('F d, Y - h:i A') }}</p>
                        </div>
                        <div class="current-status-badge status-{{ $order->status }}">
                            {{ ucfirst($order->status) }}
                        </div>
                    </div>

                    <!-- Order Timeline -->
                    <div class="order-timeline">
                        <div class="timeline-wrapper">
                            <div class="timeline-vertical">
                                <!-- Timeline Line with Progress -->
                                <div class="timeline-line">
                                    <div class="timeline-progress"
                                         style="height: {{ $order->status == 'pending' ? '25%' : ($order->status == 'paid' ? '50%' : '75%') }};">
                                    </div>
                                </div>

                                <!-- Timeline Items -->
                                <!-- Order Placed -->
                                <div class="timeline-item completed">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-header">
                                        <h5 class="timeline-title">
                                            <i class="fas fa-shopping-cart timeline-icon"></i>
                                            Order Placed
                                        </h5>
                                        <span class="timeline-time">{{ $order->created_at->format('M d, Y - h:i A') }}</span>
                                    </div>
                                    <p class="timeline-description">Your order has been received and is being processed.</p>
                                </div>

                                <!-- Payment Status -->
                                <div class="timeline-item {{ $order->status == 'paid' || $order->status == 'shipped' ? 'completed' : ($order->status == 'pending' ? 'active' : '') }}">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-header">
                                        <h5 class="timeline-title">
                                            <i class="fas fa-credit-card timeline-icon"></i>
                                            Payment
                                            @if($order->status == 'pending')
                                                Processing
                                            @else
                                                Confirmed
                                            @endif
                                        </h5>
                                        <span class="timeline-time">
                                            @if($order->status != 'pending')
                                                {{ $order->updated_at->format('M d, Y - h:i A') }}
                                            @else
                                                Pending
                                            @endif
                                        </span>
                                    </div>
                                    <p class="timeline-description">
                                        @if($order->status == 'pending')
                                            Waiting for payment confirmation.
                                        @else
                                            Payment has been confirmed successfully.
                                        @endif
                                    </p>
                                </div>

                                <!-- Shipped Status -->
                                <div class="timeline-item {{ $order->status == 'shipped' ? 'active' : ($order->status == 'completed' ? 'completed' : '') }}">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-header">
                                        <h5 class="timeline-title">
                                            <i class="fas fa-truck timeline-icon"></i>
                                            Order Shipped
                                        </h5>
                                        <span class="timeline-time">
                                            @if($order->status == 'shipped' || $order->status == 'completed')
                                                {{ $order->updated_at->format('M d, Y - h:i A') }}
                                            @else
                                                Not shipped yet
                                            @endif
                                        </span>
                                    </div>
                                    <p class="timeline-description">
                                        @if($order->status == 'shipped')
                                            Your order is on the way to your address.
                                        @elseif($order->status == 'completed')
                                            Order was shipped and delivered successfully.
                                        @else
                                            Order will be shipped after payment confirmation.
                                        @endif
                                    </p>
                                </div>

                                <!-- Delivered Status (if completed) -->
                                {{-- @if($order->status == 'completed')
                                <div class="timeline-item completed">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-header">
                                        <h5 class="timeline-title">
                                            <i class="fas fa-check-circle timeline-icon"></i>
                                            Order Delivered
                                        </h5>
                                        <span class="timeline-time">{{ $order->updated_at->format('M d, Y - h:i A') }}</span>
                                    </div>
                                    <p class="timeline-description">Order delivered successfully. Thank you for shopping with us!</p>
                                </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="order-details">
                        <div class="order-info">
                            {{-- <div class="info-card">
                                <div class="info-value">{{ $order->orderItems->count() ?? 0 }}</div>
                                <div class="info-label">Items</div>
                            </div> --}}
                            <div class="info-card">
                                <div class="info-value total-amount">{{ number_format($order->total_amount, 2) }} EGP</div>
                                <div class="info-label">Total Amount</div>
                            </div>
                            <div class="info-card">
                                <div class="info-value">{{ $order->created_at->diffForHumans() }}</div>
                                <div class="info-label">Order Age</div>
                            </div>
                            <div class="info-card">
                                <div class="info-value" style="color: {{ $order->status == 'pending' ? '#fdcb6e' : ($order->status == 'paid' ? '#74b9ff' : '#0984e3') }};">
                                    @if($order->status == 'pending')
                                        Processing
                                    @elseif($order->status == 'paid')
                                        Ready to Ship
                                    @else
                                        In Transit
                                    @endif
                                </div>
                                <div class="info-label">Current Stage</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        @else
            <!-- Empty State -->
            <div class="empty-orders">
                <i class="fas fa-shopping-cart"></i>
                <h3>No Orders Yet</h3>
                <p>You haven't placed any orders yet. Start shopping to see your orders here!</p>
                <a href="{{ route('view_product') }}" class="btn-start-shopping">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation on scroll for order cards
            const orderCards = document.querySelectorAll('.order-card');
            orderCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Animate timeline progress after page load
            setTimeout(() => {
                const progressBars = document.querySelectorAll('.timeline-progress');

                progressBars.forEach(bar => {
                    const height = bar.style.height;
                    bar.style.height = '0%';
                    setTimeout(() => {
                        bar.style.height = height;
                    }, 500);
                });
            }, 800);

            // Staggered animation for timeline items
            const timelineItems = document.querySelectorAll('.timeline-item');
            timelineItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';

                setTimeout(() => {
                    item.style.transition = 'all 0.5s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, (index * 150) + 1000);
            });
        });
    </script>
@endpush

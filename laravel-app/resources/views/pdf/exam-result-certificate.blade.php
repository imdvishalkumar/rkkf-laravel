<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result Certificate - {{ $certificate_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 0;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #ffffff;
            color: #1a1a2e;
            line-height: 1.6;
        }

        .certificate-container {
            width: 100%;
            min-height: 100vh;
            padding: 40px;
            position: relative;
        }

        /* Decorative Border */
        .certificate-border {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #d4af37;
            border-radius: 10px;
        }

        .certificate-border-inner {
            position: absolute;
            top: 30px;
            left: 30px;
            right: 30px;
            bottom: 30px;
            border: 1px solid #d4af37;
            border-radius: 8px;
        }

        /* Header Section */
        .header {
            text-align: center;
            padding: 30px 40px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: #ffffff;
            border-radius: 8px 8px 0 0;
            margin: 40px 40px 0 40px;
        }

        .header-logo {
            font-size: 42px;
            font-weight: bold;
            letter-spacing: 3px;
            color: #d4af37;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            margin-bottom: 10px;
        }

        .header-subtitle {
            font-size: 18px;
            letter-spacing: 8px;
            text-transform: uppercase;
            color: #e8e8e8;
            margin-bottom: 5px;
        }

        .header-line {
            width: 150px;
            height: 3px;
            background: #d4af37;
            margin: 15px auto;
        }

        /* Content Section */
        .content {
            padding: 40px 60px;
            margin: 0 40px;
            background: #ffffff;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }

        /* Result Details Grid */
        .details-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .detail-row {
            display: table-row;
        }

        .detail-label {
            display: table-cell;
            padding: 12px 20px 12px 0;
            font-weight: 600;
            color: #4b5563;
            width: 200px;
            vertical-align: top;
            font-size: 14px;
        }

        .detail-value {
            display: table-cell;
            padding: 12px 0;
            color: #1f2937;
            font-size: 15px;
            vertical-align: top;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 8px 25px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 1px;
        }

        .status-pass {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
        }

        .status-fail {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: #ffffff;
        }

        /* Belt Transition */
        .belt-transition {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }

        .belt-transition-title {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
        }

        .belt-arrow {
            display: inline-block;
            margin: 0 20px;
            color: #d4af37;
            font-size: 24px;
        }

        .belt-name {
            display: inline-block;
            padding: 10px 25px;
            background: #1a1a2e;
            color: #d4af37;
            border-radius: 5px;
            font-weight: bold;
            font-size: 16px;
        }

        /* Remarks Section */
        .remarks-section {
            background:
                {{ $is_passed ? '#f0fdf4' : '#fef2f2' }}
            ;
            border-left: 4px solid
                {{ $is_passed ? '#22c55e' : '#ef4444' }}
            ;
            padding: 20px 25px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }

        .remarks-title {
            font-weight: bold;
            color:
                {{ $is_passed ? '#166534' : '#991b1b' }}
            ;
            margin-bottom: 5px;
        }

        .remarks-text {
            color:
                {{ $is_passed ? '#15803d' : '#b91c1c' }}
            ;
            font-size: 15px;
        }

        /* Footer Section */
        .footer {
            text-align: center;
            padding: 30px 40px;
            margin: 0 40px 40px 40px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
        }

        .certificate-number {
            font-size: 13px;
            color: #6b7280;
            letter-spacing: 1px;
        }

        .certificate-number strong {
            color: #1a1a2e;
            font-size: 15px;
        }

        .generated-date {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 15px;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(212, 175, 55, 0.06);
            font-weight: bold;
            letter-spacing: 10px;
            z-index: -1;
            white-space: nowrap;
        }

        /* Print Styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <!-- Decorative Borders -->
        <div class="certificate-border"></div>
        <div class="certificate-border-inner"></div>

        <!-- Watermark -->
        <div class="watermark">RKKF</div>

        <!-- Header -->
        <div class="header">
            <div class="header-logo">RKKF</div>
            <div class="header-subtitle">Exam Result Certificate</div>
            <div class="header-line"></div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="section-title">Examination Details</div>

            <div class="details-grid">
                <div class="detail-row">
                    <div class="detail-label">Exam Name</div>
                    <div class="detail-value">{{ $exam_name }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Exam Date</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($exam_date)->format('F j, Y') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">{{ $location }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Attendance</div>
                    <div class="detail-value">{{ $attend === 'P' ? 'Present' : ($attend === 'A' ? 'Absent' : $attend) }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Certificate Number</div>
                    <div class="detail-value"><strong>{{ $certificate_no }}</strong></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Result Status</div>
                    <div class="detail-value">
                        <span class="status-badge {{ $is_passed ? 'status-pass' : 'status-fail' }}">
                            {{ $status }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Belt Transition -->
            <div class="belt-transition">
                <div class="belt-transition-title">Belt Transition</div>
                <span class="belt-name">{{ $belt_transition['from'] }}</span>
                <span class="belt-arrow">→</span>
                <span class="belt-name">{{ $belt_transition['to'] }}</span>
            </div>

            <!-- Remarks -->
            <div class="remarks-section">
                <div class="remarks-title">Remarks</div>
                <div class="remarks-text">{{ $remarks }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="certificate-number">
                Certificate No: <strong>{{ $certificate_no }}</strong>
            </div>
            <div class="generated-date">
                Generated on {{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}
            </div>
        </div>
    </div>
</body>

</html>
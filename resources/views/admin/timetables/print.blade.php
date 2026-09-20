<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Timetable — {{ $timetable->batch->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 12px; color: #000; padding: 30px; }

        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .header h3 { font-size: 14px; font-weight: bold; margin-bottom: 16px; }
        .header-info { text-align: left; margin-bottom: 16px; }
        .header-info table { width: 100%; }
        .header-info td { padding: 2px 8px; font-size: 12px; }
        .header-info td:first-child { font-weight: bold; width: 120px; }

        .timetable { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .timetable th {
            border: 1px solid #000; padding: 8px 6px;
            text-align: center; font-size: 11px;
            background: #d0d0d0; font-weight: bold;
        }
        .timetable td {
            border: 1px solid #000; padding: 8px 6px;
            text-align: center; font-size: 11px; min-height: 35px;
        }
        .timetable .time-col { font-weight: bold; text-align: center; background: #f0f0f0; }
        .timetable .break-row td { background: #e8e8e8; font-style: italic; }
        .do-text { font-size: 11px; }

        .subject-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .subject-table th {
            border: 1px solid #000; padding: 7px 8px;
            background: #d0d0d0; font-size: 11px; font-weight: bold;
        }
        .subject-table td { border: 1px solid #000; padding: 7px 8px; font-size: 11px; }
        .subject-table .total-row td { font-weight: bold; background: #f0f0f0; }

        .signatures { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { text-align: center; width: 200px; }
        .signature-line { border-top: 1px solid #000; margin-bottom: 6px; }
        .signature-label { font-size: 11px; font-weight: bold; }

        @media print {
            body { padding: 15px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:20px;">
    <button onclick="window.print()"
        style="background:#0a1e5e;color:white;border:none;padding:10px 24px;border-radius:8px;font-size:14px;cursor:pointer;">
        <i>🖨</i> Print Timetable
    </button>
    <button onclick="window.close()"
        style="background:#64748b;color:white;border:none;padding:10px 24px;border-radius:8px;font-size:14px;cursor:pointer;margin-left:8px;">
        Close
    </button>
</div>

<div class="header">
    <h2>Class Time Table — {{ $timetable->academic_year }} {{ $timetable->semester }}</h2>
    <h3>Advanced Technological Institute — Dambawela Premises</h3>
</div>

<div class="header-info">
    <table>
        <tr>
            <td>Course</td>
            <td>: Higher National Diploma in Information Technology (Full Time)</td>
        </tr>
        <tr>
            <td>Year</td>
            <td>: {{ $timetable->batch->name }}</td>
        </tr>
        <tr>
            <td>Effective Date</td>
            <td>: {{ \Carbon\Carbon::parse($timetable->effective_date)->format('Y / m / d') }}</td>
        </tr>
    </table>
</div>

<!-- TIMETABLE GRID -->
<table class="timetable">
    <thead>
        <tr>
            <th style="width:100px;">Time</th>
            @foreach($allDays as $day)
            <th>{{ $day }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($timeSlots as $startTime => $label)
        @if($startTime === '12.30')
        <tr class="break-row">
            <td class="time-col">12.30 - 1.00</td>
            @foreach($allDays as $day)
            <td></td>
            @endforeach
        </tr>
        @else
        <tr>
            <td class="time-col">
                @php
                    $times = [
                        '8.30'=>'8.30 - 9.30','9.30'=>'9.30 - 10.30',
                        '10.30'=>'10.30 - 11.30','11.30'=>'11.30 - 12.30',
                        '13.00'=>'1.00 - 2.00','14.00'=>'2.00 - 3.00',
                        '15.00'=>'3.00 - 4.00','16.00'=>'4.00 - 5.00',
                    ];
                @endphp
                {{ $times[$startTime] ?? $startTime }}
            </td>
            @foreach($allDays as $day)
            <td>
                @if(isset($grid[$startTime][$day]) && $grid[$startTime][$day])
                    @php $slot = $grid[$startTime][$day]; @endphp
                    @if($slot->is_continuation)
                        <span class="do-text">-Do-</span>
                    @else
                        {{ $slot->subject->code }}
                    @endif
                @endif
            </td>
            @endforeach
        </tr>
        @endif
        @endforeach
    </tbody>
</table>

<!-- SUBJECT TABLE -->
<table class="subject-table">
    <thead>
        <tr>
            <th rowspan="2">Subject Code</th>
            <th rowspan="2">Subject</th>
            <th rowspan="2">Lecturer</th>
            <th colspan="2">Hours</th>
        </tr>
        <tr>
            <th>L</th>
            <th>P</th>
        </tr>
    </thead>
    <tbody>
        @php $totalHours = 0; @endphp
        @foreach($uniqueSubjects as $item)
        @php
            $totalHours += $item['hours'];
        @endphp
        <tr>
            <td>{{ $item['subject']->code }}</td>
            <td>{{ $item['subject']->name }}</td>
            <td>{{ $item['subject']->lecturer->name ?? '—' }}</td>
            <td>{{ $item['hours'] }}</td>
            <td></td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3" style="text-align:right;">Total</td>
            <td>{{ $totalHours }}</td>
            <td></td>
        </tr>
    </tbody>
</table>

<div class="signatures">
    <div class="signature-box">
        <div class="signature-line"></div>
        <div class="signature-label">Head of Division Signature</div>
    </div>
    <div class="signature-box">
        <div class="signature-line"></div>
        <div class="signature-label">Director</div>
    </div>
</div>

</body>
</html>
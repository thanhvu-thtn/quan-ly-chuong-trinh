<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết Chuyên đề</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; }
        h2 { text-align: center; text-transform: uppercase; margin-bottom: 20px; }
        .info-table { width: 100%; margin-bottom: 20px; border: none; }
        .info-table td { border: none; padding: 5px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>CHI TIẾT CHUYÊN ĐỀ</h2>
    
    <table class="info-table">
        <tr>
            <td style="width: 40%;">Chuyên đề: {{ $topic->name }}</td>
            <td style="width: 30%;">Loại: {{ $topic->topicType->name }}</td>
            <td style="width: 30%;">Tổng số tiết: {{ $topic->total_periods }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">STT</th>
                <th style="width: 70%;">Nội dung</th>
                <th style="width: 15%;">Số tiết</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topic->contents as $index => $content)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $content->name }}</td>
                    <td class="text-center">{{ $content->periods }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">Chưa có nội dung nào.</td></tr>
            @endforelse
            
            @if($topic->contents->count() > 0)
                <tr>
                    <td colspan="2" class="text-right" style="font-weight: bold; font-style: italic;">Tổng cộng: {{ $topic->contents->count() }} nội dung</td>
                    <td class="text-center" style="font-weight: bold;">{{ $topic->contents->sum('periods') }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
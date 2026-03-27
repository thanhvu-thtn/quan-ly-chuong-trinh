<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Nội dung</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; }
        h2 { text-align: center; margin-bottom: 20px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; text-align: center; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Danh sách Nội dung Chuyên đề</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Khối</th>
                <th style="width: 30%;">Tên chuyên đề</th>
                <th style="width: 15%;">Loại chuyên đề</th>
                <th style="width: 10%;">TT</th>
                <th style="width: 35%;">Nội dung</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($contents as $content)
                <tr>
                    <td class="text-center">Lớp {{ $content->topic->grade }}</td>
                    <td>{{ $content->topic->name }}</td>
                    <td class="text-center">{{ $content->topic->topicType->name }}</td>
                    <td class="text-center">{{ $content->order }}</td>
                    <td>{{ $content->name }}</td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
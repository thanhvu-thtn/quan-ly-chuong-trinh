<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Danh sách Chuyên đề</title>
    <style>
        /* CSS cơ bản để file PDF và Word hiển thị bảng có viền đen */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 14px;
        }

        h2 {
            text-align: center;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>
        DANH SÁCH CHUYÊN ĐỀ {{ $topicTypeName ? mb_strtoupper($topicTypeName, 'UTF-8') : '' }} VẬT LÝ
        {{ $selectedGrade ? 'LỚP ' . $selectedGrade : 'TOÀN CẤP' }}
    </h2>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Khối</th>
                <th style="width: 10%;">Thứ tự</th>
                <th>Tên chuyên đề</th>
                <th style="width: 20%;">Phân loại</th>
                <th style="width: 15%;">Số tiết</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topics as $topic)
                <tr>
                    <td class="text-center">{{ $topic->grade }}</td>
                    <td class="text-center">{{ $topic->order }}</td>
                    <td>{{ $topic->name }}</td>
                    <td class="text-center">{{ $topic->topicType->name }}</td>
                    <td class="text-center">{{ $topic->total_periods }}</td>
                </tr>
            @endforeach
            @if($topics->count() > 0)
                <tr>
                    <td colspan="4" style="text-align: right; padding-right: 15px; font-weight: bold; font-style: italic;">
                        Tổng cộng: {{ $topics->count() }} chuyên đề
                    </td>
                    <td class="text-center" style="font-weight: bold;">
                        {{ $topics->sum('total_periods') }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</body>

</html>

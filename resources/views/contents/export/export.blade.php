<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14pt;
        }

        h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        /* Định dạng cho công thức Toán Pandoc */
        .math.inline {
            font-style: italic;
        }
    </style>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
</head>

<body>

    <h2>{{ $title }}</h2>

    <table>
        <thead>
            <tr>
                <th width="8%">Lớp</th>
                <th width="12%">Loại</th>
                <th width="20%">Tên chuyên đề</th>
                <th width="20%">Nội dung</th>
                <th width="8%">Số tiết</th>
                <th width="32%">Yêu cầu cần đạt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contents as $content)
                <tr>
                    <td class="text-center">{{ $content->topic->grade }}</td>
                    <td class="text-center">{{ $content->topic->topicType->name }}</td>
                    <td>{{ $content->topic->name }}</td>
                    <td>{{ $content->name }}</td>
                    <td class="text-center">{{ $content->periods }}</td>
                    <td>
                        @if ($content->objectives->count() > 0)
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach ($content->objectives as $objective)
                                    @php
                                        // Tìm thẻ span class="math-tex" và bọc nội dung bên trong bằng dấu $...$
                                        //Mã cũ
                                        /* $parsedMath = preg_replace(
                                            '/<span[^>]*class="math-tex"[^>]*>(.*?)<\/span>/is',
                                            '\$$1\$',
                                            $objective->description,
                                        ); */

                                        //Mã mới
                                        $result = preg_replace_callback(
                                            // Regex tìm thẻ span có class math-tex và bắt lấy nội dung bên trong
                                            '/<span[^>]*class=["\']math-tex["\'][^>]*>(.*?)<\/span>/is',
                                            function ($matches) {
                                                $content = $matches[1]; // Nội dung nằm giữa cặp thẻ <span>...</span>

                                                // Loại bỏ khoảng trắng ở 2 đầu (đề phòng trường hợp HTML có dấu cách thừa)
                                                $trimmedContent = trim($content);

                                                // Kiểm tra nếu nội dung bắt đầu bằng \( và kết thúc bằng \)
                                                if (
                                                    str_starts_with($trimmedContent, '\(') &&
                                                    str_ends_with($trimmedContent, '\)')
                                                ) {
                                                    // Cắt bỏ 2 ký tự đầu '\(' và 2 ký tự cuối '\)', sau đó bọc lại bằng $
                                                    $innerMath = substr($trimmedContent, 2, -2);
                                                    // QUAN TRỌNG: Cắt bỏ khoảng trắng thừa ở 2 đầu của công thức
                                                    // Để đảm bảo chuẩn Pandoc: $math$ thay vì $ math $
                                                    $innerMath = trim($innerMath);
                                                    return '$' . $innerMath . '$';
                                                }

                                                // Trường hợp ngược lại (ví dụ như \[a\]), giữ nguyên nội dung bên trong
                                                return $content;
                                            },
                                            $objective->description,
                                        );
                                    @endphp
                                    <li>{!! $result !!}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="fw-bold">
        Tổng số chuyên đề: {{ $totalTopics }} <br>
        Tổng số tiết: {{ $totalPeriods }} tiết
    </p>

</body>

</html>

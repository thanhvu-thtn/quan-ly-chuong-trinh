<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TopicType;
use App\Models\Topic;
use App\Models\Content;

class PhysicsCurriculumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. TẠO 3 LOẠI CHUYÊN ĐỀ
        $typeCoBan = TopicType::create([
            'name' => 'Cơ bản',
            'description' => 'Bắt buộc đối với mọi học sinh.'
        ]);

        $typeNangCao = TopicType::create([
            'name' => 'Nâng cao',
            'description' => 'Chuyên đề học tập tự chọn (chỉ một số lớp học).'
        ]);

        $typeChuyen = TopicType::create([
            'name' => 'Chuyên',
            'description' => 'Bắt buộc đối với học sinh các lớp chuyên Vật lý.'
        ]);

        // ==========================================
        // 2. TẠO DỮ LIỆU MẪU CHO LỚP 10
        // ==========================================

        // --- A. Chuyên đề Cơ bản (Ví dụ: Động học - 10 tiết) ---
        $topicDongHoc = Topic::create([
            'topic_type_id' => $typeCoBan->id,
            'name'          => 'Động học',
            'grade'         => 10,
            'total_periods' => 10, // Tổng số tiết là 10
        ]);

        // Tạo nội dung cho chuyên đề Động học (Tổng số tiết phải đúng bằng 10)
        Content::create([
            'topic_id'   => $topicDongHoc->id,
            'name'       => 'Mô tả chuyển động',
            'objectives' => 'Nêu được các khái niệm: vị trí, độ dịch chuyển, tốc độ, vận tốc. Phân biệt được quãng đường và độ dịch chuyển.',
            'periods'    => 4, // 4 tiết
        ]);

        Content::create([
            'topic_id'   => $topicDongHoc->id,
            'name'       => 'Chuyển động biến đổi đều và Sự rơi tự do',
            'objectives' => 'Viết được phương trình chuyển động. Giải bài tập rơi tự do và ném ngang.',
            'periods'    => 6, // 6 tiết (4 + 6 = 10)
        ]);


        // --- B. Chuyên đề Nâng cao/Tự chọn (Ví dụ: Trái đất và Bầu trời - 15 tiết) ---
        $topicTraiDat = Topic::create([
            'topic_type_id' => $typeNangCao->id,
            'name'          => 'Trái Đất và bầu trời',
            'grade'         => 10,
            'total_periods' => 15,
        ]);

        Content::create([
            'topic_id'   => $topicTraiDat->id,
            'name'       => 'Hệ Mặt Trời',
            'objectives' => 'Mô tả được cấu trúc của Hệ Mặt Trời, sự hình thành và chuyển động của các hành tinh.',
            'periods'    => 7,
        ]);

        Content::create([
            'topic_id'   => $topicTraiDat->id,
            'name'       => 'Xác định phương hướng',
            'objectives' => 'Biết cách xác định phương hướng bằng các chòm sao và la bàn.',
            'periods'    => 8, // 7 + 8 = 15
        ]);


        // --- C. Chuyên đề Chuyên (Ví dụ: Động lực học chất điểm nâng cao - 20 tiết) ---
        $topicChuyenDongLuc = Topic::create([
            'topic_type_id' => $typeChuyen->id,
            'name'          => 'Động lực học chất điểm nâng cao',
            'grade'         => 10,
            'total_periods' => 20,
        ]);

        Content::create([
            'topic_id'   => $topicChuyenDongLuc->id,
            'name'       => 'Hệ quy chiếu phi quán tính',
            'objectives' => 'Hiểu và vận dụng được lực quán tính, lực Coriolis trong các hệ quy chiếu chuyển động có gia tốc.',
            'periods'    => 10,
        ]);

        Content::create([
            'topic_id'   => $topicChuyenDongLuc->id,
            'name'       => 'Bài toán va chạm phức tạp',
            'objectives' => 'Giải quyết được các bài toán va chạm đàn hồi và mềm trong không gian 2 chiều, 3 chiều.',
            'periods'    => 10, // 10 + 10 = 20
        ]);
        
        // Bạn có thể copy paste các khối code trên để tạo thêm dữ liệu cho lớp 11 và 12...
    }
}

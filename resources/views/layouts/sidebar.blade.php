<ul class="nav flex-column">

    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('users.*') ? 'active text-primary fw-bold' : 'text-dark' }}"
            href="{{ route('users.index') }}">
            👥 Quản lý người dùng
        </a>
    </li>

    @php
        // Biến kiểm tra xem người dùng có đang ở một trong các trang thuộc Chương trình khung không
        $isCurriculumActive =
            request()->routeIs('topic-types.*') || request()->routeIs('topics.*') || request()->routeIs('contents.*');
    @endphp

    <li class="nav-item">
        <a class="nav-link {{ $isCurriculumActive ? 'text-primary fw-bold' : 'text-dark collapsed' }}"
            data-bs-toggle="collapse" href="#collapseCurriculum" role="button"
            aria-expanded="{{ $isCurriculumActive ? 'true' : 'false' }}" aria-controls="collapseCurriculum">
            📚 Chương trình khung {!! $isCurriculumActive ? '▼' : '▶' !!}
        </a>

        <div class="collapse {{ $isCurriculumActive ? 'show' : '' }}" id="collapseCurriculum">
            <ul class="nav flex-column ms-3 border-start border-2 border-primary ps-2">
                <li class="nav-item mb-1 mt-1">
                    <a class="nav-link {{ request()->routeIs('topic-types.*') ? 'active text-primary fw-bold' : 'text-secondary' }} py-1"
                        href="{{ route('topic-types.index') }}">
                        🔹 Loại chuyên đề
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link {{ request()->routeIs('topics.*') ? 'active text-primary fw-bold' : 'text-secondary' }} py-1"
                        href="{{ route('topics.index') }}">
                        🔹 Chuyên đề theo lớp
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a class="nav-link text-secondary py-1" href="#">
                        🔹 Nội dung chuyên đề
                    </a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item mt-2">
        <a class="nav-link text-dark" href="#">
            🗓️ Phân phối chương trình
        </a>
    </li>

</ul>

<ul class="list-unstyled mb-0">
    @foreach ($peminjaman->riwayats as $r)
        <li class="d-flex gap-2 mb-2">
            <span class="dot dot-green mt-2"></span>
            <div>
                <span class="sp-badge {{ \App\Models\Peminjaman::badge($r->status) }}">{{ $r->status }}</span>
                <span class="small text-secondary">{{ $r->created_at->format('d M Y H:i') }}{{ $r->user ? ' · ' . $r->user->name : '' }}</span>
                @if ($r->catatan)<div class="small">{{ $r->catatan }}</div>@endif
            </div>
        </li>
    @endforeach
</ul>

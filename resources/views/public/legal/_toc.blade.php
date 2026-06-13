<div style="background:var(--ms-surface);border:1px solid var(--ms-border);border-radius:14px;
            padding:1.5rem;margin-bottom:2.5rem">
    <h5 style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;
               color:var(--ms-muted);margin-bottom:1rem">Table of Contents</h5>
    <ol style="margin:0;padding-left:1.25rem;display:flex;flex-direction:column;gap:0.4rem">
        @foreach($items as $i => $item)
        <li style="font-size:0.88rem">
            <a href="#s{{ $i + 1 }}"
               style="color:var(--ms-green);text-decoration:none;font-weight:500"
               onmouseover="this.style.textDecoration='underline'"
               onmouseout="this.style.textDecoration='none'">
                {{ $item }}
            </a>
        </li>
        @endforeach
    </ol>
</div>

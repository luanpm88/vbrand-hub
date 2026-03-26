{{-- Table of Contents — receives $toc array of [{level, id, text}] --}}
@if(!empty($toc) && count($toc) > 0)
<div class="kb-toc">
    <h4 class="kb-toc__title">Table of Contents</h4>
    <ul class="kb-toc__list">
        @foreach($toc as $item)
            <li>
                <a href="#{{ $item['id'] }}" class="{{ $item['level'] === 3 ? 'kb-toc__h3' : '' }}">
                    {{ $item['text'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tocLinks = document.querySelectorAll('.kb-toc__list a');
    if (tocLinks.length === 0) return;

    const headings = [];
    tocLinks.forEach(function(link) {
        const id = link.getAttribute('href').substring(1);
        const el = document.getElementById(id);
        if (el) headings.push({ el: el, link: link });
    });

    function updateActive() {
        let current = null;
        const scrollTop = window.scrollY + 100;

        headings.forEach(function(h) {
            if (h.el.offsetTop <= scrollTop) {
                current = h;
            }
        });

        tocLinks.forEach(function(l) { l.classList.remove('active'); });
        if (current) current.link.classList.add('active');
    }

    window.addEventListener('scroll', updateActive, { passive: true });
    updateActive();
});
</script>
@endpush

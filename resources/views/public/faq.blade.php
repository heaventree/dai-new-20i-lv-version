@extends('layouts.app')
@section('title', 'FAQ — Driver Assessments Ireland')
@section('meta_description', 'Find answers to common questions about our medical driving assessments, process, and legal compliance.')

@section('content')

@php
$fallbackCategories = ['General Questions','Assessment Process','Before Your Assessment','After Your Assessment'];
$fallbackFaqs = [
    ['category'=>'General Questions','q'=>'What is a Driving Assessment?','a'=>'A driving assessment is a professional evaluation used to determine a person\'s fitness to drive safely, often following a medical condition, a change in ability, or a recommendation from a healthcare professional.'],
    ['category'=>'General Questions','q'=>'Who Needs a Driving Assessment?','a'=>'Driving assessments are typically recommended for individuals with medical conditions that may affect their ability to drive safely (e.g. Stroke, Dementia, Parkinson\'s Disease, Diabetes, or Cardiovascular Disorders), older drivers, or anyone referred by their GP, consultant, or occupational therapist. Family members may also request an on-road assessment if they have concerns about a loved one\'s driving ability.'],
    ['category'=>'General Questions','q'=>'How much does it cost?','a'=>'The standard fee is €' . $fee . '. Payment instructions are provided during the online booking process.'],
    ['category'=>'General Questions','q'=>'What if I decide not to go ahead?','a'=>'Please let us know as soon as possible if you wish to cancel or reschedule your assessment. A cancellation fee will apply if the assessment is cancelled within 48 hours of the scheduled assessment date. An administration fee may apply to assessments cancelled more than 48 hours before the scheduled assessment date.'],
    ['category'=>'General Questions','q'=>'Who are the assessors?','a'=>'Our assessors are trained professionals with backgrounds in driving instruction. They have specialist experience in assessing drivers with medical conditions and are committed to conducting assessments in a fair, supportive, and respectful manner.'],
    ['category'=>'Assessment Process','q'=>'How Do I Refer a Patient, Family Member, or Myself for an Assessment?','a'=>'Healthcare professionals can refer patients using our secure, encrypted HCP Referral Form or by emailing us directly via Healthmail. We are a registered Healthmail white-site user and can securely send and receive referrals through this service. Family members and individuals do not require a referral and can contact us directly. Once a booking number has been issued by our team, they can arrange an assessment using the "Book an Assessment" tab.'],
    ['category'=>'Assessment Process','q'=>'How Do I Book an Assessment?','a'=>'Once you have received a booking number from our team, you can use the "Book an Assessment" tab to complete your booking. Alternatively, you can contact us directly by email. Our team will be happy to answer any questions and guide you through the assessment process.'],
    ['category'=>'Assessment Process','q'=>'What Does the Assessment Involve?','a'=>'The assessment involves driving on routes that are familiar to the client and regularly used in their day-to-day life. The aim is to help clients feel as comfortable and confident as possible throughout the assessment process. On the day of the assessment, one of our assessors will visit the client\'s home, and the assessment will typically take place in the client\'s own vehicle. The assessment usually lasts just under one hour. During this time, the assessor will observe the client\'s driving and take notes. These observations are then reviewed by our office team, who prepare a comprehensive final report. The final report will be provided to the client and any relevant healthcare professionals involved in their care.'],
    ['category'=>'Assessment Process','q'=>'How Long Does It Take?','a'=>'Most assessments take just under one hour to complete.'],
    ['category'=>'Assessment Process','q'=>'Where Does the Assessment Take Place?','a'=>'Assessments are conducted on routes that the client is most likely to use regularly. One of our fully qualified assessors will attend the client\'s residence on the scheduled appointment date. The assessment is usually completed in the client\'s own vehicle. However, if this is not possible, we may be able to provide an assessor\'s vehicle, subject to availability.'],
    ['category'=>'Before Your Assessment','q'=>'Do I need a valid driving licence?','a'=>'Yes, you must hold a valid driving licence to take part in the on-road assessment. Please note that the assessor will need to see your physical licence on the day of the assessment. If your licence has expired or is due to expire, please contact us as soon as possible. We can put you in touch with an RSA contact who may be able to assist you in applying for a Temporary Learner Permit on medical grounds. This is a temporary permit intended to allow you to complete the assessment.'],
    ['category'=>'Before Your Assessment','q'=>'What should I bring on the day of the assessment?','a'=>'You must bring your valid driving licence or learner permit with you. It is also important to ensure that your vehicle is roadworthy, taxed, insured, and has a valid NCT certificate (where applicable). A full cancellation fee of €' . $fee . ' will apply if the assessor arrives and the assessment cannot proceed because any of the above requirements have not been met. If you wear glasses or hearing aids while driving, please ensure you wear them during the assessment.'],
    ['category'=>'Before Your Assessment','q'=>'Can someone come with me?','a'=>'We recommend that clients undertake the assessment alone, as having a third person in the vehicle may increase feelings of anxiety or nervousness. However, if you would feel more comfortable with the support of a family member or friend, they are welcome to accompany you during the assessment.'],
    ['category'=>'Before Your Assessment','q'=>'What if I have not driven in a while?','a'=>'Many of our clients referred for a driving assessment have not driven for some time due to illness, injury, or medical treatment. Our assessors are understanding and experienced in supporting individuals in this situation and will do their best to help you feel at ease throughout the assessment. We recommend completing a short practice drive beforehand (with a qualified driver, if required) to help rebuild your confidence and familiarity with driving.'],
    ['category'=>'After Your Assessment','q'=>'What happens after the assessment?','a'=>'A report will be completed and sent to both you and the individual\'s doctor within five working days after the assessment. The On-Road Driving Assessment forms one part of the overall process of determining a person\'s fitness to drive.'],
    ['category'=>'After Your Assessment','q'=>'Who makes the final decision about whether I can drive again?','a'=>'The final decision about returning to driving rests with the individual\'s Healthcare Practitioner. Our On-Road Driving Assessment provides valuable insight into your driving ability and is an important part of the overall assessment process. Your GP, consultant, or other healthcare provider will also consider additional factors, including your medical history and the results of any Off-Road Assessments, such as cognitive evaluations. We strongly recommend discussing your assessment report with your healthcare provider before making any decisions about returning to driving.'],
    ['category'=>'After Your Assessment','q'=>'Can I drive straight after the assessment?','a'=>'No. You should always consult your healthcare provider before making any decision about returning to driving.'],
];

$cmsItems = $page->content_json['items'] ?? [];
$cmsCategories = $page->content_json['categories'] ?? [];
$faqs = count($cmsItems) ? $cmsItems : $fallbackFaqs;
$categories = count($cmsCategories) ? $cmsCategories : $fallbackCategories;
@endphp

{{-- HERO --}}
<section class="py-10" style="background:radial-gradient(ellipse at 60% 50%,hsl(215 81% 23%) 0%,hsl(215 81% 14%) 100%)">
    <div class="container mx-auto px-6 text-center">
        <h1 class="font-display text-5xl md:text-6xl font-extrabold text-white mb-4"
            style="letter-spacing:-0.02em;font-family:Manrope,sans-serif">FAQ</h1>
        <p class="text-white/70 text-base md:text-lg mb-10 max-w-md mx-auto leading-relaxed">
            Find answers to common questions about our medical driving assessments, process, and legal compliance.
        </p>
        <div class="max-w-lg mx-auto relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="search" id="faq-search" placeholder="Search for questions, topics, or keywords..."
                   class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-white text-gray-800 text-base outline-none placeholder-gray-400"
                   style="box-shadow:0px 4px 16px rgba(0,0,0,0.12)">
        </div>
    </div>
</section>

{{-- VIDEO --}}
@php
    $videoFile = $page->content_json['video_file'] ?? '';
    $videoUrl  = $page->content_json['video_url'] ?? '';
    $embedUrl  = '';
    if (!$videoFile) {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/', $videoUrl, $m)) {
            $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
        } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
            $embedUrl = 'https://player.vimeo.com/video/' . $m[1];
        }
    }
@endphp
@if($videoFile || $embedUrl)
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto">
            <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:0.75rem;box-shadow:0 4px 16px rgba(0,0,0,0.1)">
                @if($videoFile)
                <video controls playsinline preload="metadata"
                       style="position:absolute;top:0;left:0;width:100%;height:100%;background:#000;border-radius:0.75rem">
                    <source src="{{ asset(ltrim($videoFile, '/')) }}" type="video/{{ pathinfo($videoFile, PATHINFO_EXTENSION) }}">
                </video>
                @else
                <iframe src="{{ $embedUrl }}" frameborder="0" allowfullscreen
                        style="position:absolute;top:0;left:0;width:100%;height:100%"></iframe>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- MAIN --}}
<section class="py-16 bg-gray-50" style="min-height:500px">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-[240px_1fr] gap-10">

            {{-- Sidebar --}}
            <aside>
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Categories</p>
                <nav class="flex flex-col gap-1" id="faq-cat-nav">
                    @foreach($categories as $i => $cat)
                    @php $count = count(array_filter($faqs, fn($f) => ($f['category'] ?? '') === $cat)); @endphp
                    <button class="faq-cat-btn flex items-center justify-between w-full text-left px-4 py-3 rounded-lg text-sm font-medium transition-all"
                            data-cat="{{ $cat }}"
                            style="{{ $i === 0 ? 'background:hsl(215 81% 23%);color:white' : 'background:transparent;color:hsl(215 20% 48%)' }}">
                        <span>{{ $cat }}</span>
                        <span class="text-xs rounded-full px-2 py-0.5 font-semibold ml-2 shrink-0"
                              style="{{ $i === 0 ? 'background:rgba(255,255,255,0.2);color:white' : 'background:hsl(215 20% 92%);color:hsl(215 20% 48%)' }}">
                            {{ $count }}
                        </span>
                    </button>
                    @endforeach
                </nav>
            </aside>

            {{-- Accordion --}}
            <div class="bg-white rounded-xl px-6 py-2" style="box-shadow:0px 4px 12px rgba(25,28,29,0.06)">
                @foreach($faqs as $i => $faq)
                @if(!empty($faq['q']))
                <div class="faq-item border-b border-gray-100 last:border-0"
                     data-cat="{{ $faq['category'] ?? '' }}"
                     data-q="{{ strtolower($faq['q']) }}"
                     data-a="{{ strtolower($faq['a'] ?? '') }}">
                    <button class="faq-trigger w-full flex items-center justify-between py-5 text-left group"
                            aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                        <span class="font-display font-semibold text-base text-gray-900 group-hover:text-primary transition-colors pr-4"
                              style="letter-spacing:-0.01em;font-family:Manrope,sans-serif">
                            {{ $faq['q'] }}
                        </span>
                        <svg class="faq-chevron h-5 w-5 text-gray-400 shrink-0 transition-transform duration-200 {{ $i === 0 ? 'rotate-180' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="faq-answer pb-5 {{ $i === 0 ? '' : 'hidden' }}">
                        <p class="text-gray-500 text-base leading-relaxed">{{ $faq['a'] ?? '' }}</p>
                    </div>
                </div>
                @endif
                @endforeach
                <div id="faq-no-results" class="py-10 text-center text-gray-400 text-sm hidden">
                    No questions match your search. Try a different keyword.
                </div>
            </div>

        </div>
    </div>
</section>

{{-- STILL HAVE QUESTIONS --}}
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="rounded-2xl px-8 py-14 text-center relative overflow-hidden"
             style="background:radial-gradient(ellipse at 70% 50%,hsl(215 81% 23%) 0%,hsl(215 81% 14%) 100%)">
            <div class="absolute inset-0 pointer-events-none opacity-10"
                 style="background-image:repeating-linear-gradient(-45deg,rgba(255,255,255,0.08) 0px,rgba(255,255,255,0.08) 1px,transparent 1px,transparent 28px)"></div>
            <div class="relative z-10">
                <h2 class="font-display text-3xl md:text-4xl font-extrabold text-white mb-4"
                    style="letter-spacing:-0.02em;font-family:Manrope,sans-serif">Still have questions?</h2>
                <p class="text-white/70 text-base mb-8 max-w-md mx-auto leading-relaxed">
                    Our clinical support team is available Monday through Friday, 9:00 AM to 5:00 PM to assist with your specific concerns.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('contact') }}"
                       class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-bold text-base transition-all hover:brightness-105 active:scale-95"
                       style="background:#ffcf00;color:#231b00">
                        Contact Us Now
                    </a>
                    <a href="mailto:info@dai.ie"
                       class="inline-flex items-center gap-2 px-7 py-3.5 rounded-xl font-bold text-base text-white transition-all hover:bg-white/10 active:scale-95"
                       style="border:2px solid rgba(255,255,255,0.35)">
                        Email Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function(){
    var allFaqs   = document.querySelectorAll('.faq-item');
    var catBtns   = document.querySelectorAll('.faq-cat-btn');
    var searchEl  = document.getElementById('faq-search');
    var noResults = document.getElementById('faq-no-results');
    var activeCategory = catBtns[0] ? catBtns[0].dataset.cat : '';

    function updateDisplay(){
        var q = searchEl.value.trim().toLowerCase();
        var visible = 0;
        allFaqs.forEach(function(item){
            var catMatch = item.dataset.cat === activeCategory;
            var qMatch   = !q || item.dataset.q.includes(q) || item.dataset.a.includes(q);
            var show = catMatch && qMatch;
            item.classList.toggle('hidden', !show);
            if(show) visible++;
        });
        noResults.classList.toggle('hidden', visible > 0);
    }

    catBtns.forEach(function(btn){
        btn.addEventListener('click', function(){
            activeCategory = btn.dataset.cat;
            catBtns.forEach(function(b){
                var isActive = b === btn;
                b.style.background = isActive ? 'hsl(215 81% 23%)' : 'transparent';
                b.style.color      = isActive ? 'white'            : 'hsl(215 20% 48%)';
                var badge = b.querySelector('span');
                if(badge){
                    badge.style.background = isActive ? 'rgba(255,255,255,0.2)' : 'hsl(215 20% 92%)';
                    badge.style.color      = isActive ? 'white'                 : 'hsl(215 20% 48%)';
                }
            });
            updateDisplay();
            // Open first visible item
            var firstVisible = Array.from(allFaqs).find(function(el){ return !el.classList.contains('hidden'); });
            if(firstVisible){
                var ans = firstVisible.querySelector('.faq-answer');
                var chev = firstVisible.querySelector('.faq-chevron');
                if(ans && ans.classList.contains('hidden')){ ans.classList.remove('hidden'); chev && chev.classList.add('rotate-180'); }
            }
        });
    });

    searchEl && searchEl.addEventListener('input', updateDisplay);

    // Accordion
    document.querySelectorAll('.faq-trigger').forEach(function(btn){
        btn.addEventListener('click', function(){
            var ans  = btn.nextElementSibling;
            var chev = btn.querySelector('.faq-chevron');
            var open = !ans.classList.contains('hidden');
            ans.classList.toggle('hidden', open);
            chev && chev.classList.toggle('rotate-180', !open);
        });
    });

    // Init — show first category
    updateDisplay();
})();
</script>
@endpush

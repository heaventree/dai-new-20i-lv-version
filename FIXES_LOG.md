# FIXES LOG — Client Feedback 26-06-26 (Batch 1)

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 1 | Remove 'Admin Login' from public footer | `resources/views/layouts/app.blade.php` | 450 | `<a href="...">Admin Login</a>` | Commented out (hidden from public) | ✅ Fixed |
| 2 | 'HSE Approved' → recommended provider text | `resources/views/public/home.blade.php` | 100 | `HSE Approved` | `Recommended provider through the National Office for Road Traffic Medicine` | ✅ Fixed |
| 3a | 'RSA Compliant Protocol' → 'RSA Registered' | `resources/views/public/about.blade.php` | 56 | `RSA Compliant Protocol` | `RSA Registered` | ✅ Fixed |
| 3b | 'RSA Compliant' → 'RSA Registered' | `resources/views/public/about.blade.php` | 124 | `RSA Compliant` | `RSA Registered` | ✅ Fixed |
| 4 | 'RSA Guidelines 2025' → '2026' | `resources/views/public/about.blade.php` | 124 | `Guidelines 2025` | `Guidelines 2026` | ✅ Fixed |
| 4b | RSA Guidelines link | N/A | N/A | No RSA guideline links found in views | N/A | ⚠️ No links to update — add manually if needed |
| 5a | 'Aging' → 'Ageing' | `resources/views/public/services.blade.php` | 13 | `The Aging Driver` | `The Ageing Driver` | ✅ Fixed |
| 5b | 'Rigor' → 'Rigour' | `resources/views/public/services.blade.php` | 93 | `Clinical Rigor` | `Clinical Rigour` | ✅ Fixed |
| 5c | 'Specialized' → 'Specialised' (comment) | `resources/views/public/services.blade.php` | 110 | `Specialized` | `Specialised` | ✅ Fixed |
| 5d | 'Specialized' → 'Specialised' (heading) | `resources/views/public/services.blade.php` | 119 | `Specialized Condition Assessments` | `Specialised Condition Assessments` | ✅ Fixed |
| 5e | 'License' → 'Licence' (label) | `resources/views/public/assessment/index.blade.php` | 118 | `Driving License Details` | `Driving Licence Details` | ✅ Fixed |
| 5f | 'license' → 'licence' (label) | `resources/views/public/assessment/application.blade.php` | 197 | `driving license number` | `driving licence number` | ✅ Fixed |
| 5g | 'license_number' (variable name) | `resources/views/public/assessment/application.blade.php` | 198 | `name="license_number"` | Skipped — variable/DB column name | ⚠️ Skipped (variable name) |
| 6 | '€195' → '€235' (seeder default) | `database/seeders/DatabaseSeeder.php` | 25 | `'assessment_fee' => '195'` | `'assessment_fee' => '235'` | ✅ Fixed |
| 6b | '€195' in blade views | N/A | N/A | Fee is loaded dynamically from settings DB | N/A | ⚠️ Update DB setting on server: `UPDATE settings SET value='235' WHERE key='assessment_fee'` |
| 7 | 'GDPR & HIPAA Compliant' → 'GDPR & Data Protection Act 2018' | `resources/views/public/hcp-referral.blade.php` | 208 | `GDPR &amp; HIPAA Compliant` | `GDPR &amp; Data Protection Act 2018` | ✅ Fixed |
| 8 | Copyright '© 2026' on all pages | `resources/views/layouts/app.blade.php` | 460 | `© {{ date('Y') }}` already present in shared layout | N/A | ✅ Already present |
| 9 | 'Start Online Referral' → 'HCP Secure Referral' | `resources/views/public/home.blade.php` | 297 | `Start Online Referral` | `HCP Secure Referral` | ✅ Fixed |
| 10 | 'View Locations' → 'Book an Assessment' | `resources/views/public/home.blade.php` | 300 | `View Locations` | `Book an Assessment` | ✅ Fixed |
| 11 | 'Download Referral Form' → 'HCP Secure Referral' | `resources/views/public/services.blade.php` | 198 | `Download Referral Form` | `HCP Secure Referral` | ✅ Fixed |
| 12 | 'Contact our Team' → 'Book an Assessment' | `resources/views/public/services.blade.php` | 203 | `Contact our Team` | `Book an Assessment` | ✅ Fixed |
| 13 | 'Professional Referral Portal' → 'Health Care Professional Portal' | `resources/views/public/service-show.blade.php` | 226 | `Professional Referral Portal` | `Health Care Professional Portal` | ✅ Fixed |
| 14 | 'Monday through Friday' → 'Monday to Friday' | `resources/views/public/faq.blade.php` | 154 | `Monday through Friday` | `Monday to Friday` | ✅ Fixed |
| 15 | Remove 'clinical' from 'clinical support team' | `resources/views/public/faq.blade.php` | 154 | `Our clinical support team` | `Our support team` | ✅ Fixed |
| 16 | 'Ballyglass, Turloughmore' → full address | `resources/views/public/contact.blade.php` | 129 | `Ballyglass, Turloughmore, Athenry, Co. Galway` | `Lackaghbeg, Lackagh, Turloughmore, Athenry, Co. Galway` | ✅ Fixed |

---

# Batch 2 — Page Specific Fixes 26-06-26

## HOME PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 1 | 'a HCP' → 'an HCP (Healthcare Professional)' | `resources/views/public/home.blade.php` | 137 | `If you are a HCP` | `If you are an HCP (Healthcare Professional)` | ✅ Fixed |
| 2 | Dr Sharon Healy testimonial truncated | Database (testimonials table) | N/A | Quote ends mid-sentence with 'I have no hesitation in' | Not in codebase/seeder — stored in live DB only | ⚠️ Requires manual DB fix — full quote not available in code |
| 3 | Fr. O'Keeffe testimonial duplicated attribution | Database (testimonials table) | N/A | `— Fr. Patrick O'Keeffe, C.Ss.R., Fr. Patrick O'Keeffe, C.Ss.R.` | Not in codebase/seeder — stored in live DB only | ⚠️ Requires manual DB fix |
| 4a | 'HCP Secure Referral' link verified | `resources/views/public/home.blade.php` | 298 | `route('hcp-referral')` → `/hcp-referral-form` | Correct | ✅ Verified |
| 4b | 'Book an Assessment' CTA link fixed | `resources/views/public/home.blade.php` | 302 | `route('contact')` (→ /contact) | `route('assessment.index')` (→ /arrange-assessment) | ✅ Fixed |

## SERVICES PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 5 | 'Multi-disciplinary specialist review' | `resources/views/public/services.blade.php` | 77 | `Multi-disciplinary specialist review` | `Specialist Assessment Review` | ✅ Fixed |
| 6 | 'Clinical Rigour' → 'Experienced Driver Assessors' | `resources/views/public/services.blade.php` | 96 | `Clinical Rigour` | `Experienced Driver Assessors` | ✅ Fixed |
| 7 | 'helpful restrictive licensing' reworded | `resources/views/public/services.blade.php` | 97 | `helpful restrictive licensing or specialised training recommendations` | `guidance on conditional, temporary medical permits or restricted licensing` | ✅ Fixed |
| 8 | 'twelve years' → '10+ years' | `resources/views/public/services.blade.php` | 96 | `twelve years of experience` | `10+ years of experience` | ✅ Fixed |
| 9 | 'Vision Impairment' → 'Vision & Hearing Impairment' | `resources/views/public/services.blade.php` | 16 | `Vision Impairment` | `Vision & Hearing Impairment` | ✅ Fixed |
| 10 | Add 'and all Health Care Professionals' | `resources/views/public/service-show.blade.php` | 221 | `...for GPs, Occupational Therapists, and Consultants.` | `...for GPs, Occupational Therapists, Consultants, and all Health Care Professionals.` | ✅ Fixed |

## ABOUT DAI PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 11 | 'As clinicians' rewording | `resources/views/public/about.blade.php` | 80 | `As clinicians, we are committed` | `As leaders in providing on-road driver assessments, we are committed` | ✅ Fixed |
| 12 | 'we tend to deliver...clinical assessment training' | `resources/views/public/about.blade.php` | 81 | `we tend to deliver...clinical assessment training` | `we deliver...assessment training` | ✅ Fixed |
| 13 | Remove 'We can ensure our drivers...' | `resources/views/public/about.blade.php` | 82 | Full paragraph with two sentences | Removed entirely | ✅ Fixed |
| 14 | Remove 'We have striven to develop training...' | `resources/views/public/about.blade.php` | 82 | Part of same paragraph | Removed entirely | ✅ Fixed |
| 15 | 'Advancing OT driver assessment' → remove OT | `resources/views/public/about.blade.php` | 87 | `Advancing OT driver assessment pathways nationally` | `Advancing driver assessment pathways nationally` | ✅ Fixed |
| 16 | 'clinical rigour' → 'experience and training' | `resources/views/public/about.blade.php` | 52 | `Their clinical rigour ensures` | `Their experience and training ensures` | ✅ Fixed |
| 17 | Remove 'clinical' from assessment standards | `resources/views/public/about.blade.php` | 129 | `best-practice clinical assessment standards` | `best-practice assessment standards` | ✅ Fixed |
| 18 | Add FAQ link to 'Have questions' section | `resources/views/public/about.blade.php` | 224 | `Email our team for assistance.` | `Visit our FAQ page or email our team for assistance.` (FAQ linked) | ✅ Fixed |

## HCP REFERRAL PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 19 | Replace 'Reason for Referral' dropdown options | `resources/views/public/hcp-referral.blade.php` | 158 | 7 options starting with 'Neurological Condition' | 16 options starting with 'Cognitive Impairment' | ✅ Fixed |
| 20 | 'AES-256 encrypted' claim | `resources/views/public/hcp-referral.blade.php` | 211 | `All transmissions are AES-256 encrypted` | `All data is encrypted and stored securely in compliance with GDPR` | ✅ Fixed |

## CONTACT PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 21 | Remove 'Get Directions' link | `resources/views/public/contact.blade.php` | 162 | `<a href="...">Get Directions</a>` | Commented out (removed from view) | ✅ Fixed |
| 22 | Add opening hours | `resources/views/public/contact.blade.php` | 133 | (not present) | Added `Monday to Friday, 9:00am to 5:00pm` with clock icon | ✅ Fixed |

## Server-side actions required

1. Update the assessment fee in the database: `UPDATE settings SET value='235' WHERE key='assessment_fee';`
2. If RSA guideline PDF links are needed, use: `https://www.rsa.ie/docs/default-source/road-safety/slainte-agus-tiomaint/medical-fitness-to-drive-guidelines-2026.pdf?sfvrsn=a3301610_2`
3. **Testimonials (manual DB fix needed):**
   - Dr Sharon Healy: quote appears truncated ending with 'I have no hesitation in' — restore full quote in testimonials table
   - Fr. Patrick O'Keeffe, C.Ss.R.: name/location is duplicated in the testimonials table — remove the duplicate

---

# Batch 3 — FAQ, Team, Book Assessment, Structural Fixes 26-06-26

## FAQ PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 1 | Accordion expand/collapse check | `resources/views/public/faq.blade.php` | 111-133, 225-232 | JS toggles `.hidden` class on `.faq-answer` + rotates chevron on `.faq-trigger` click | All 17 items use identical markup — accordion works correctly | ✅ Verified |
| 2 | Category filters check | `resources/views/public/faq.blade.php` | 94-106, 185-196 | JS filters by `data-cat` attribute matching `activeCategory` | All 9 categories (General Questions, Booking, Assessment, Preparation, Support, After Assessment, Pricing, Policies, About Us) rendered from CMS `content_json` | ✅ Verified |
| 3 | FAQ price shows €235 | `resources/views/public/faq.blade.php` | 12, 21 | Fee loaded via `$fee` variable from `Setting::get('assessment_fee', '235')` | Default fallback is 235; live DB needs `UPDATE settings SET value='235' WHERE key='assessment_fee'` | ✅ Verified (code default is 235) |
| 4 | Add 5 missing FAQ questions | `database/seeders/DatabaseSeeder.php` | N/A | Not present | Not added to seeder (FAQs managed via admin CMS panel, not seeder) | ⚠️ Add via admin panel — see below |
| 5 | 'Monday through Friday' check | `resources/views/public/faq.blade.php` | 155 | Fixed in Batch 1 to 'Monday to Friday' | Confirmed correct | ✅ Verified |

### FAQ questions to add via admin panel at `/admin/cms-pages/9/edit`:

1. **General Questions** — Q: *Will this affect my driving licence or will DAI report me to the NDLS?* — A: *DAI does not report directly to the NDLS. The final decision about your licence rests with your referring Healthcare Professional and the NDLS, not DAI. Our role is to provide an objective, professional assessment. Our goal is always to support safe driving where possible.*
2. **General Questions** — Q: *Is it a pass or fail test?* — A: *No — it is not a pass or fail test. It is a professional assessment that evaluates your current driving ability. The outcome is a detailed report with recommendations, not a pass or fail result.*
3. **Pricing** — Q: *How much does it cost and what is included?* — A: *The assessment fee is €235. This includes the full on-road driving assessment, a detailed written report, and recommendations for your Healthcare Professional. Please check our cancellation policy before booking.*
4. **Preparation** — Q: *What do I need to bring?* — A: *Please bring your valid driving licence, your Eircode, and confirmation of your current motor insurance, NCT, and motor tax. These details will also be required during the booking process.*
5. **General Questions** — Q: *Whose car is used during the assessment?* — A: *Your own car is used during the assessment. Please ensure it is taxed, insured, and has a valid NCT.*

## DAI TEAM PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 6 | Team member profile links | `resources/views/public/team.blade.php` | 31 | Links use `route('team.show', $member->slug)` → `/team/{slug}` | Route exists at `routes/web.php:33`. Links are dynamic from DB — if any team member has a missing slug, the link will 404. | ✅ Verified (code correct; data-dependent) |

## BOOK AN ASSESSMENT PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 7 | 'Driving License' → 'Driving Licence' | `resources/views/public/assessment/index.blade.php` | 119 | Fixed in Batch 1 | Confirmed — only commented original remains | ✅ Verified |
| 8 | Dev bypass link | `resources/views/public/assessment/index.blade.php` | 171 | Gated by `@if(!env('STRIPE_SECRET') \|\| app()->environment('local','testing'))` | Route also gated identically in `routes/web.php:46` — only shows when Stripe is not configured | ⚠️ Properly gated — ensure `STRIPE_SECRET` is set in production `.env` |

## STRUCTURAL / GLOBAL FIXES

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 9a | Turnaround: 72 hours | `resources/views/public/home.blade.php` | 147 | `within 72 hours` | N/A — flagged for client confirmation | ⚠️ Flag |
| 9b | Turnaround: 5 working days | `resources/views/public/home.blade.php` | 165 | `within 5 working days` | N/A — flagged for client confirmation | ⚠️ Flag |
| 9c | Turnaround: 5–7 days (report) | `resources/views/public/service-show.blade.php` | 44 | `within 5–7 days` | N/A — flagged for client confirmation | ⚠️ Flag |
| 9d | Turnaround: 5–7 days (repeat) | `resources/views/public/service-show.blade.php` | 190 | `within 5–7 days` | N/A — flagged for client confirmation | ⚠️ Flag |
| 9e | Turnaround: 24-Hour Review | `resources/views/public/hcp-referral.blade.php` | 39 | `24-Hour Review...within one business day` | N/A — flagged for client confirmation | ⚠️ Flag |
| 9f | Turnaround: one business day | `resources/views/public/hcp-referral-thanks.blade.php` | 13 | `within one business day` | N/A — flagged for client confirmation | ⚠️ Flag |
| 9g | Turnaround: 48 hours (cancellation) | `resources/views/public/faq.blade.php` | 13 | `within 48 hours of the scheduled assessment date` | N/A — flagged for client confirmation | ⚠️ Flag |
| 9h | Turnaround: five working days | `resources/views/public/faq.blade.php` | 24 | `within five working days after the assessment` | N/A — flagged for client confirmation | ⚠️ Flag |
| 10 | Services hero anchor `#specialist-assessments` | `resources/views/public/services.blade.php` | 47, 119 | Button links to `#specialist-assessments`; section has `id="specialist-assessments"` | Anchor exists and matches | ✅ Verified |
| 11 | Admin Login removal check | `resources/views/layouts/app.blade.php` | 449-452 | Commented out in Batch 1 | Not visible on public pages | ✅ Verified |
| 12 | HCP privacy claims consistency | `resources/views/public/hcp-referral.blade.php` | 38, 213 | Line 38: `compliance with medical privacy standards`; Line 213: `compliance with GDPR` | Both now say `encrypted and stored securely in compliance with GDPR` | ✅ Fixed |

## Server-side actions required (cumulative)

1. Update the assessment fee in the database: `UPDATE settings SET value='235' WHERE key='assessment_fee';`
2. If RSA guideline PDF links are needed, use: `https://www.rsa.ie/docs/default-source/road-safety/slainte-agus-tiomaint/medical-fitness-to-drive-guidelines-2026.pdf?sfvrsn=a3301610_2`
3. **Testimonials (manual DB fix needed):**
   - Dr Sharon Healy: quote appears truncated ending with 'I have no hesitation in' — restore full quote in testimonials table
   - Fr. Patrick O'Keeffe, C.Ss.R.: name/location is duplicated in the testimonials table — remove the duplicate
4. **Add 5 new FAQ questions** via admin panel at `/admin/cms-pages/9/edit` (see list above)
5. **Ensure `STRIPE_SECRET` is set** in production `.env` so the dev bypass link does not appear on the live Book an Assessment page
6. **Turnaround times** (items 9a-9h above): confirm with client whether 72h, 5 working days, 5-7 days, 24h, and 48h cancellation window are all correct

---

# Batch 4 — Address verification + Assessment Fee admin UI 26-06-28

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 1 | Footer address check | `resources/views/layouts/app.blade.php` | 405 | `Lackaghbeg, Lackagh, Turloughmore, Athenry, Co. Galway` | Already correct from Batch 1 | ✅ Verified |
| 2 | Footer phone check | `resources/views/layouts/app.blade.php` | 398 | `+353 (0)86 0422535` | Correct | ✅ Verified |
| 3 | Assessment fee admin UI | `resources/views/admin/settings/index.blade.php` | 65 | No field for assessment fee | Added €-prefixed number input under Payment Gateway tab | ✅ Fixed |
| 4 | Assessment fee controller | `app/Http/Controllers/Admin/SettingsController.php` | 27 | `assessment_fee` not in stripe tab save list | Added to `setIfPresent` array | ✅ Fixed |
| 5a | Fee read: booking page | `app/Http/Controllers/Public/AssessmentController.php` | 26 | `Setting::get('assessment_fee', '235')` | Already dynamic | ✅ Verified |
| 5b | Fee read: Stripe checkout | `app/Http/Controllers/Public/AssessmentController.php` | 53 | `Setting::get('assessment_fee', '235')` | Already dynamic | ✅ Verified |
| 5c | Fee read: FAQ page | `app/Http/Controllers/Public/FaqController.php` | 10 | `Setting::get('assessment_fee', '235')` | Already dynamic | ✅ Verified |
| 5d | Fee display: booking page | `resources/views/public/assessment/index.blade.php` | 15, 87, 143 | `€{{ $fee }}` | Already dynamic via controller `$fee` variable | ✅ Verified |
| 5e | Fee display: FAQ fallback | `resources/views/public/faq.blade.php` | 12, 21 | `€' . $fee . '` | Already dynamic via controller `$fee` variable | ✅ Verified |

---

# Batch 5 — Services UI, Contact Form, Video, Remaining Fixes 26-06-26

## SERVICES PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 1 | RSA Guidelines link on condition detail pages | `resources/views/public/service-show.blade.php` | 235 | No RSA link | Added RSA 2026 guidelines PDF link card in sidebar on every condition page | ✅ Fixed |
| 2 | Hardcoded €235 in sidebar | `resources/views/public/service-show.blade.php` | 186 | `€235` hardcoded | Now reads from `Setting::get('assessment_fee', '235')` | ✅ Fixed |
| 3a | Slug mismatch: Physical Disabilities | `resources/views/public/services.blade.php` | 12 | Slug `congenital-disorders` → DB record `service-congenital-disorders` | Slug matches DB — changing would break existing record. Rename via admin panel if needed | ⚠️ Log only — rename slug in DB if desired |
| 3b | Slug mismatch: Ageing Driver | `resources/views/public/services.blade.php` | 15 | Slug `your-health-and-driving` → DB record `service-your-health-and-driving` | Same — slug matches DB | ⚠️ Log only — rename slug in DB if desired |
| 4 | 'Learn more' link visibility | `resources/views/public/services.blade.php` | 156 | No underline | Added underline + offset for clear link styling | ✅ Fixed |
| 5 | Featured condition card colours | `resources/views/public/services.blade.php` | 140 | No brand colour border | Added 4px navy left border | ✅ Fixed |
| 6 | Sub-condition pill colours | `resources/views/public/services.blade.php` | 177 | Faint border, `font-medium` | Navy-tinted border, `font-semibold`, hover lift + shadow | ✅ Fixed |
| 7 | Service detail page colours | `resources/views/public/service-show.blade.php` | N/A | Already uses navy headings, gold CTAs, navy sidebar | Brand colours already consistent | ✅ Verified |

## CONTACT PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 8 | Contact form email default | `app/Http/Controllers/Public/ContactController.php` | 36 | Default `info@driverassessmentsireland.ie` | Default `info@dai.ie` | ✅ Fixed |
| 9 | Honeypot spam protection | `app/Http/Controllers/Public/ContactController.php` + `contact.blade.php` | 17, 51 | No honeypot | Added hidden `website_url` field; if filled, silently rejects | ✅ Fixed |
| 10 | Privacy statement | `resources/views/public/contact.blade.php` | 116 | No privacy text | Added GDPR privacy line below submit button | ✅ Fixed |

## HCP REFERRAL PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 11a | File upload field | `resources/views/public/hcp-referral.blade.php` | 174 | No file upload | Added optional document upload (PDF, DOC, DOCX, JPG, PNG, max 5MB) | ✅ Fixed |
| 11b | File upload controller | `app/Http/Controllers/Public/HcpReferralController.php` | 27 | No file handling | Added validation + storage to `storage/app/referral-documents/` | ✅ Fixed |
| 11c | File upload migration | `database/migrations/2026_06_28_000001_add_document_path_to_hcp_referrals.php` | N/A | No columns | Added `document_path` and `document_name` to `hcp_referrals` table | ✅ Fixed |
| 11d | File upload model | `app/Models/HcpReferral.php` | 7 | No document fields | Added `document_path`, `document_name` to fillable | ✅ Fixed |

## SERVICES PAGE VIDEO

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 12a | Video section on services page | `resources/views/public/services.blade.php` | 58 | No video | Added YouTube/Vimeo embed between hero and main content (reads from `services_video_url` setting) | ✅ Fixed |
| 12b | Admin settings for services video | `resources/views/admin/settings/index.blade.php` | 312 | No field | Added video URL field under Menus & Social tab | ✅ Fixed |
| 12c | Controller save | `app/Http/Controllers/Admin/SettingsController.php` | 49 | Not saved | Added `services_video_url` to menus tab save list | ✅ Fixed |

## BOOK AN ASSESSMENT PAGE

| # | Item | File | Line | Before | After | Status |
|---|------|------|------|--------|-------|--------|
| 13 | Flip order (details first, payment last) | N/A | N/A | Payment is step 0, details steps 1-5 | Requires significant restructuring: Stripe session creation must move to final step, AssessmentApplication record must be created before payment, status flow needs redesign | ⚠️ Future task — logged below |

## Notes

- **Virus scanning recommendation:** Uploaded HCP referral documents are stored server-side without virus scanning. Consider integrating ClamAV or a cloud scanning service before production use with sensitive medical documents.
- **Slug renaming:** To fix SEO slugs (e.g. `congenital-disorders` → `physical-disabilities`), update both the `cms_pages.slug` column in the DB AND the `$featured` array in `services.blade.php`. This must be done together to avoid broken links.
- **Assessment flow restructure (future task):** Flipping to details-first requires: (1) create AssessmentApplication record before payment with status='unpaid', (2) move Stripe checkout to final form step, (3) update success handler to update existing record rather than create new one, (4) handle abandoned unpaid records. This is a full sprint task, not a quick fix.

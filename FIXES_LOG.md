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

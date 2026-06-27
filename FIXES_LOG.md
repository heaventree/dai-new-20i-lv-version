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

## Server-side actions required

1. Update the assessment fee in the database: `UPDATE settings SET value='235' WHERE key='assessment_fee';`
2. If RSA guideline PDF links are needed, use: `https://www.rsa.ie/docs/default-source/road-safety/slainte-agus-tiomaint/medical-fitness-to-drive-guidelines-2026.pdf?sfvrsn=a3301610_2`

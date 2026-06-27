<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use App\Models\Setting;
use App\Models\EmailTemplate;
use App\Models\CmsPage;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user — firstOrCreate so password changes in admin panel survive restarts
        AdminUser::firstOrCreate(
            ['username' => 'admin'],
            ['password_hash' => Hash::make('dai-admin-2025'), 'role' => 'admin']
        );

        // Default settings
        $defaults = [
            'maintenance_mode'   => '0',
            'stripe_mode'        => 'test',
            'assessment_fee'     => '195',
            'contact_email'      => 'info@driverassessmentsireland.ie',
            'notification_email' => 'info@driverassessmentsireland.ie',
            'mail_from_address'  => 'info@driverassessmentsireland.ie',

            // Google Sheets
            'google_sheets_id'            => '1hvbRmC5qMUctk0733OK0283nEAvCWyRgLsoZiO0G7mw',
            'google_service_account_json' => env('GOOGLE_SERVICE_ACCOUNT_JSON', ''),
}',

            // SMTP
            'mail_host'     => 'smtp.dai.ie',
            'mail_port'     => '465',
            'mail_username' => 'heaventree@dai.ie',
            'mail_password' => 'GB8c;,-dk&=p',
        ];

        // firstOrCreate so settings changed via admin panel survive restarts
        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }

        // Email templates
        $hcpBody = <<<'HTML'
<div style="margin-bottom:20px">
  <h3 style="margin:0 0 10px;font-size:13px;font-weight:700;color:#1a3a6b;text-transform:uppercase;letter-spacing:0.05em;border-bottom:2px solid #e0e8f0;padding-bottom:5px">Healthcare Professional</h3>
  <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:8px"><tr>
    <td style="padding:7px 12px 7px 0;color:#555;vertical-align:top;white-space:nowrap;width:42%">HCP Name</td>
    <td style="padding:7px 0;font-weight:600;color:#1a1a1a;word-break:break-word">{{hcpName}}</td>
  </tr><tr>
    <td style="padding:7px 12px 7px 0;color:#555;vertical-align:top;white-space:nowrap;width:42%">HCP Address</td>
    <td style="padding:7px 0;font-weight:600;color:#1a1a1a;word-break:break-word">{{hcpAddress}}</td>
  </tr><tr>
    <td style="padding:7px 12px 7px 0;color:#555;vertical-align:top;white-space:nowrap;width:42%">HCP Contact Number</td>
    <td style="padding:7px 0;font-weight:600;color:#1a1a1a;word-break:break-word">{{hcpPhone}}</td>
  </tr><tr>
    <td style="padding:7px 12px 7px 0;color:#555;vertical-align:top;white-space:nowrap;width:42%">HCP Email</td>
    <td style="padding:7px 0;font-weight:600;color:#1a1a1a;word-break:break-word">{{hcpEmail}}</td>
  </tr></table>
</div>
<div style="margin-bottom:20px">
  <h3 style="margin:0 0 10px;font-size:13px;font-weight:700;color:#1a3a6b;text-transform:uppercase;letter-spacing:0.05em;border-bottom:2px solid #e0e8f0;padding-bottom:5px">Patient Details</h3>
  <table style="width:100%;border-collapse:collapse;font-size:14px;margin-bottom:8px"><tr>
    <td style="padding:7px 12px 7px 0;color:#555;vertical-align:top;white-space:nowrap;width:42%">Patient Name</td>
    <td style="padding:7px 0;font-weight:600;color:#1a1a1a;word-break:break-word">{{patientName}}</td>
  </tr><tr>
    <td style="padding:7px 12px 7px 0;color:#555;vertical-align:top;white-space:nowrap;width:42%">Date of Birth</td>
    <td style="padding:7px 0;font-weight:600;color:#1a1a1a;word-break:break-word">{{patientDob}}</td>
  </tr><tr>
    <td style="padding:7px 12px 7px 0;color:#555;vertical-align:top;white-space:nowrap;width:42%">Patient County</td>
    <td style="padding:7px 0;font-weight:600;color:#1a1a1a;word-break:break-word">{{patientCounty}}</td>
  </tr></table>
</div>
HTML;

        $paymentBody = <<<'HTML'
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Dear {{name}},</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Thank you for your payment of <strong>€{{amount}}</strong>. We have received your assessment fee and your application is now being processed.</p>
<div style="background:#f0f4ff;border-radius:8px;padding:16px 20px;margin-bottom:20px">
  <p style="margin:0 0 6px;font-size:13px;color:#555;font-weight:600;text-transform:uppercase;letter-spacing:0.04em">Order Reference</p>
  <p style="margin:0;font-size:18px;font-weight:700;color:#132d5e;letter-spacing:0.02em">{{orderId}}</p>
</div>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">You will receive a link to complete your assessment application form shortly. Please complete it at your earliest convenience.</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0">Kind regards,<br><strong>Driver Assessments Ireland</strong><br>info@driverassessmentsireland.ie<br>+353 (0)86 0422535</p>
HTML;

        $assessmentBody = <<<'HTML'
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Dear {{name}},</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Thank you — we have received your completed assessment application.</p>
<div style="background:#f0f4ff;border-radius:8px;padding:16px 20px;margin-bottom:20px">
  <p style="margin:0 0 6px;font-size:13px;color:#555;font-weight:600;text-transform:uppercase;letter-spacing:0.04em">Reference</p>
  <p style="margin:0;font-size:18px;font-weight:700;color:#132d5e;letter-spacing:0.02em">{{orderId}}</p>
</div>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">One of our assessors will be in contact within 5 working days to arrange a suitable appointment date and location across our 32-county service area.</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0">Kind regards,<br><strong>Driver Assessments Ireland</strong><br>info@driverassessmentsireland.ie<br>+353 (0)86 0422535</p>
HTML;

        $contactBody = <<<'HTML'
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Dear {{name}},</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Thank you for contacting Driver Assessments Ireland. We have received your message and a member of our team will respond within <strong>2 business days</strong>.</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">If your query is urgent, please call us directly on <strong>+353 (0)86 0422535</strong>.</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0">Kind regards,<br><strong>Driver Assessments Ireland</strong><br>info@driverassessmentsireland.ie<br>+353 (0)86 0422535</p>
HTML;

        $templates = [
            [
                'slug'    => 'hcp-referral-confirmation',
                'name'    => 'HCP Referral Confirmation',
                'subject' => 'New HCP Referral – {{patientName}}',
                'body'    => trim($hcpBody),
            ],
            [
                'slug'    => 'payment-received',
                'name'    => 'Payment Received',
                'subject' => 'Payment Confirmed – Driver Assessments Ireland',
                'body'    => trim($paymentBody),
            ],
            [
                'slug'    => 'assessment-confirmation',
                'name'    => 'Assessment Application Received',
                'subject' => 'Assessment Application Received – Driver Assessments Ireland',
                'body'    => trim($assessmentBody),
            ],
            [
                'slug'    => 'contact-auto-reply',
                'name'    => 'Contact Form Auto-Reply',
                'subject' => 'We received your message – Driver Assessments Ireland',
                'body'    => trim($contactBody),
            ],
        ];
        foreach ($templates as $t) {
            EmailTemplate::updateOrCreate(
                ['slug' => $t['slug']],
                array_merge($t, ['is_active' => true, 'is_default' => true])
            );
        }

        // CMS pages
        $pages = [
            ['slug' => 'about',   'title' => 'About Us',                   'content' => '<p>Driver Assessments Ireland provides specialist driving assessments for individuals with medical conditions or disabilities across all 32 counties of Ireland.</p>', 'meta_description' => 'About Driver Assessments Ireland - specialist driving assessors serving all 32 counties.'],
            ['slug' => 'faq',     'title' => 'Frequently Asked Questions', 'content' => '', 'meta_description' => 'Frequently asked questions about driver assessments in Ireland.'],
            ['slug' => 'contact', 'title' => 'Contact Us',                 'content' => '<p>Get in touch with our team at Driver Assessments Ireland.</p>', 'meta_description' => 'Contact Driver Assessments Ireland - phone, email and enquiry form.'],
        ];
        foreach ($pages as $p) {
            CmsPage::updateOrCreate(['slug' => $p['slug']], array_merge($p, ['is_published' => true]));
        }

        // Service CMS pages
        $services = [
            [
                'slug'             => 'service-stroke',
                'title'            => 'Stroke & Acquired Brain Injury',
                'meta_description' => 'Driver assessment for stroke and acquired brain injury — serving all 32 counties of Ireland.',
                'content_json'     => [
                    'summary'    => 'Comprehensive post-stroke driving evaluations assessing cognitive, visual, and motor function to support safe return to driving.',
                    'overview'   => "A stroke or acquired brain injury (ABI) can affect many of the skills needed to drive safely, including vision, concentration, reaction speed, and physical control of the vehicle. Many people can return to driving after a stroke or ABI, but an independent assessment is usually required before doing so.\n\nAt Driver Assessments Ireland, our specialist occupational therapists conduct thorough evaluations that consider all aspects of driving ability in the context of your specific condition and recovery progress.",
                    'who'        => "This assessment is for individuals who:\n\n- Have had a stroke or transient ischaemic attack (TIA)\n- Have sustained a traumatic or non-traumatic acquired brain injury\n- Have been advised by their GP or neurologist to have their fitness to drive assessed\n- Wish to return to driving following rehabilitation\n- Hold a Group 1 or Group 2 (vocational) driving licence",
                    'impact'     => "A stroke or ABI can affect driving in many ways depending on the area of the brain affected. Common impacts include:\n\n- Visual field deficits (hemianopia or quadrantanopia) affecting the ability to see hazards on one side\n- Slowed reaction time and reduced processing speed\n- Impaired attention, concentration, or divided attention\n- Physical weakness or spasticity affecting steering, braking, or gear control\n- Fatigue, which can significantly impair sustained driving performance\n- Cognitive changes including memory, planning, and decision-making",
                    'assessment' => "Our assessment involves three stages:\n\n1. Pre-drive clinical interview: Review of medical history, medications, and any relevant reports from your neurologist, GP, or rehabilitation team.\n\n2. Off-road evaluation: Standardised cognitive and perceptual tests relevant to driving, including visual field screening and reaction time assessment.\n\n3. On-road assessment: A structured drive of 45–60 minutes on a variety of road types in your local area. The assessor will evaluate all aspects of your driving.\n\nFollowing the assessment, a detailed written report is prepared outlining findings and recommendations. This is typically provided within 5–7 working days.",
                    'buttons'    => [
                        ['label' => 'RSA Guidelines', 'url' => 'https://www.rsa.ie/services/licensed-drivers/medical-fitness-to-drive'],
                        ['label' => 'Stroke Alliance Ireland', 'url' => 'https://strokeallianceireland.ie/'],
                    ],
                ],
            ],
            [
                'slug'             => 'service-parkinsons',
                'title'            => "Parkinson's Disease",
                'meta_description' => "Driver assessment for Parkinson's disease — specialist evaluation serving all 32 counties of Ireland.",
                'content_json'     => [
                    'summary'    => "Specialist assessment accounting for motor fluctuations, medication timing, and cognitive changes associated with Parkinson's disease.",
                    'overview'   => "Parkinson's disease is a progressive neurological condition that can affect driving in complex ways. The impact varies significantly between individuals and at different stages of the disease. Many people with Parkinson's can continue to drive safely, particularly in the earlier stages, with appropriate assessment and monitoring.\n\nOur assessors have specialist experience in evaluating drivers with Parkinson's, understanding the importance of timing the assessment in relation to medication cycles (on/off periods).",
                    'who'        => "This assessment is recommended for:\n\n- Individuals with a diagnosis of Parkinson's disease or Parkinsonism who wish to continue driving\n- Those referred by their GP, neurologist, or specialist Parkinson's nurse\n- Drivers who have noticed changes in their driving and want an independent evaluation\n- Individuals whose licence has been queried by the NDLS",
                    'impact'     => "Parkinson's disease can affect driving through both motor and non-motor symptoms:\n\n- Tremor affecting steering control and gear changes\n- Rigidity and bradykinesia (slowness of movement) affecting reaction times and vehicle control\n- Postural instability affecting the ability to look over the shoulder or react to sudden hazards\n- Visual changes including reduced contrast sensitivity\n- Cognitive changes including attention, visuospatial processing, and executive function\n- Sudden 'off' periods where medication effectiveness drops significantly\n- Excessive daytime sleepiness, a common non-motor symptom",
                    'assessment' => "We work closely with the referring clinician to schedule the assessment at an optimal time in the medication cycle. The assessment includes:\n\n1. Clinical interview covering symptom profile, medication regimen, and 'on/off' patterns.\n\n2. Standardised cognitive and motor screening relevant to driving.\n\n3. On-road assessment conducted during an 'on' period when medication is most effective.\n\nA detailed report is provided to you and, with your consent, to your neurologist or GP.",
                    'buttons'    => [
                        ['label' => 'Parkinson\'s Ireland', 'url' => 'https://www.parkinsons.ie/'],
                        ['label' => 'RSA Medical Fitness', 'url' => 'https://www.rsa.ie/services/licensed-drivers/medical-fitness-to-drive'],
                    ],
                ],
            ],
            [
                'slug'             => 'service-dementia',
                'title'            => 'Dementia & Cognitive Decline',
                'meta_description' => 'Driver assessment for dementia and cognitive decline — compassionate, evidence-based evaluation across Ireland.',
                'content_json'     => [
                    'summary'    => "Sensitive, evidence-based evaluations for individuals with mild to moderate cognitive impairment, including Alzheimer's disease.",
                    'overview'   => "A diagnosis of dementia does not automatically mean that someone cannot drive. Many individuals in the early stages of dementia can continue to drive safely, though their driving fitness needs to be formally assessed and monitored over time.\n\nDriver Assessments Ireland offers compassionate, expert evaluations for individuals and families navigating this difficult issue. Our assessors understand the emotional complexity involved and approach every assessment with sensitivity and respect for the individual.",
                    'who'        => "This assessment is appropriate for:\n\n- Individuals with a diagnosis of Alzheimer's disease or other dementia syndromes\n- Those experiencing mild cognitive impairment (MCI) where driving is a concern\n- People referred by their GP, geriatrician, or memory clinic\n- Family members concerned about an older relative's driving safety",
                    'impact'     => "Cognitive decline can affect driving in the following ways:\n\n- Difficulty with route-finding and navigation, even on familiar roads\n- Reduced ability to process multiple pieces of information simultaneously\n- Impaired judgement at junctions and in complex traffic situations\n- Slowed reaction times and reduced hazard perception\n- Getting lost or confused, particularly in unfamiliar environments\n- Visuospatial difficulties affecting parking and lane positioning",
                    'assessment' => "Our assessment is structured to be as comfortable and non-threatening as possible for the individual:\n\n1. Initial consultation with the individual and, if appropriate, a family member or carer. We review all relevant medical information.\n\n2. Brief cognitive screening using validated tools relevant to driving.\n\n3. On-road assessment in familiar, local roads wherever possible.\n\nThe written report is provided to the individual and, with consent, to their GP or specialist. We provide clear, sensitive communication of findings.",
                    'buttons'    => [
                        ['label' => "Alzheimer's Society of Ireland", 'url' => 'https://alzheimer.ie/'],
                        ['label' => 'RSA Medical Fitness', 'url' => 'https://www.rsa.ie/services/licensed-drivers/medical-fitness-to-drive'],
                    ],
                ],
            ],
            [
                'slug'             => 'service-epilepsy',
                'title'            => 'Epilepsy & Seizure Disorders',
                'meta_description' => 'Driver assessment for epilepsy and seizure disorders — RSA guideline compliant evaluation across all 32 counties.',
                'content_json'     => [
                    'summary'    => 'Assessment of driving fitness following seizure events, in line with RSA guidelines on seizure-free periods and licence categories.',
                    'overview'   => "Epilepsy and seizure disorders require careful consideration in the context of driving. Irish and European regulations set out specific seizure-free periods before an individual can return to driving, which vary depending on the type of seizure, licence category (Group 1 private or Group 2 vocational), and circumstances of the seizure.\n\nDriver Assessments Ireland provides expert assessments to support individuals returning to driving after a seizure event, and provides comprehensive reports accepted by the NDLS.",
                    'who'        => "This assessment is for:\n\n- Individuals who have had a seizure and wish to return to driving after the required seizure-free period\n- Those referred by their GP or neurologist\n- Drivers with established epilepsy whose medication has changed\n- Holders of Group 2 (vocational) licences requiring more stringent assessment",
                    'impact'     => "The primary concern with epilepsy and driving is the risk of a seizure occurring while driving. Additional considerations include:\n\n- Medication side effects such as drowsiness or slowed processing\n- Cognitive effects of longstanding epilepsy\n- Post-ictal states (confusion following a seizure)\n- Nocturnal seizures and their relevance to Group 1 licence holders\n- Visual field deficits in some epilepsy syndromes",
                    'assessment' => "Our assessment follows RSA and DVLA guidelines closely:\n\n1. Clinical interview to document seizure history, type, frequency, medication, and seizure-free period.\n\n2. Review of specialist neurologist reports and any relevant investigations.\n\n3. Functional on-road assessment where indicated.\n\nWe work with your neurologist to ensure all documentation required by the NDLS is in order. Our report clearly states whether the current seizure-free period and clinical picture are consistent with safe driving under current regulations.",
                    'buttons'    => [
                        ['label' => 'Epilepsy Ireland', 'url' => 'https://www.epilepsy.ie/'],
                        ['label' => 'RSA Medical Fitness', 'url' => 'https://www.rsa.ie/services/licensed-drivers/medical-fitness-to-drive'],
                    ],
                ],
            ],
            [
                'slug'             => 'service-visual',
                'title'            => 'Visual Impairments',
                'meta_description' => 'Driver assessment for visual impairments — functional visual evaluation for Irish drivers.',
                'content_json'     => [
                    'summary'    => 'On-road functional visual assessment for drivers with reduced acuity, visual field loss, or conditions such as glaucoma and macular degeneration.',
                    'overview'   => "Good vision is fundamental to safe driving. Irish law sets minimum visual standards for driving, but meeting the legal minimum does not always mean an individual is safe to drive. A functional driving assessment provides a real-world picture of how a visual impairment affects actual driving performance.\n\nDriver Assessments Ireland works with individuals with a wide range of visual conditions to provide expert, evidence-based assessments of their driving fitness.",
                    'who'        => "This assessment is appropriate for:\n\n- Individuals with glaucoma, macular degeneration, or other progressive eye conditions\n- Those who have had a visual field defect following stroke or neurological injury\n- Drivers with monocular vision (vision in one eye only)\n- People referred by their ophthalmologist, optometrist, or GP with visual concerns\n- Individuals whose visual acuity has changed significantly",
                    'impact'     => "Visual impairments can affect driving in the following ways:\n\n- Reduced visual acuity making it difficult to read road signs or identify hazards at distance\n- Visual field loss creating 'blind spots' for other road users, pedestrians, or hazards\n- Impaired contrast sensitivity making driving in low light or fog more dangerous\n- Monocular vision affecting depth perception, particularly relevant for parking and overtaking\n- Photosensitivity making driving in bright sunlight difficult",
                    'assessment' => "Our visual driving assessment includes:\n\n1. Review of ophthalmology reports and any visual field charts.\n\n2. Clinical vision screening including a number plate test (legal requirement), visual acuity, and visual field assessment.\n\n3. On-road assessment evaluating how the visual impairment affects real-world driving performance.\n\nWhere adaptive equipment (e.g. bioptic telescopes, tinted lenses) may be beneficial, we can advise on appropriate resources.",
                    'buttons'    => [
                        ['label' => 'NCBI Vision Ireland', 'url' => 'https://ncbi.ie/'],
                        ['label' => 'RSA Visual Standards', 'url' => 'https://www.rsa.ie/services/licensed-drivers/medical-fitness-to-drive'],
                    ],
                ],
            ],
            [
                'slug'             => 'service-age-related',
                'title'            => 'Age-Related Assessment',
                'meta_description' => 'Driver assessment for older drivers — holistic evaluation for age-related changes in driving ability across Ireland.',
                'content_json'     => [
                    'summary'    => 'Holistic evaluation for older drivers addressing the cumulative effects of ageing on driving performance, safety awareness, and reaction times.',
                    'overview'   => "Many older drivers drive safely well into their 70s, 80s, and beyond. However, normal ageing processes can gradually affect the skills needed for safe driving. Driver Assessments Ireland provides respectful, expert evaluations that give older drivers and their families an honest, evidence-based picture of current driving ability.\n\nOur assessors are experienced in working sensitively with older adults, understanding that driving represents independence and dignity for many people.",
                    'who'        => "An age-related assessment may be appropriate for:\n\n- Older drivers (typically 70+) who wish to self-assess or have been requested to do so by their GP\n- Those whose family members have expressed concerns about driving safety\n- Drivers who have noticed changes in their own driving ability\n- Individuals who have recently had a fall, hospitalisation, or new medical diagnosis\n- Drivers required by the NDLS to undergo assessment",
                    'impact'     => "Normal ageing can affect driving in the following ways:\n\n- Slowed reaction times and reduced processing speed\n- Reduced flexibility in the neck and shoulders affecting mirror checks and reversing\n- Changes in night vision and increased sensitivity to glare\n- Reduced ability to divide attention between multiple tasks\n- Fatigue affecting sustained driving ability on longer journeys\n- Cumulative medication effects\n- Subtle cognitive changes affecting navigation and decision-making",
                    'assessment' => "Our age-related assessment is designed to be thorough but non-threatening:\n\n1. Pre-drive consultation covering medical history, current medications, driving patterns, and any concerns.\n\n2. Screening for visual acuity, visual fields, cognitive function, and physical range of movement.\n\n3. On-road assessment on familiar local roads, evaluating all aspects of driving performance.\n\nWe provide clear, honest feedback and practical recommendations — including whether adaptive equipment, vehicle modifications, or driving restriction (e.g. daylight only, local area only) might enable safe continued driving.",
                    'buttons'    => [
                        ['label' => 'Age Action Ireland', 'url' => 'https://www.ageaction.ie/'],
                        ['label' => 'RSA Medical Fitness', 'url' => 'https://www.rsa.ie/services/licensed-drivers/medical-fitness-to-drive'],
                    ],
                ],
            ],
        ];
        foreach ($services as $s) {
            CmsPage::updateOrCreate(
                ['slug' => $s['slug']],
                array_merge($s, ['is_published' => true])
            );
        }

        // Team members — updateOrCreate by slug so uploaded photos are NEVER overwritten
        $teamMembers = [
            ['name'=>'Sinead Cumiskey',    'slug'=>'sinead-cumiskey',    'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Sinead Cumiskey. This will be updated with the full profile.', 'coru_registration'=>'', 'display_order'=>1,  'is_active'=>true],
            ['name'=>'Darragh Dunne',      'slug'=>'darragh-dunne',      'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Darragh Dunne.',       'coru_registration'=>'', 'display_order'=>2,  'is_active'=>true],
            ['name'=>'Dessie Kearney',     'slug'=>'dessie-kearney',     'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Dessie Kearney.',      'coru_registration'=>'', 'display_order'=>3,  'is_active'=>true],
            ['name'=>'Colin Blackmore',    'slug'=>'colin-blackmore',    'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Colin Blackmore.',     'coru_registration'=>'', 'display_order'=>4,  'is_active'=>true],
            ['name'=>'Michael McAulliffe', 'slug'=>'michael-mcaulliffe', 'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Michael McAulliffe.',  'coru_registration'=>'', 'display_order'=>5,  'is_active'=>true],
            ['name'=>'Mag Dunphy',         'slug'=>'mag-dunphy',         'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Mag Dunphy.',          'coru_registration'=>'', 'display_order'=>6,  'is_active'=>true],
            ['name'=>'Ruairi Thomas',      'slug'=>'ruairi-thomas',      'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Ruairi Thomas.',       'coru_registration'=>'', 'display_order'=>7,  'is_active'=>true],
            ['name'=>'Szilard Teszar',     'slug'=>'szilard-teszar',     'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Szilard Teszar.',      'coru_registration'=>'', 'display_order'=>8,  'is_active'=>true],
            ['name'=>'Nessa Murphy Shanks','slug'=>'nessa-murphy-shanks','title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Nessa Murphy Shanks.', 'coru_registration'=>'', 'display_order'=>9,  'is_active'=>true],
            ['name'=>'Anna McCann',        'slug'=>'anna-mccann',        'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Anna McCann.',          'coru_registration'=>'', 'display_order'=>10, 'is_active'=>true],
            ['name'=>"Sean O'Keefe",       'slug'=>'sean-okeefe',        'title'=>'Driving Assessor & Instructor', 'bio'=>"Placeholder biography for Sean O'Keefe.",        'coru_registration'=>'', 'display_order'=>11, 'is_active'=>true],
            ['name'=>'Kirstie Jones',      'slug'=>'kirstie-jones',      'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Kirstie Jones.',       'coru_registration'=>'', 'display_order'=>12, 'is_active'=>true],
            ['name'=>'Niamh',              'slug'=>'niamh',              'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Niamh.',                'coru_registration'=>'', 'display_order'=>13, 'is_active'=>true],
            ['name'=>'Lisa Hurney',        'slug'=>'lisa-hurney',        'title'=>'Driving Assessor & Instructor', 'bio'=>'Placeholder biography for Lisa Hurney.',          'coru_registration'=>'', 'display_order'=>14, 'is_active'=>true],
        ];
        foreach ($teamMembers as $member) {
            // Match on slug; only fill `photo` when creating — never overwrite an existing photo
            $existing = \App\Models\TeamMember::where('slug', $member['slug'])->first();
            if ($existing) {
                $existing->update(array_diff_key($member, ['photo' => true]));
            } else {
                \App\Models\TeamMember::create($member);
            }
        }

        // Testimonials
        $testimonials = [
            ['quote' => 'The assessment gave me confidence to drive again after my stroke. The team were incredibly patient and the report was accepted by the NDLS straight away.', 'name' => 'Mary O\'Brien', 'location' => 'Co. Galway', 'stars' => 5, 'highlight' => true, 'sort_order' => 1],
            ['quote' => 'My father felt completely at ease throughout the entire process. Our assessor explained every step clearly and the written report was detailed and professional.', 'name' => 'Brian Sullivan', 'location' => 'Co. Cork', 'stars' => 5, 'highlight' => true, 'sort_order' => 2],
            ['quote' => 'Professional from start to finish. The report was comprehensive and accepted by the NDLS without any issue. I would highly recommend Driver Assessments Ireland.', 'name' => 'David Walsh', 'location' => 'Co. Dublin', 'stars' => 5, 'highlight' => true, 'sort_order' => 3],
            ['quote' => 'After my hip replacement I wasn\'t sure if I\'d drive again. The team guided me through the whole process and I was back behind the wheel within weeks.', 'name' => 'Ann Dolan', 'location' => 'Co. Limerick', 'stars' => 5, 'highlight' => false, 'sort_order' => 4],
            ['quote' => 'My GP referred me and I was assessed within the week. The process was straightforward and the report was ready in 48 hours. Excellent service.', 'name' => 'Patrick Kearney', 'location' => 'Co. Tipperary', 'stars' => 5, 'highlight' => false, 'sort_order' => 5],
        ];
        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['name' => $t['name']], $t);
        }
    }
}

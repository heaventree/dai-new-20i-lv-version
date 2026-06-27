<?php
// DAI — PHP data import. Uses PDO prepared statements. DELETE after use.
header('Content-Type: text/plain; charset=utf-8');

$envFile = dirname(__DIR__) . '/.env';
$env = [];
foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    if (str_starts_with(trim($line), '#')) continue;
    [$k, $v] = array_pad(explode('=', $line, 2), 2, '');
    $env[trim($k)] = trim($v, '"\'');
}

try {
    $pdo = new PDO(
        'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_DATABASE'] . ';charset=utf8mb4',
        $env['DB_USERNAME'], $env['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "DB: OK\n\n";
} catch (Exception $e) { die("DB FAILED: " . $e->getMessage() . "\n"); }

$DATA_SETTINGS  = array (
  0 => 
  array (
    'key' => 'maintenance_mode',
    'value' => '0',
  ),
  1 => 
  array (
    'key' => 'stripe_mode',
    'value' => 'test',
  ),
  2 => 
  array (
    'key' => 'assessment_fee',
    'value' => '235',
  ),
  3 => 
  array (
    'key' => 'contact_email',
    'value' => 'info@driverassessmentsireland.ie',
  ),
  4 => 
  array (
    'key' => 'notification_email',
    'value' => 'heaventree@dai.ie',
  ),
  5 => 
  array (
    'key' => 'mail_from_address',
    'value' => 'heaventree@dai.ie',
  ),
  6 => 
  array (
    'key' => 'google_sheets_id',
    'value' => '1hvbRmC5qMUctk0733OK0283nEAvCWyRgLsoZiO0G7mw',
  ),
  7 => 
  array (
    'key' => 'google_service_account_json',
    'value' => env('GOOGLE_SERVICE_ACCOUNT_JSON', ''),
}',
  ),
  8 => 
  array (
    'key' => 'mail_host',
    'value' => 'smtp.dai.ie',
  ),
  9 => 
  array (
    'key' => 'mail_port',
    'value' => '465',
  ),
  10 => 
  array (
    'key' => 'mail_username',
    'value' => 'heaventree@dai.ie',
  ),
  11 => 
  array (
    'key' => 'mail_password',
    'value' => '"UwV<9hAqQ;*',
  ),
  12 => 
  array (
    'key' => 'ga4_id',
    'value' => 'UA-75194056-1',
  ),
  13 => 
  array (
    'key' => 'gtm_id',
    'value' => 'GT-K54RRRB',
  ),
  14 => 
  array (
    'key' => 'pixel_id',
    'value' => NULL,
  ),
  15 => 
  array (
    'key' => 'cc_email',
    'value' => NULL,
  ),
);

$DATA_ADMINS    = array (
  0 => 
  array (
    'username' => 'admin',
    'email' => 'abitha123@gmail.com',
    'password_hash' => '$2y$12$E8yRSlCcETdTVcVvn8N5jOueWEnr2Z0nekr59nuGPjO7xM8aVFOD.',
    'role' => 'admin',
  ),
  1 => 
  array (
    'username' => 'heaventree',
    'email' => 'info@heaventree.ie',
    'password_hash' => '$2y$12$YKc1opSLVvfWnX5WoOtwiueg3LaN/eYAxAwYoW211v34HfaWVWIve',
    'role' => 'admin',
  ),
);

$DATA_TEMPLATES = array (
  0 => 
  array (
    'slug' => 'assessment-confirmation',
    'name' => 'Assessment Application Received',
    'subject' => 'Assessment Application Received – Driver Assessments Ireland',
    'body' => '<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Dear {{name}},</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Thank you — we have received your completed assessment application.</p>
<div style="background:#f0f4ff;border-radius:8px;padding:16px 20px;margin-bottom:20px">
  <p style="margin:0 0 6px;font-size:13px;color:#555;font-weight:600;text-transform:uppercase;letter-spacing:0.04em">Reference</p>
  <p style="margin:0;font-size:18px;font-weight:700;color:#132d5e;letter-spacing:0.02em">{{orderId}}</p>
</div>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">One of our assessors will be in contact within 5 working days to arrange a suitable appointment date and location across our 32-county service area.</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0">Kind regards,<br><strong>Driver Assessments Ireland</strong><br>info@driverassessmentsireland.ie<br>+353 (0)86 0422535</p>',
    'is_active' => 1,
    'is_default' => 1,
  ),
  1 => 
  array (
    'slug' => 'hcp-referral-confirmation',
    'name' => 'HCP Referral Confirmation',
    'subject' => 'New HCP Referral – {{patientName}}',
    'body' => '<div style="margin-bottom:20px">
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
</div>',
    'is_active' => 1,
    'is_default' => 1,
  ),
  2 => 
  array (
    'slug' => 'contact-auto-reply',
    'name' => 'Contact Form Auto-Reply',
    'subject' => 'We received your message – Driver Assessments Ireland',
    'body' => '<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Dear {{name}},</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Thank you for contacting Driver Assessments Ireland. We have received your message and a member of our team will respond within <strong>2 business days</strong>.</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">If your query is urgent, please call us directly on <strong>+353 (0)86 0422535</strong>.</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0">Kind regards,<br><strong>Driver Assessments Ireland</strong><br>info@driverassessmentsireland.ie<br>+353 (0)86 0422535</p>',
    'is_active' => 1,
    'is_default' => 1,
  ),
  3 => 
  array (
    'slug' => 'payment-received',
    'name' => 'Payment Received',
    'subject' => 'Payment Confirmed – Driver Assessments Ireland',
    'body' => '<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Dear {{name}},</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">Thank you for your payment of <strong>€{{amount}}</strong>. We have received your assessment fee and your application is now being processed.</p>
<div style="background:#f0f4ff;border-radius:8px;padding:16px 20px;margin-bottom:20px">
  <p style="margin:0 0 6px;font-size:13px;color:#555;font-weight:600;text-transform:uppercase;letter-spacing:0.04em">Order Reference</p>
  <p style="margin:0;font-size:18px;font-weight:700;color:#132d5e;letter-spacing:0.02em">{{orderId}}</p>
</div>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0 0 20px">You will receive a link to complete your assessment application form shortly. Please complete it at your earliest convenience.</p>
<p style="font-size:15px;color:#333;line-height:1.6;margin:0">Kind regards,<br><strong>Driver Assessments Ireland</strong><br>info@driverassessmentsireland.ie<br>+353 (0)86 0422535</p>',
    'is_active' => 1,
    'is_default' => 1,
  ),
);

$DATA_CMS       = array (
  0 => 
  array (
    'slug' => 'about',
    'title' => 'About Us',
    'content' => '<p>Driver Assessments Ireland provides specialist driving assessments for individuals with medical conditions or disabilities across all 32 counties of Ireland.</p>',
    'content_json' => NULL,
    'meta_description' => 'About Driver Assessments Ireland - specialist driving assessors serving all 32 counties.',
    'image_path' => NULL,
    'is_published' => 1,
  ),
  1 => 
  array (
    'slug' => 'faq',
    'title' => 'Frequently Asked Questions',
    'content' => '',
    'content_json' => NULL,
    'meta_description' => 'Frequently asked questions about driver assessments in Ireland.',
    'image_path' => NULL,
    'is_published' => 1,
  ),
  2 => 
  array (
    'slug' => 'contact',
    'title' => 'Contact Us',
    'content' => '<p>Get in touch with our team at Driver Assessments Ireland.</p>',
    'content_json' => NULL,
    'meta_description' => 'Contact Driver Assessments Ireland - phone, email and enquiry form.',
    'image_path' => NULL,
    'is_published' => 1,
  ),
  3 => 
  array (
    'slug' => 'service-stroke',
    'title' => 'Stroke & Acquired Brain Injury',
    'content' => NULL,
    'content_json' => '{"summary":"Comprehensive post-stroke driving evaluations assessing cognitive, visual, and motor function to support safe return to driving.","overview":"A stroke or acquired brain injury (ABI) can affect many of the skills needed to drive safely, including vision, concentration, reaction speed, and physical control of the vehicle. Many people can return to driving after a stroke or ABI, but an independent assessment is usually required before doing so.\\n\\nAt Driver Assessments Ireland, our specialist occupational therapists conduct thorough evaluations that consider all aspects of driving ability in the context of your specific condition and recovery progress.","who":"This assessment is for individuals who:\\n\\n- Have had a stroke or transient ischaemic attack (TIA)\\n- Have sustained a traumatic or non-traumatic acquired brain injury\\n- Have been advised by their GP or neurologist to have their fitness to drive assessed\\n- Wish to return to driving following rehabilitation\\n- Hold a Group 1 or Group 2 (vocational) driving licence","impact":"A stroke or ABI can affect driving in many ways depending on the area of the brain affected. Common impacts include:\\n\\n- Visual field deficits (hemianopia or quadrantanopia) affecting the ability to see hazards on one side\\n- Slowed reaction time and reduced processing speed\\n- Impaired attention, concentration, or divided attention\\n- Physical weakness or spasticity affecting steering, braking, or gear control\\n- Fatigue, which can significantly impair sustained driving performance\\n- Cognitive changes including memory, planning, and decision-making","assessment":"Our assessment involves three stages:\\n\\n1. Pre-drive clinical interview: Review of medical history, medications, and any relevant reports from your neurologist, GP, or rehabilitation team.\\n\\n2. Off-road evaluation: Standardised cognitive and perceptual tests relevant to driving, including visual field screening and reaction time assessment.\\n\\n3. On-road assessment: A structured drive of 45\\u201360 minutes on a variety of road types in your local area. The assessor will evaluate all aspects of your driving.\\n\\nFollowing the assessment, a detailed written report is prepared outlining findings and recommendations. This is typically provided within 5\\u20137 working days.","buttons":[{"label":"RSA Guidelines","url":"https:\\/\\/www.rsa.ie\\/services\\/licensed-drivers\\/medical-fitness-to-drive"},{"label":"Stroke Alliance Ireland","url":"https:\\/\\/strokeallianceireland.ie\\/"}]}',
    'meta_description' => 'Driver assessment for stroke and acquired brain injury — serving all 32 counties of Ireland.',
    'image_path' => '/images/services/service-stroke.jpg',
    'is_published' => 1,
  ),
  4 => 
  array (
    'slug' => 'service-parkinsons',
    'title' => 'Parkinson\'s Disease',
    'content' => NULL,
    'content_json' => '{"summary":"Specialist assessment accounting for motor fluctuations, medication timing, and cognitive changes associated with Parkinson\'s disease.","overview":"Parkinson\'s disease is a progressive neurological condition that can affect driving in complex ways. The impact varies significantly between individuals and at different stages of the disease. Many people with Parkinson\'s can continue to drive safely, particularly in the earlier stages, with appropriate assessment and monitoring.\\n\\nOur assessors have specialist experience in evaluating drivers with Parkinson\'s, understanding the importance of timing the assessment in relation to medication cycles (on\\/off periods).","who":"This assessment is recommended for:\\n\\n- Individuals with a diagnosis of Parkinson\'s disease or Parkinsonism who wish to continue driving\\n- Those referred by their GP, neurologist, or specialist Parkinson\'s nurse\\n- Drivers who have noticed changes in their driving and want an independent evaluation\\n- Individuals whose licence has been queried by the NDLS","impact":"Parkinson\'s disease can affect driving through both motor and non-motor symptoms:\\n\\n- Tremor affecting steering control and gear changes\\n- Rigidity and bradykinesia (slowness of movement) affecting reaction times and vehicle control\\n- Postural instability affecting the ability to look over the shoulder or react to sudden hazards\\n- Visual changes including reduced contrast sensitivity\\n- Cognitive changes including attention, visuospatial processing, and executive function\\n- Sudden \'off\' periods where medication effectiveness drops significantly\\n- Excessive daytime sleepiness, a common non-motor symptom","assessment":"We work closely with the referring clinician to schedule the assessment at an optimal time in the medication cycle. The assessment includes:\\n\\n1. Clinical interview covering symptom profile, medication regimen, and \'on\\/off\' patterns.\\n\\n2. Standardised cognitive and motor screening relevant to driving.\\n\\n3. On-road assessment conducted during an \'on\' period when medication is most effective.\\n\\nA detailed report is provided to you and, with your consent, to your neurologist or GP.","buttons":[{"label":"Parkinson\'s Ireland","url":"https:\\/\\/www.parkinsons.ie\\/"},{"label":"RSA Medical Fitness","url":"https:\\/\\/www.rsa.ie\\/services\\/licensed-drivers\\/medical-fitness-to-drive"}]}',
    'meta_description' => 'Driver assessment for Parkinson\'s disease — specialist evaluation serving all 32 counties of Ireland.',
    'image_path' => '/images/services/service-parkinsons.jpg',
    'is_published' => 1,
  ),
  5 => 
  array (
    'slug' => 'service-dementia',
    'title' => 'Dementia & Cognitive Decline',
    'content' => NULL,
    'content_json' => '{"summary":"Sensitive, evidence-based evaluations for individuals with mild to moderate cognitive impairment, including Alzheimer\'s disease.","overview":"A diagnosis of dementia does not automatically mean that someone cannot drive. Many individuals in the early stages of dementia can continue to drive safely, though their driving fitness needs to be formally assessed and monitored over time.\\n\\nDriver Assessments Ireland offers compassionate, expert evaluations for individuals and families navigating this difficult issue. Our assessors understand the emotional complexity involved and approach every assessment with sensitivity and respect for the individual.","who":"This assessment is appropriate for:\\n\\n- Individuals with a diagnosis of Alzheimer\'s disease or other dementia syndromes\\n- Those experiencing mild cognitive impairment (MCI) where driving is a concern\\n- People referred by their GP, geriatrician, or memory clinic\\n- Family members concerned about an older relative\'s driving safety","impact":"Cognitive decline can affect driving in the following ways:\\n\\n- Difficulty with route-finding and navigation, even on familiar roads\\n- Reduced ability to process multiple pieces of information simultaneously\\n- Impaired judgement at junctions and in complex traffic situations\\n- Slowed reaction times and reduced hazard perception\\n- Getting lost or confused, particularly in unfamiliar environments\\n- Visuospatial difficulties affecting parking and lane positioning","assessment":"Our assessment is structured to be as comfortable and non-threatening as possible for the individual:\\n\\n1. Initial consultation with the individual and, if appropriate, a family member or carer. We review all relevant medical information.\\n\\n2. Brief cognitive screening using validated tools relevant to driving.\\n\\n3. On-road assessment in familiar, local roads wherever possible.\\n\\nThe written report is provided to the individual and, with consent, to their GP or specialist. We provide clear, sensitive communication of findings.","buttons":[{"label":"Alzheimer\'s Society of Ireland","url":"https:\\/\\/alzheimer.ie\\/"},{"label":"RSA Medical Fitness","url":"https:\\/\\/www.rsa.ie\\/services\\/licensed-drivers\\/medical-fitness-to-drive"}]}',
    'meta_description' => 'Driver assessment for dementia and cognitive decline — compassionate, evidence-based evaluation across Ireland.',
    'image_path' => '/images/services/service-dementia.jpg',
    'is_published' => 1,
  ),
  6 => 
  array (
    'slug' => 'service-epilepsy',
    'title' => 'Epilepsy & Seizure Disorders',
    'content' => NULL,
    'content_json' => '{"summary":"Assessment of driving fitness following seizure events, in line with RSA guidelines on seizure-free periods and licence categories.","overview":"Epilepsy and seizure disorders require careful consideration in the context of driving. Irish and European regulations set out specific seizure-free periods before an individual can return to driving, which vary depending on the type of seizure, licence category (Group 1 private or Group 2 vocational), and circumstances of the seizure.\\n\\nDriver Assessments Ireland provides expert assessments to support individuals returning to driving after a seizure event, and provides comprehensive reports accepted by the NDLS.","who":"This assessment is for:\\n\\n- Individuals who have had a seizure and wish to return to driving after the required seizure-free period\\n- Those referred by their GP or neurologist\\n- Drivers with established epilepsy whose medication has changed\\n- Holders of Group 2 (vocational) licences requiring more stringent assessment","impact":"The primary concern with epilepsy and driving is the risk of a seizure occurring while driving. Additional considerations include:\\n\\n- Medication side effects such as drowsiness or slowed processing\\n- Cognitive effects of longstanding epilepsy\\n- Post-ictal states (confusion following a seizure)\\n- Nocturnal seizures and their relevance to Group 1 licence holders\\n- Visual field deficits in some epilepsy syndromes","assessment":"Our assessment follows RSA and DVLA guidelines closely:\\n\\n1. Clinical interview to document seizure history, type, frequency, medication, and seizure-free period.\\n\\n2. Review of specialist neurologist reports and any relevant investigations.\\n\\n3. Functional on-road assessment where indicated.\\n\\nWe work with your neurologist to ensure all documentation required by the NDLS is in order. Our report clearly states whether the current seizure-free period and clinical picture are consistent with safe driving under current regulations.","buttons":[{"label":"Epilepsy Ireland","url":"https:\\/\\/www.epilepsy.ie\\/"},{"label":"RSA Medical Fitness","url":"https:\\/\\/www.rsa.ie\\/services\\/licensed-drivers\\/medical-fitness-to-drive"}]}',
    'meta_description' => 'Driver assessment for epilepsy and seizure disorders — RSA guideline compliant evaluation across all 32 counties.',
    'image_path' => '/images/services/service-epilepsy.jpg',
    'is_published' => 1,
  ),
  7 => 
  array (
    'slug' => 'service-visual',
    'title' => 'Visual Impairments',
    'content' => NULL,
    'content_json' => '{"summary":"On-road functional visual assessment for drivers with reduced acuity, visual field loss, or conditions such as glaucoma and macular degeneration.","overview":"Good vision is fundamental to safe driving. Irish law sets minimum visual standards for driving, but meeting the legal minimum does not always mean an individual is safe to drive. A functional driving assessment provides a real-world picture of how a visual impairment affects actual driving performance.\\n\\nDriver Assessments Ireland works with individuals with a wide range of visual conditions to provide expert, evidence-based assessments of their driving fitness.","who":"This assessment is appropriate for:\\n\\n- Individuals with glaucoma, macular degeneration, or other progressive eye conditions\\n- Those who have had a visual field defect following stroke or neurological injury\\n- Drivers with monocular vision (vision in one eye only)\\n- People referred by their ophthalmologist, optometrist, or GP with visual concerns\\n- Individuals whose visual acuity has changed significantly","impact":"Visual impairments can affect driving in the following ways:\\n\\n- Reduced visual acuity making it difficult to read road signs or identify hazards at distance\\n- Visual field loss creating \'blind spots\' for other road users, pedestrians, or hazards\\n- Impaired contrast sensitivity making driving in low light or fog more dangerous\\n- Monocular vision affecting depth perception, particularly relevant for parking and overtaking\\n- Photosensitivity making driving in bright sunlight difficult","assessment":"Our visual driving assessment includes:\\n\\n1. Review of ophthalmology reports and any visual field charts.\\n\\n2. Clinical vision screening including a number plate test (legal requirement), visual acuity, and visual field assessment.\\n\\n3. On-road assessment evaluating how the visual impairment affects real-world driving performance.\\n\\nWhere adaptive equipment (e.g. bioptic telescopes, tinted lenses) may be beneficial, we can advise on appropriate resources.","buttons":[{"label":"NCBI Vision Ireland","url":"https:\\/\\/ncbi.ie\\/"},{"label":"RSA Visual Standards","url":"https:\\/\\/www.rsa.ie\\/services\\/licensed-drivers\\/medical-fitness-to-drive"}]}',
    'meta_description' => 'Driver assessment for visual impairments — functional visual evaluation for Irish drivers.',
    'image_path' => NULL,
    'is_published' => 1,
  ),
  8 => 
  array (
    'slug' => 'service-age-related',
    'title' => 'Age-Related Assessment',
    'content' => NULL,
    'content_json' => '{"summary":"Holistic evaluation for older drivers addressing the cumulative effects of ageing on driving performance, safety awareness, and reaction times.","overview":"Many older drivers drive safely well into their 70s, 80s, and beyond. However, normal ageing processes can gradually affect the skills needed for safe driving. Driver Assessments Ireland provides respectful, expert evaluations that give older drivers and their families an honest, evidence-based picture of current driving ability.\\n\\nOur assessors are experienced in working sensitively with older adults, understanding that driving represents independence and dignity for many people.","who":"An age-related assessment may be appropriate for:\\n\\n- Older drivers (typically 70+) who wish to self-assess or have been requested to do so by their GP\\n- Those whose family members have expressed concerns about driving safety\\n- Drivers who have noticed changes in their own driving ability\\n- Individuals who have recently had a fall, hospitalisation, or new medical diagnosis\\n- Drivers required by the NDLS to undergo assessment","impact":"Normal ageing can affect driving in the following ways:\\n\\n- Slowed reaction times and reduced processing speed\\n- Reduced flexibility in the neck and shoulders affecting mirror checks and reversing\\n- Changes in night vision and increased sensitivity to glare\\n- Reduced ability to divide attention between multiple tasks\\n- Fatigue affecting sustained driving ability on longer journeys\\n- Cumulative medication effects\\n- Subtle cognitive changes affecting navigation and decision-making","assessment":"Our age-related assessment is designed to be thorough but non-threatening:\\n\\n1. Pre-drive consultation covering medical history, current medications, driving patterns, and any concerns.\\n\\n2. Screening for visual acuity, visual fields, cognitive function, and physical range of movement.\\n\\n3. On-road assessment on familiar local roads, evaluating all aspects of driving performance.\\n\\nWe provide clear, honest feedback and practical recommendations \\u2014 including whether adaptive equipment, vehicle modifications, or driving restriction (e.g. daylight only, local area only) might enable safe continued driving.","buttons":[{"label":"Age Action Ireland","url":"https:\\/\\/www.ageaction.ie\\/"},{"label":"RSA Medical Fitness","url":"https:\\/\\/www.rsa.ie\\/services\\/licensed-drivers\\/medical-fitness-to-drive"}]}',
    'meta_description' => 'Driver assessment for older drivers — holistic evaluation for age-related changes in driving ability across Ireland.',
    'image_path' => '/images/services/service-age-related.jpg',
    'is_published' => 1,
  ),
  9 => 
  array (
    'slug' => 'service-congenital-disorders',
    'title' => 'Congenital Disorders',
    'content' => NULL,
    'content_json' => '{"summary":"Many people living with congenital conditions drive safely with the right assessment, adaptive equipment, and support.","overview":"Congenital conditions \\u2014 those present from birth \\u2014 can affect physical, cognitive, or neurological functioning. With the right assessment and, where needed, vehicle adaptations, many people with congenital conditions can drive safely and independently.","impact":"Depending on the specific condition, driving may be affected through physical limitations in limb function, visual or perceptual difficulties, cognitive or processing challenges, fatigue and reduced endurance.","assessment":"Our assessment identifies the individual\'s current driving abilities across physical, visual, and cognitive domains. Where vehicle modifications may help, we advise on adaptive equipment and can liaise with approved vehicle adaptation providers.","buttons":[]}',
    'meta_description' => 'Many people living with congenital conditions drive safely with the right assessment, adaptive equipment, and support.',
    'image_path' => '/images/services/service-congenital-disorders.jpg',
    'is_published' => 1,
  ),
  10 => 
  array (
    'slug' => 'service-neurological-disorders',
    'title' => 'Neurological Disorders',
    'content' => NULL,
    'content_json' => '{"summary":"Conditions such as MS, Parkinson\'s, and epilepsy require specialist evaluation to determine fitness to drive.","overview":"A wide range of neurological conditions can affect driving ability. These include Multiple Sclerosis (MS), Parkinson\'s disease, Motor Neurone Disease (MND), brain tumours, and other acquired or progressive neurological conditions.","impact":"Neurological disorders may affect driving through tremor and motor control difficulties, fatigue and reduced endurance, visual disturbances, cognitive and memory changes, slowed reaction times, and unpredictable symptom fluctuations.","assessment":"We provide a thorough clinical assessment and on-road evaluation tailored to the individual\'s specific neurological condition. Our assessors have specialist experience across a wide range of neurological presentations and work with drivers at all stages of their condition.","buttons":[]}',
    'meta_description' => 'Conditions such as MS, Parkinson\'s, and epilepsy require specialist evaluation to determine fitness to drive.',
    'image_path' => '/images/services/service-neurological-disorders.jpg',
    'is_published' => 1,
  ),
  11 => 
  array (
    'slug' => 'service-brain-injury',
    'title' => 'Brain Injury',
    'content' => NULL,
    'content_json' => '{"summary":"Acquired brain injury affects drivers in complex ways. Our specialist assessors provide thorough evaluation and practical guidance.","overview":"Acquired brain injury (ABI), whether resulting from trauma, stroke, infection, or other causes, can affect every aspect of driving. Effects may be subtle but can significantly increase crash risk.","impact":"Brain injury can affect driving through physical deficits affecting vehicle control, cognitive changes including attention, memory, and processing speed, impulsivity and poor insight, fatigue, and changes in personality or behaviour that affect driving judgement.","assessment":"Our ABI driver assessments are comprehensive and include detailed cognitive and functional screening, an on-road evaluation with a specialist assessor, and a written report. We understand the complexity of ABI and the importance of accurate, sensitive assessment.","buttons":[]}',
    'meta_description' => 'Acquired brain injury affects drivers in complex ways. Our specialist assessors provide thorough evaluation and practical guidance.',
    'image_path' => '/images/services/service-brain-injury.jpg',
    'is_published' => 1,
  ),
  12 => 
  array (
    'slug' => 'service-cardiovascular-disorders',
    'title' => 'Cardiovascular Disorders',
    'content' => NULL,
    'content_json' => '{"summary":"Heart conditions and recent cardiac events may require medical clearance and a fitness to drive assessment.","overview":"Cardiovascular conditions including heart attack, heart failure, arrhythmias, and angina can affect fitness to drive. The NDLS has specific medical fitness standards for cardiovascular conditions which must be met before driving can be resumed.","impact":"Cardiovascular disorders can affect driving through sudden onset of symptoms such as dizziness or loss of consciousness, reduced stamina and endurance, effects of medication on alertness and reaction time, and anxiety following a cardiac event.","assessment":"Our assessment works alongside medical reports from your cardiologist or GP to provide a holistic picture of your driving fitness. We conduct a functional and on-road evaluation and provide a report that can support your application to the NDLS.","buttons":[]}',
    'meta_description' => 'Heart conditions and recent cardiac events may require medical clearance and a fitness to drive assessment.',
    'image_path' => '/images/services/service-cardiovascular-disorders.jpg',
    'is_published' => 1,
  ),
  13 => 
  array (
    'slug' => 'service-psychiatric-disorders',
    'title' => 'Psychiatric Disorders',
    'content' => NULL,
    'content_json' => '{"summary":"Mental health conditions and their treatments can affect driving. We provide sensitive, non-judgemental assessments.","overview":"Certain psychiatric conditions and their pharmacological treatments can affect driving ability. It is important that these are assessed in a sensitive, person-centred manner, balancing the individual\'s right to drive with safety on public roads.","impact":"Psychiatric conditions may affect driving through impaired concentration and attention, slower reaction times, effects of psychotropic medication on alertness and cognition, impulsivity, and fluctuating mental state.","assessment":"Our assessors approach psychiatric assessments with sensitivity and professionalism. We work with the individual\'s treating clinician where appropriate and produce a balanced, evidence-based report that considers the person\'s wellbeing alongside driving safety.","buttons":[]}',
    'meta_description' => 'Mental health conditions and their treatments can affect driving. We provide sensitive, non-judgemental assessments.',
    'image_path' => '/images/services/service-psychiatric-disorders.jpg',
    'is_published' => 1,
  ),
  14 => 
  array (
    'slug' => 'service-diabetes-mellitus',
    'title' => 'Diabetes Mellitus',
    'content' => NULL,
    'content_json' => '{"summary":"People living with diabetes can drive safely. We help assess fitness to drive and advise on managing diabetes while driving.","overview":"Many people with diabetes drive safely. However, both hypoglycaemia (low blood sugar) and long-term complications of diabetes \\u2014 such as neuropathy, retinopathy, and cardiovascular disease \\u2014 can affect driving fitness.","impact":"Diabetes can affect driving through hypoglycaemia causing impaired consciousness or reaction time, peripheral neuropathy affecting vehicle control, visual impairment from diabetic retinopathy, and cardiovascular complications.","assessment":"Our assessment considers all aspects of diabetes management in the context of driving. We review the individual\'s history and current management, conduct a functional assessment, and provide practical recommendations around safe driving and hypoglycaemia management.","buttons":[]}',
    'meta_description' => 'People living with diabetes can drive safely. We help assess fitness to drive and advise on managing diabetes while driving.',
    'image_path' => '/images/services/service-diabetes-mellitus.jpg',
    'is_published' => 1,
  ),
  15 => 
  array (
    'slug' => 'service-renal-disorders',
    'title' => 'Renal Disorders',
    'content' => NULL,
    'content_json' => '{"summary":"Chronic kidney disease and dialysis can affect energy levels and cognitive function relevant to safe driving.","overview":"Chronic kidney disease (CKD) and the treatments required \\u2014 particularly dialysis \\u2014 can cause significant fatigue and may affect cognitive function and physical ability. These factors are relevant to fitness to drive.","impact":"Renal disorders can affect driving through severe fatigue, particularly post-dialysis, cognitive effects of uraemia, physical complications including cardiovascular disease, and effects of anaemia on alertness and stamina.","assessment":"We work with the individual\'s renal team to understand their current health status and conduct a tailored driving assessment. We advise on appropriate driving times relative to dialysis sessions and any other relevant safety considerations.","buttons":[]}',
    'meta_description' => 'Chronic kidney disease and dialysis can affect energy levels and cognitive function relevant to safe driving.',
    'image_path' => '/images/services/service-renal-disorders.jpg',
    'is_published' => 1,
  ),
  16 => 
  array (
    'slug' => 'service-auditory-visual-sensory-loss',
    'title' => 'Auditory or Visual Sensory Loss',
    'content' => NULL,
    'content_json' => '{"summary":"Sensory loss including vision or hearing changes may affect driving safety. Our assessment identifies practical solutions.","overview":"Changes in vision or hearing can affect driving ability in significant ways. In Ireland, all drivers must meet minimum visual standards for driving. Hearing loss, while not a barrier to driving in most cases, can affect hazard awareness.","impact":"Sensory changes affecting driving include reduced visual acuity or field of vision, difficulty with contrast sensitivity and night vision, inability to see potential hazards at the periphery, and reduced awareness of auditory cues such as sirens or horns.","assessment":"Our assessment includes a thorough vision screening, a review of hearing aids or other assistive devices, and a practical on-road driving evaluation. Where vision is a concern, we liaise with optometrists and ophthalmologists as needed.","buttons":[]}',
    'meta_description' => 'Sensory loss including vision or hearing changes may affect driving safety. Our assessment identifies practical solutions.',
    'image_path' => '/images/services/service-auditory-visual-sensory-loss.jpg',
    'is_published' => 1,
  ),
  17 => 
  array (
    'slug' => 'service-respiratory-sleep-disorders',
    'title' => 'Respiratory & Sleep Disorders',
    'content' => NULL,
    'content_json' => '{"summary":"Conditions like sleep apnoea, COPD, and severe asthma can impair alertness and driving safety.","overview":"Respiratory conditions \\u2014 particularly those affecting sleep quality such as obstructive sleep apnoea (OSA) \\u2014 can severely impair alertness and driving. OSA in particular is associated with significantly increased crash risk.","impact":"Respiratory and sleep disorders affect driving through excessive daytime sleepiness and fatigue, microsleeps at the wheel, impaired concentration and reaction time, and the effects of oxygen desaturation on cognitive function.","assessment":"Our assessment explores the individual\'s sleep history, treatment compliance (e.g. CPAP therapy), and functional alertness. We provide practical recommendations and can support NDLS applications where required.","buttons":[]}',
    'meta_description' => 'Conditions like sleep apnoea, COPD, and severe asthma can impair alertness and driving safety.',
    'image_path' => '/images/services/service-respiratory-sleep-disorders.jpg',
    'is_published' => 1,
  ),
  18 => 
  array (
    'slug' => 'service-learning-difficulties',
    'title' => 'Learning Difficulties',
    'content' => NULL,
    'content_json' => '{"summary":"Many people with learning difficulties drive successfully. Our assessment focuses on ability, not diagnosis.","overview":"People with learning difficulties, including intellectual disability, dyslexia, dyspraxia, and ADHD, can and do drive successfully. Our assessment focuses on what the individual can do, not their diagnosis.","impact":"Depending on the specific difficulty, driving may be affected through difficulties processing visual or spatial information, challenges with multi-tasking and divided attention, impulsivity or difficulty with hazard perception, and difficulties learning new routes or reading road signs.","assessment":"Our person-centred assessment considers the whole individual, using practical and functional approaches rather than purely cognitive testing. We identify strengths as well as areas for development and advise on strategies and, where needed, specialist driving tuition.","buttons":[]}',
    'meta_description' => 'Many people with learning difficulties drive successfully. Our assessment focuses on ability, not diagnosis.',
    'image_path' => '/images/services/service-learning-difficulties.jpg',
    'is_published' => 1,
  ),
  19 => 
  array (
    'slug' => 'service-your-health-and-driving',
    'title' => 'Your Health and Driving',
    'content' => NULL,
    'content_json' => '{"summary":"Any significant health change may affect your fitness to drive. If you\'re unsure, a professional assessment gives clarity.","overview":"Many health conditions not listed individually here may still affect fitness to drive. If you or your healthcare professional have any concern about whether your health is affecting your driving, a professional assessment can provide clarity and peace of mind.","impact":"General health factors that may affect driving include side effects of prescription medications, post-operative recovery and reduced physical function, chronic pain affecting concentration and mobility, fatigue from any significant illness, and age-related changes in reaction time and vision.","assessment":"Our general health and driving assessments are tailored to the individual. We take a comprehensive history, conduct relevant functional tests, and complete an on-road assessment. Our report provides clear, practical recommendations.","buttons":[]}',
    'meta_description' => 'Any significant health change may affect your fitness to drive. If you\'re unsure, a professional assessment gives clarity.',
    'image_path' => '/images/services/service-your-health-and-driving.jpg',
    'is_published' => 1,
  ),
);

// Settings
$stmt = $pdo->prepare("INSERT INTO `settings` (`key`,`value`,`created_at`,`updated_at`) VALUES (?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE `value`=VALUES(`value`),`updated_at`=NOW()");
foreach ($DATA_SETTINGS as $r) { $stmt->execute([$r['key'], $r['value']]); }
echo "Settings: " . count($DATA_SETTINGS) . " rows upserted\n";

// Admin users
$stmt = $pdo->prepare("INSERT INTO `admin_users` (`username`,`email`,`password_hash`,`role`,`created_at`,`updated_at`) VALUES (?,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE `email`=VALUES(`email`),`password_hash`=VALUES(`password_hash`),`updated_at`=NOW()");
foreach ($DATA_ADMINS as $r) { $stmt->execute([$r['username'], $r['email'], $r['password_hash'], $r['role']]); }
echo "Admin users: " . count($DATA_ADMINS) . " rows upserted\n";

// Email templates
$stmt = $pdo->prepare("INSERT INTO `email_templates` (`slug`,`name`,`subject`,`body`,`is_active`,`is_default`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE `name`=VALUES(`name`),`subject`=VALUES(`subject`),`body`=VALUES(`body`),`updated_at`=NOW()");
foreach ($DATA_TEMPLATES as $r) { $stmt->execute([$r['slug'], $r['name'], $r['subject'], $r['body'], $r['is_active'], $r['is_default']]); }
echo "Email templates: " . count($DATA_TEMPLATES) . " rows upserted\n";

// CMS pages
$stmt = $pdo->prepare("INSERT INTO `cms_pages` (`slug`,`title`,`content`,`content_json`,`meta_description`,`image_path`,`is_published`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE `title`=VALUES(`title`),`content`=VALUES(`content`),`content_json`=VALUES(`content_json`),`meta_description`=VALUES(`meta_description`),`image_path`=VALUES(`image_path`),`updated_at`=NOW()");
foreach ($DATA_CMS as $r) { $stmt->execute([$r['slug'], $r['title'], $r['content'], $r['content_json'], $r['meta_description'], $r['image_path'], $r['is_published']]); }
echo "CMS pages: " . count($DATA_CMS) . " rows upserted\n\n";

echo "Done. Delete this file now.\n";

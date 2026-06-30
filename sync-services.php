<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CmsPage;

// Legacy/duplicate service pages superseded by the canonical 14-service list.
// Their content is folded into: service-age-related -> service-your-health-and-driving,
// service-visual -> service-auditory-visual-sensory-loss,
// service-parkinsons -> service-neurological-disorders.
$obsolete = ['service-parkinsons', 'service-visual', 'service-age-related'];

foreach ($obsolete as $slug) {
    $page = CmsPage::where('slug', $slug)->first();
    if ($page) {
        echo "Deleting obsolete service: {$slug} ({$page->title})\n";
        $page->delete();
    } else {
        echo "Not found (already removed): {$slug}\n";
    }
}

// Canonical 14 services — verify all exist; report any missing.
$canonical = [
    'service-dementia',
    'service-congenital-disorders',
    'service-stroke',
    'service-neurological-disorders',
    'service-brain-injury',
    'service-cardiovascular-disorders',
    'service-psychiatric-disorders',
    'service-diabetes-mellitus',
    'service-renal-disorders',
    'service-auditory-visual-sensory-loss',
    'service-respiratory-sleep-disorders',
    'service-learning-difficulties',
    'service-epilepsy',
    'service-your-health-and-driving',
];

echo "\nChecking canonical 14 services:\n";
foreach ($canonical as $slug) {
    $exists = CmsPage::where('slug', $slug)->exists();
    echo ($exists ? "OK   " : "MISSING ") . $slug . "\n";
}

echo "\nDone.\n";

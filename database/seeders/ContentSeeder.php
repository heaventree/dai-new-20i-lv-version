<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CmsPage;
use App\Models\TeamMember;
use App\Models\Setting;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $scraped = base_path('../../scripts/scraped');

        $this->seedTeamMembers($scraped . '/team_members.json');
        $this->seedCmsPages($scraped . '/cms_pages.json');
        $this->seedSettings($scraped . '/settings.json');
    }

    private function seedTeamMembers(string $path): void
    {
        if (!file_exists($path)) {
            $this->command->warn("team_members.json not found — skipping.");
            return;
        }

        $members = json_decode(file_get_contents($path), true);
        $count = 0;

        foreach ($members as $data) {
            TeamMember::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'name'          => $data['name'],
                    'title'         => $data['title'] ?? null,
                    'bio'           => $data['bio'] ?? null,
                    'photo'         => $data['photo'] ?? null,
                    'display_order' => $data['display_order'] ?? 0,
                    'is_active'     => $data['is_active'] ?? true,
                ]
            );
            $count++;
        }

        $this->command->info("Team members seeded: {$count}");
    }

    private function seedCmsPages(string $path): void
    {
        if (!file_exists($path)) {
            $this->command->warn("cms_pages.json not found — skipping.");
            return;
        }

        $pages = json_decode(file_get_contents($path), true);
        $count = 0;

        foreach ($pages as $data) {
            CmsPage::updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'title'            => $data['title'],
                    'content'          => $data['content'] ?? null,
                    'content_json'     => $data['content_json'] ?? null,
                    'meta_description' => $data['meta_description'] ?? null,
                    'is_published'     => $data['is_published'] ?? true,
                ]
            );
            $count++;
        }

        $this->command->info("CMS pages seeded: {$count}");
    }

    private function seedSettings(string $path): void
    {
        if (!file_exists($path)) {
            $this->command->warn("settings.json not found — skipping.");
            return;
        }

        $settings = json_decode(file_get_contents($path), true);
        $count = 0;

        foreach ($settings as $data) {
            Setting::updateOrCreate(
                ['key' => $data['key']],
                ['value' => $data['value']]
            );
            $count++;
        }

        $this->command->info("Settings seeded: {$count}");
    }
}

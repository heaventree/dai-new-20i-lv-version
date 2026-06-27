<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Env-var fallbacks for deployment-critical settings.
     * When the DB row is empty (fresh deployment), these env vars are used instead.
     */
    private static array $envFallbacks = [
        'stripe_secret_key_test'      => ['STRIPE_SECRET_KEY', 'STRIPE_SECRET'],
        'stripe_secret_key_live'      => ['STRIPE_SECRET_KEY_LIVE', 'STRIPE_SECRET'],
        'stripe_publishable_key_test' => ['STRIPE_PUBLISHABLE_KEY', 'STRIPE_PUBLIC_KEY'],
        'stripe_publishable_key_live' => ['STRIPE_PUBLISHABLE_KEY_LIVE', 'STRIPE_PUBLIC_KEY'],
    ];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        $value   = $setting ? $setting->value : null;

        if (($value === null || $value === '') && isset(self::$envFallbacks[$key])) {
            foreach (self::$envFallbacks[$key] as $envKey) {
                $envVal = env($envKey);
                if ($envVal !== null && $envVal !== '') {
                    return $envVal;
                }
            }
        }

        return ($value !== null && $value !== '') ? $value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

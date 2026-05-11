<?php

namespace App\Support;

use App\Models\ProfileFieldDefinition;
use Illuminate\Validation\Rule;

class ProfileFieldRules
{
    /**
     * Build a Laravel rules array for the given field, keyed by the input
     * field_key (and `field_key.subKey` for shape sub-fields). The output
     * can be merged into a single rules array passed to Validator::make so
     * that all definition-driven validation runs in one pass.
     *
     * @return array<string, list<mixed>>
     */
    public static function buildFor(string $key, ProfileFieldDefinition $definition): array
    {
        $rules = [$key => self::rootRules($definition)];
        $config = $definition->validation_rules ?? [];

        if ($definition->data_type === 'json' && isset($config['shape']) && is_array($config['shape'])) {
            $required = $config['required_keys'] ?? [];

            foreach ($config['shape'] as $subKey => $subType) {
                $subRules = in_array($subKey, $required, true) ? ['required'] : ['nullable'];
                $subRules[] = self::laravelTypeFor((string) $subType);
                $rules["{$key}.{$subKey}"] = array_values(array_filter($subRules));
            }
        }

        if ($definition->data_type === 'json' && isset($config['array_of'])) {
            $rules["{$key}.*"] = [self::laravelTypeFor((string) $config['array_of'])];
        }

        return $rules;
    }

    /**
     * @return list<mixed>
     */
    private static function rootRules(ProfileFieldDefinition $definition): array
    {
        $config = $definition->validation_rules ?? [];

        return match ($definition->data_type) {
            'date' => array_values(array_filter([
                'required',
                'date',
                isset($config['before_or_equal']) ? 'before_or_equal:'.$config['before_or_equal'] : null,
                isset($config['after_or_equal']) ? 'after_or_equal:'.$config['after_or_equal'] : null,
            ])),
            'enum' => array_values(array_filter([
                'required',
                'string',
                isset($config['enum']) && is_array($config['enum']) ? Rule::in($config['enum']) : null,
            ])),
            'string' => array_values(array_filter([
                'required',
                'string',
                isset($config['max_length']) ? 'max:'.(int) $config['max_length'] : null,
            ])),
            'phone' => ['required', 'string', 'max:32'],
            'email' => ['required', 'email', 'max:255'],
            'json' => ['required', 'array'],
            default => ['required'],
        };
    }

    private static function laravelTypeFor(string $type): string
    {
        return match ($type) {
            'string' => 'string',
            'int', 'integer' => 'integer',
            'bool', 'boolean' => 'boolean',
            'date' => 'date',
            'email' => 'email',
            'array' => 'array',
            default => 'string',
        };
    }
}

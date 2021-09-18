<?php

namespace Quillstack\Dotenv;

class ValueTypes
{
    public function extractValueTypes(mixed &$value): void
    {
        $this->extractMultiLine($value);

        if ($this->extractStringValue($value)) {
            return;
        }

        if ($this->extractBooleanValues($value)) {
            return;
        }

        $this->extractNumericValues($value);
    }

    private function extractMultiLine(mixed &$value): void
    {
        if (!is_string($value)) {
            return;
        }

        $value = str_ireplace('\n', "\n", $value);
    }

    private function extractStringValue(mixed &$value): bool
    {
        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            $value = trim($value, '"');

            return true;
        }

        if (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            $value = trim($value, "'");

            return true;
        }

        return false;
    }

    /**
     * Tries to extract boolean values.
     */
    private function extractBooleanValues(mixed &$value): bool
    {
        if (strcasecmp($value, 'true') === 0) {
            $value = true;

            return true;
        } elseif (strcasecmp($value, 'false') === 0) {
            $value = false;

            return true;
        }

        return false;
    }

    private function extractNumericValues(mixed &$value): void
    {
        if (!is_numeric($value)) {
            return;
        }

        if (strstr($value, '.')) {
            $value = (float) $value;
        } else {
            $value = (int) $value;
        }
    }
}

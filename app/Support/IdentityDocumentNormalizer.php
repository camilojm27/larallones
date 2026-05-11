<?php

namespace App\Support;

class IdentityDocumentNormalizer
{
    /**
     * Strip dots, dashes, spaces and uppercase the document number so that
     * `1.234.567.890`, `1-234-567-890` and `1234567890` collide on the unique
     * index `(country_code, document_type, document_number)`.
     */
    public static function normalize(string $documentNumber): string
    {
        $stripped = preg_replace('/[\s.\-]+/u', '', $documentNumber) ?? '';

        return strtoupper($stripped);
    }
}

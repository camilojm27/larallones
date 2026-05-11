<?php

namespace Tests\Unit;

use App\Support\IdentityDocumentNormalizer;
use PHPUnit\Framework\TestCase;

class IdentityDocumentNormalizerTest extends TestCase
{
    public function test_strips_punctuation_and_whitespace_and_uppercases(): void
    {
        $this->assertSame('1234567890', IdentityDocumentNormalizer::normalize('1.234.567.890'));
        $this->assertSame('1234567890', IdentityDocumentNormalizer::normalize('1-234-567-890'));
        $this->assertSame('1234567890', IdentityDocumentNormalizer::normalize(' 1 234 567 890 '));
        $this->assertSame('AB123456', IdentityDocumentNormalizer::normalize('ab-123 456'));
        $this->assertSame('1234567890', IdentityDocumentNormalizer::normalize('1234567890'));
    }
}

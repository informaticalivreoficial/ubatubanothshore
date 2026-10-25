<?php

namespace Tests\Unit;

use App\Support\LegalContent;
use Tests\TestCase;

class LegalContentTest extends TestCase
{
    public function test_formats_numbered_sections_and_removes_empty_paragraphs(): void
    {
        $result = LegalContent::format(
            '<p>1. <strong>Objeto</strong></p><p>Texto inicial.</p><p><br></p><p>2. <strong>Reservas</strong></p>'
        );

        $this->assertStringContainsString('<h2 id="secao-1-objeto"><span>1.</span>Objeto</h2>', $result['html']);
        $this->assertStringContainsString('<h2 id="secao-2-reservas"><span>2.</span>Reservas</h2>', $result['html']);
        $this->assertStringNotContainsString('<p><br></p>', $result['html']);
        $this->assertCount(2, $result['sections']);
        $this->assertSame('secao-1-objeto', $result['sections'][0]['id']);
    }

    public function test_creates_unique_ids_for_duplicate_sections(): void
    {
        $result = LegalContent::format(
            '<p>5. <strong>Responsabilidades</strong></p><p>5. <strong>Responsabilidades</strong></p>'
        );

        $this->assertSame('secao-5-responsabilidades', $result['sections'][0]['id']);
        $this->assertSame('secao-5-responsabilidades-2', $result['sections'][1]['id']);
    }

    public function test_adds_protocol_to_external_links(): void
    {
        $result = LegalContent::format('<p>Site: <a href="www.ubatubanorthshore.com.br">site</a></p>');

        $this->assertStringContainsString('href="https://www.ubatubanorthshore.com.br"', $result['html']);
    }
}

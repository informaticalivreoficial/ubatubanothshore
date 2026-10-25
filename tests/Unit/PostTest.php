<?php

namespace Tests\Unit;

use App\Models\Post;
use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_url_uses_article_route_by_default(): void
    {
        $post = new Post(['slug' => 'ilha-das-couves']);

        $this->assertSame(
            route('web.blog.artigo', ['slug' => 'ilha-das-couves']),
            $post->url
        );
    }

    public function test_url_uses_news_route_for_news_posts(): void
    {
        $post = new Post(['slug' => 'nova-loja', 'type' => 'noticia']);

        $this->assertSame(
            route('web.blog.noticia', ['slug' => 'nova-loja']),
            $post->url
        );
    }

    public function test_summary_prefers_excerpt_and_falls_back_to_plain_content(): void
    {
        $withExcerpt = new Post(['excerpt' => 'Resumo pronto', 'content' => '<p>Conteúdo completo</p>']);
        $withoutExcerpt = new Post(['content' => '<p>Conteúdo <strong>completo</strong></p>']);

        $this->assertSame('Resumo pronto', $withExcerpt->summary());
        $this->assertSame('Conteúdo completo', $withoutExcerpt->summary());
    }
}

<?php

use App\HtmlChunker;

test('example', function () {

    $html = <<<HTML
<h1>Hello</h1>
<p>World</p>
<h2>Section 1</h2>
<p>Paragraph 1</p>
<p>Paragraph 2</p>
<h2>Section 2</h2>
<p>Paragraph 3</p>
HTML;

    $chunks = HtmlChunker::chunk($html);

    $result = [
        '# Hello \n World',
        '# Hello \n ## Section 1 \n Paragraph 1',
        '# Hello \n ## Section 1 \n Paragraph 2',
        '# Hello \n ## Section 2 \n Paragraph 3',
    ];

    expect($chunks)->toEqual($result);
    
});

test('handles multiple heading levels', function () {
    $html = <<<HTML
<h1>Main Title</h1>
<h2>Section 1</h2>
<h3>Subsection 1.1</h3>
<p>Content 1</p>
<h3>Subsection 1.2</h3>
<p>Content 2</p>
<h2>Section 2</h2>
<p>Content 3</p>
HTML;

    $chunks = HtmlChunker::chunk($html);

    expect($chunks)->toContain('# Main Title \n ## Section 1 \n ### Subsection 1.1 \n Content 1');
    expect($chunks)->toContain('# Main Title \n ## Section 1 \n ### Subsection 1.2 \n Content 2');
    expect($chunks)->toContain('# Main Title \n ## Section 2 \n Content 3');
});

test('handles list elements', function () {
    $html = <<<HTML
<h1>List Example</h1>
<ul>
<li>Item 1</li>
<li>Item 2</li>
</ul>
<ol>
<li>Ordered 1</li>
<li>Ordered 2</li>
</ol>
HTML;

    $chunks = HtmlChunker::chunk($html);

    expect($chunks)->toContain('# List Example \n Item 1 Item 2');
    expect($chunks)->toContain('# List Example \n Ordered 1 Ordered 2');
});

test('removes formatting from content', function () {
    $html = <<<HTML
<h1>Formatted Content</h1>
<p>This is <strong>bold</strong> and <em>italic</em> text with <a href="#">links</a>.</p>
HTML;

    $chunks = HtmlChunker::chunk($html);

    expect($chunks)->toContain('# Formatted Content \n This is bold and italic text with links.');
});

test('ignores empty elements', function () {
    $html = <<<HTML
<h1>Title</h1>
<p></p>
<p>   </p>
<p>Valid content</p>
<p><strong></strong></p>
HTML;

    $chunks = HtmlChunker::chunk($html);

    expect($chunks)->toHaveCount(1);
    expect($chunks)->toContain('# Title \n Valid content');
});

<?php

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
        '# Hello \n ## Section 1 \n Paragraph 1 \n Paragraph 2',
        '# Hello \n ## Section 2 \n Paragraph 3',
    ];

    expect($chunks)->toEqual($result);
    
});

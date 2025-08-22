## PHP HTML Chunking

A tiny helper that turns HTML into small, context‑aware chunks. Each chunk carries its heading “trail” (like a breadcrumb) plus a single piece of content. That makes the text easier to search, embed, summarize, or stream to LLMs without losing where it came from.

### Why this exists

- **Preserve context**: Keep the section path (H1 → H2 → H3…) alongside each snippet.
- **Right‑sized pieces**: Split long pages into bite‑size, meaningful units.
- **Plain text**: Strip inline formatting; lists become markdown‑like text.

### How it works (at a glance)

- **Headings**: `h1`–`h6` build a running context. Missing levels are handled gracefully.
- **Content elements**: Currently chunks `p`, `ul`, `ol` elements.
- **Formatting**: Inline tags are removed; `ul`/`ol` become `- item` or `1. item` lines.
- **Output**: Each chunk is a single string with markdown‑style headings on top and the element’s content below.

### Quick example

```php
use App\HtmlChunker;

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

/* Result:
[
  "# Hello\nWorld",
  "# Hello\n## Section 1\nParagraph 1",
  "# Hello\n## Section 1\nParagraph 2",
  "# Hello\n## Section 2\nParagraph 3",
]
*/
```

### Lists example

```php
use App\HtmlChunker;

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

/* Result:
[
  "# List Example\n- Item 1\n- Item 2",
  "# List Example\n1. Ordered 1\n2. Ordered 2",
]
*/
```

### Minimal usage

```php
use App\HtmlChunker;

$chunks = HtmlChunker::chunk($htmlString);
// Each entry is a context-rich, newline-delimited string ready for storage or embeddings
```



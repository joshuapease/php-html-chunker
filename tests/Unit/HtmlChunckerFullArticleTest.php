<?php

use App\HtmlChunker;


test('real article', function () {
    $html = <<<'HTML'


  
            
<div class="mx-24 md:mx-40" data-component="_partials/blocks/markdown">
  <div class="max-w-736 mx-auto rich-text">
    <p>Testing doesn’t have to be formal, complicated, or overwhelming. At its core, it’s just about making sure the thing you built works - and continues to work as things change. That could mean checking if a string is turned into a slug correctly, making sure a contact form submits, or confirming that your homepage layout didn’t fall apart after a plugin update.</p>
<hr>
<span id="why-testing-matters"></span><h2>Why Testing Matters <a class="anchor" href="#why-testing-matters" title="Direct link to Why Testing Matters" aria-label="Direct link to Why Testing Matters">#</a></h2>
<p>Testing helps us:</p>
<ul>
<li><strong>Catch bugs before users do</strong> – Avoid regressions that slip in from code or plugin changes.</li>
<li><strong>Document what’s supposed to happen</strong> – Tests describe expected behavior and make it easier to revisit code later.</li>
<li><strong>Ship faster</strong> – Cut down on manual QA time and gain confidence in your PRs.</li>
<li><strong>Refactor safely</strong> – Clean up code without fear of unintended side effects.</li>
<li><strong>Feel more confident</strong> – Testing reduces guesswork and anxiety when making changes.</li>
</ul>
<p>Bottom line: testing isn’t about writing more code — it’s about protecting the code you already have and making future changes less risky.</p>
<hr>
<span id="what-testing-really-means"></span><h2>What Testing Really Means <a class="anchor" href="#what-testing-really-means" title="Direct link to What Testing Really Means" aria-label="Direct link to What Testing Really Means">#</a></h2>
<p>Testing isn’t just one thing – there are different types, each checking a different level of your app:</p>
<span id="unit-tests"></span><h3>🔹 Unit Tests <a class="anchor" href="#unit-tests" title="Direct link to 🔹 Unit Tests" aria-label="Direct link to 🔹 Unit Tests">#</a></h3>


<p>Small, focused tests for a specific function or method. Fast, simple, and great for services or helpers.</p>
<pre><code class="language-php">$this-&gt;assertEquals(
    'cafe-racer', 
    SlugHelper::normalize('Café Racer')
);
</code></pre>
<span id="functional-tests"></span><h3>🔹 Functional Tests <a class="anchor" href="#functional-tests" title="Direct link to 🔹 Functional Tests" aria-label="Direct link to 🔹 Functional Tests">#</a></h3>
<p>Check how a feature behaves – like a form submission or controller logic.</p>
<pre><code class="language-php">$I-&gt;amOnPage('/contact');
$I-&gt;submitForm('#contact-form', [...]);
$I-&gt;see('Thank you');
</code></pre>
<span id="acceptance-tests"></span><h3>🔹 Acceptance Tests <a class="anchor" href="#acceptance-tests" title="Direct link to 🔹 Acceptance Tests" aria-label="Direct link to 🔹 Acceptance Tests">#</a></h3>
<p>Simulate a user flow in a real browser (e.g. login, create entry). These tests are great for workflows that span multiple parts of the system.</p>
<pre><code class="language-php">$I-&gt;amOnPage('/admin');
$I-&gt;fillField('Username', 'admin');
$I-&gt;click('Login');
$I-&gt;see('Dashboard');
</code></pre>
<span id="visual-regression-tests"></span><h3>🔹 Visual Regression Tests <a class="anchor" href="#visual-regression-tests" title="Direct link to 🔹 Visual Regression Tests" aria-label="Direct link to 🔹 Visual Regression Tests">#</a></h3>
<p>Use BackstopJS to compare screenshots of your site over time. If a layout shifts or an element disappears, it’ll flag it.</p>
<pre><code class="language-js">{
  label: "Homepage",
  url: "http://project.ddev.site/",
  selectors: ["document"],
  viewports: [{ width: 1280, height: 800 }]
}
</code></pre>

  </div>
</div>

          
            <div class="mx-24 md:mx-40" data-component="_partials/blocks/markdown">
  <div class="max-w-736 mx-auto rich-text">
    <hr>
<span id="tools-we-use"></span><h2>Tools We Use <a class="anchor" href="#tools-we-use" title="Direct link to Tools We Use" aria-label="Direct link to Tools We Use">#</a></h2>
<ul>
<li><strong><a href="https://codeception.com/">Codeception</a></strong> – For unit, functional, and acceptance testing. Works well with Craft and Yii2. Uses PHPUnit under the hood.</li>
<li><strong><a href="https://garris.github.io/BackstopJS/">BackstopJS</a></strong> – For catching CSS and layout regressions with screenshots.</li>
<li><strong><a href="https://ddev.com/">DDEV</a></strong> – Docker-based environment to run all your tests reliably and consistently across the team.</li>
</ul>
<hr>
<span id="what-you-should-test-on-every-craft-project"></span><h2>What You Should Test on <em>Every</em> Craft Project <a class="anchor" href="#what-you-should-test-on-every-craft-project" title="Direct link to What You Should Test on Every Craft Project" aria-label="Direct link to What You Should Test on Every Craft Project">#</a></h2>
<p>You don’t need full coverage. But if you’re looking for where to start, this is a strong baseline:</p>
<ul>
<li>✅ A <strong>unit test</strong> for a helper (slug generator, price formatter, etc.)</li>
<li>✅ A <strong>functional test</strong> for at least one form (contact, newsletter, etc.)</li>
<li>✅ An <strong>acceptance test</strong> for login flow or critical path</li>
<li>✅ A <strong>BackstopJS test</strong> of your homepage and a key interior template</li>
<li>✅ A <strong>404 page test</strong> – easy to set up and protects user experience</li>
</ul>
<p>These are small to set up and pay off quickly — especially during Craft updates, plugin upgrades, or template refactors.</p>
<hr>
<span id="ddev-makes-testing-easy"></span><h2>DDEV Makes Testing Easy <a class="anchor" href="#ddev-makes-testing-easy" title="Direct link to DDEV Makes Testing Easy" aria-label="Direct link to DDEV Makes Testing Easy">#</a></h2>
<p>With DDEV, you can run everything locally with ease. We have a custom command setup to make it simple to run our tests here at Viget.</p>
<p><code>.ddev/commands/web/codecept.sh</code></p>
<pre><code class="language-bash">## Usage: codecept
## Description: Run codeception for this project
vendor/bin/codecept "$@"
</code></pre>
<p>Which can be run like this:</p>
<pre><code class="language-bash"># Run PHP tests
$ ddev codecept run
</code></pre>
<p>Backstop can be run within DDEV with a little work, but we typically run it globally on our system:</p>
<pre><code class="language-bash"># Run BackstopJS visual tests
$ backstop test
</code></pre>
<p>You can also run these in CI – and seeing tests pass or fail <em>before</em> you merge code is a huge boost in confidence.</p>
<hr>
<span id="tips-to-keep-your-tests-useful"></span><h2>Tips to Keep Your Tests Useful <a class="anchor" href="#tips-to-keep-your-tests-useful" title="Direct link to Tips to Keep Your Tests Useful" aria-label="Direct link to Tips to Keep Your Tests Useful">#</a></h2>
<ul>
<li>Use clean, repeatable test data (fixtures or factories)</li>
<li>Keep tests isolated – don’t rely on one test running before another</li>
<li>Enable visual output for failures (screenshots help a lot)</li>
<li>Focus on the fragile or critical features – not everything</li>
<li>Add tests to your pull request workflow when possible</li>
</ul>
<p>This is less about perfection, and more about progress. A few well-placed tests will help your project stay stable as it grows.</p>
<hr>
<span id="final-thoughts"></span><h2>Final Thoughts <a class="anchor" href="#final-thoughts" title="Direct link to Final Thoughts" aria-label="Direct link to Final Thoughts">#</a></h2>
<p>You don’t need a huge test suite to get value from testing. Just a few smart tests can save you time and give you confidence in your code.</p>
<p>Testing isn’t about slowing down development. It’s what allows us to move faster without sacrificing stability.</p>
<span id="get-started"></span><h3>Get Started <a class="anchor" href="#get-started" title="Direct link to Get Started" aria-label="Direct link to Get Started">#</a></h3>
<p>If you’re looking to get started testing Craft CMS now, I’ve compiled a few resources that might be a good next step for you:</p>
<p><a href="https://github.com/craftcms/cms/tree/develop/tests">Craft CMS GitHub Repo (Craft Test Suite)</a>
<em>Reference real-world tests and configuration from the core CMS team.</em></p>
<p><a href="https://craftquest.io/courses/testing-with-codeception">CraftQuest “Testing with Codeception” course</a>
<em>Paid (but extremely valuable) video series on installing and configuring Codeception in Craft environments.</em></p>
<p><a href="https://laracasts.com/series/phpunit-testing-in-laravel">PHPUnit Testing in Laravel</a> 
<em>While not Craft-specific, I also highly recommend this series on Laracasts. It’s a great deep dive into writing and refining good tests in PHP.</em></p>
<p><a href="https://www.codurance.com/publications/2020/01/16/backstopjs-tutorial">Getting Started With BackstopJS</a>
<em>Clean walkthrough of setup with Docker, CI integration, and best practices to avoid flaky tests.</em></p>
<hr>
<span id="further-reading"></span><h3>Further Reading <a class="anchor" href="#further-reading" title="Direct link to Further Reading" aria-label="Direct link to Further Reading">#</a></h3>
<p>The Craft 5 docs are notably missing a testing page. There’s an internal debate whether or not to switch testing providers from Codeception to <a href="https://pestphp.com/">Pest</a>. While that debate plays out, I recommend taking a look at the <a href="https://craftcms.com/docs/4.x/testing/">Craft 4 Testing</a> docs for reference while getting testing setup.</p>

  </div>
</div>

HTML;

    $chunks = HtmlChunker::chunk($html);

    $expected = [
        'Testing doesn’t have to be formal, complicated, or overwhelming. At its core, it’s just about making sure the thing you built works - and continues to work as things change. That could mean checking if a string is turned into a slug correctly, making sure a contact form submits, or confirming that your homepage layout didn’t fall apart after a plugin update.',
        implode('\n',[
            '## Why Testing Matters #',
            'Testing helps us:',
        ]),
        implode('\n',[
            '## Why Testing Matters #',
            '- Catch bugs before users do – Avoid regressions that slip in from code or plugin changes.',
            '- Document what’s supposed to happen – Tests describe expected behavior and make it easier to revisit code later.',
            '- Ship faster – Cut down on manual QA time and gain confidence in your PRs.',
            '- Refactor safely – Clean up code without fear of unintended side effects.',
            '- Feel more confident – Testing reduces guesswork and anxiety when making changes.',
        ]),
        implode('\n', [
            '## Why Testing Matters #',
            'Bottom line: testing isn’t about writing more code — it’s about protecting the code you already have and making future changes less risky.'
        ]),
        implode('\n', [
            '## What Testing Really Means #',
            'Testing isn’t just one thing – there are different types, each checking a different level of your app:',
        ]),
        implode('\n', [
            '## What Testing Really Means #',
            '### 🔹 Unit Tests #',
            'Small, focused tests for a specific function or method. Fast, simple, and great for services or helpers.',
        ]),
        implode('\n', [
            '## What Testing Really Means #',
            '### 🔹 Functional Tests #',
            'Check how a feature behaves – like a form submission or controller logic.',
        ]),
        implode('\n', [
            '## What Testing Really Means #',
            '### 🔹 Acceptance Tests #',
            'Simulate a user flow in a real browser (e.g. login, create entry). These tests are great for workflows that span multiple parts of the system.',
        ]),
        implode('\n', [
            '## What Testing Really Means #',
            '### 🔹 Visual Regression Tests #',
            'Use BackstopJS to compare screenshots of your site over time. If a layout shifts or an element disappears, it’ll flag it.',
        ]),
        implode('\n', [
            '## Tools We Use #',
            '- Codeception – For unit, functional, and acceptance testing. Works well with Craft and Yii2. Uses PHPUnit under the hood.',
            '- BackstopJS – For catching CSS and layout regressions with screenshots.',
            '- DDEV – Docker-based environment to run all your tests reliably and consistently across the team.',
        ]),
        implode('\n', [
            '## What You Should Test on Every Craft Project #',
            'You don’t need full coverage. But if you’re looking for where to start, this is a strong baseline:',
        ]),
        implode('\n', [
            '## What You Should Test on Every Craft Project #',
            '- ✅ A unit test for a helper (slug generator, price formatter, etc.)',
            '- ✅ A functional test for at least one form (contact, newsletter, etc.)',
            '- ✅ An acceptance test for login flow or critical path',
            '- ✅ A BackstopJS test of your homepage and a key interior template',
            '- ✅ A 404 page test – easy to set up and protects user experience',
        ]),
        implode('\n', [
            '## What You Should Test on Every Craft Project #',
            'These are small to set up and pay off quickly — especially during Craft updates, plugin upgrades, or template refactors.',
        ]),
        implode('\n', [
            '## DDEV Makes Testing Easy #',
            'With DDEV, you can run everything locally with ease. We have a custom command setup to make it simple to run our tests here at Viget.',
        ]),
        implode('\n', [
            '## DDEV Makes Testing Easy #',
            '.ddev/commands/web/codecept.sh',
        ]),
        implode('\n', [
            '## DDEV Makes Testing Easy #',
            'Which can be run like this:',
        ]),
        implode('\n', [
            '## DDEV Makes Testing Easy #',
            'Backstop can be run within DDEV with a little work, but we typically run it globally on our system:',
        ]),
        implode('\n', [
            '## DDEV Makes Testing Easy #',
            'You can also run these in CI – and seeing tests pass or fail before you merge code is a huge boost in confidence.',
        ]),
        implode('\n', [
            '## Tips to Keep Your Tests Useful #',
            '- Use clean, repeatable test data (fixtures or factories)',
            '- Keep tests isolated – don’t rely on one test running before another',
            '- Enable visual output for failures (screenshots help a lot)',
            '- Focus on the fragile or critical features – not everything',
            '- Add tests to your pull request workflow when possible',
        ]),
        implode('\n', [
            '## Tips to Keep Your Tests Useful #',
            'This is less about perfection, and more about progress. A few well-placed tests will help your project stay stable as it grows.',
        ]),
        implode('\n', [
            '## Final Thoughts #',
            'You don’t need a huge test suite to get value from testing. Just a few smart tests can save you time and give you confidence in your code.',
        ]),
        implode('\n', [
            '## Final Thoughts #',
            'Testing isn’t about slowing down development. It’s what allows us to move faster without sacrificing stability.',
        ]),
        implode('\n', [
            '## Final Thoughts #',
            '### Get Started #',
            'If you’re looking to get started testing Craft CMS now, I’ve compiled a few resources that might be a good next step for you:',
        ]),
        implode('\n', [
            '## Final Thoughts #',
            '### Get Started #',
            'Craft CMS GitHub Repo (Craft Test Suite) Reference real-world tests and configuration from the core CMS team.',
        ]),
        implode('\n', [
            '## Final Thoughts #',
            '### Get Started #',
            'CraftQuest “Testing with Codeception” course Paid (but extremely valuable) video series on installing and configuring Codeception in Craft environments.',
        ]),
        implode('\n', [
            '## Final Thoughts #',
            '### Get Started #',
            'PHPUnit Testing in Laravel While not Craft-specific, I also highly recommend this series on Laracasts. It’s a great deep dive into writing and refining good tests in PHP.',
        ]),
        implode('\n', [
            '## Final Thoughts #',
            '### Get Started #',
            'Getting Started With BackstopJS Clean walkthrough of setup with Docker, CI integration, and best practices to avoid flaky tests.',
        ]),
        implode('\n', [
            '## Final Thoughts #',
            '### Further Reading #',
            'The Craft 5 docs are notably missing a testing page. There’s an internal debate whether or not to switch testing providers from Codeception to Pest. While that debate plays out, I recommend taking a look at the Craft 4 Testing docs for reference while getting testing setup.',
        ]),
    ];  

    expect($chunks)->toEqual($expected);
});
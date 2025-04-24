<?php

use App\Services\HtmlToMarkdownService;
use App\Services\ThreadManagementService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Claude\Claude3Api\Client as ClaudeClient;
use Claude\Claude3Api\Config as ClaudeConfig;
use Claude\Claude3Api\Models\Message;
use Claude\Claude3Api\Models\Content\TextContent;
use Claude\Claude3Api\Requests\MessageRequest;
use Claude\Claude3Api\Models\Content\ImageContent;

Schedule::command('telescope:prune --hours=48')->daily();


Artisan::command('test:claude-analyze {jobUrl?}', function () {

    if(!$this->argument('jobUrl')) {
        $this->error('Please provide a job URL');
        return;
    }
    $jobUrl = $this->argument('jobUrl');
//    $jobUrl = 'https://www.google.com/about/careers/applications/jobs/results/84398062939579078-senior-staff-partner-solutions-architect-google-cloud?location=Miami+UnitedStates&src=Online/Job+Board/glassdoor';

    // fetch html from google job
    $options = [
        'verify' => false,
        'proxy' => 'http://127.0.0.1:10001',
    ];
    $headers = [
        'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:137.0) Gecko/20100101 Firefox/137.0',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.5',
        'Referer' => 'https://www.google.com/about/careers/applications/jobs/results/',
        'Cookie' => 'AEC=AVcja2flegBy2c8uJKLeWbCfQ6obs-cXIIb0KWLIfedfv5gLSuBbYEKbjew; NID=523=YT0Ll6nimqXKNqW4GdwsFIhsqQcM4j5mWK86ns21LGdhIA58PkdDbiaKXEep15V9oRoD5kPnuguqNSVouRfwU5tv4df3txNy1nytH4lNx4OD2FJnBU0MNrJVXm8E0KVaLS1qslTUcYlnXY0Pz4cxOdc1J1TH_k8LrAwWFFu3KeKoO3Gq4ysOiLWHpmsFHAroqdTYThkZTE9BsQd33FH9F238xpy1Cq0tDSi81-PVDVsKGrYgvbTX9f0GJ7cAu3n52klb8hoB7sqvdDusWDDQWnAilaqV-GPlmaHEvTC47z7GJqglAER396K84cvudXbFIxvy7DyVGuEhDWp8ZqNl2uY3vvvAAFdOJnv8SDh6qQcPmtgVBARuHvHOg3S4970rsZz_xdUF9YFY6SzydG_YI2dpUNyBA4Rr71ivDGeD5oy0w2p7VF2VjHoP4FxQWmkpSOpIjMq7P-OcyisN6SX4J02wpzOlohjIkigEbPeVW0QfcLQGKlQzrnSgT6fuY4N7RdfMKcP70gfpVj0bYT8o9sqnzI_8GyfAIs2SVR-D5qi0ZraVNcdHNYV9dcdJ5fyE-1Yb98LjcHHcT9PjCeP-Kfb6gi-fDXriqjt22a7txDTXNOTXMpU-F6pdgH8nMmlijQKcnytQlfJkXSLdUM5lyOIKwa0m8vAfTDG-esby0G93_vV4kqVrgE_2e3KDww1pxqb-36bxTLLdMjFnKxs6XoxDztk5hBpI0xRUk5o; SID=g.a000vgixh16fo-DawRjxA1ERLvL_qmG2Bf2CxPRQLzsAn7arutwoUalpGLfUFQHto5-hOXYthgACgYKAaASARMSFQHGX2MifYnqg1PmljD-_aAa5pIgUBoVAUF8yKowICpgcjnBE0Zp9pqYvd2i0076; __Secure-1PSID=g.a000vgixh16fo-DawRjxA1ERLvL_qmG2Bf2CxPRQLzsAn7arutwozOJVJNQQ6rQe530aa5rbOwACgYKAT4SARMSFQHGX2Mii8NtzM51CSdnNbu-9G8BKBoVAUF8yKpNIO3d9Wi3NZlryJItuqe-0076; __Secure-3PSID=g.a000vgixh16fo-DawRjxA1ERLvL_qmG2Bf2CxPRQLzsAn7arutwoTwS58CkQF0vFuts9UlyhFwACgYKAcASARMSFQHGX2MiP8Ll13zL-W24qeGwBzPRTRoVAUF8yKrSAvw5v9cCqyi7YDSxyBp20076; HSID=AGJwcprdwNv3J9Wu9; SSID=AX35EfxYohjRuR1ze; APISID=lCol4wHBBR7JvFDP/ACYXPhT15SEV2vYrj; SAPISID=7y5ybgXL9oFp9Efo/A_DpWH_tQ07OXR1U-; __Secure-1PAPISID=7y5ybgXL9oFp9Efo/A_DpWH_tQ07OXR1U-; __Secure-3PAPISID=7y5ybgXL9oFp9Efo/A_DpWH_tQ07OXR1U-; SIDCC=AKEyXzVmwvaP24Fs5Bw4M8ldPbH8rlOniAFXxBtaR5zIRZi2cIuPLYivJNJBKjH-fvGNgaLz; __Secure-1PSIDCC=AKEyXzW_BkviToIkEYamQ_10SyNtK1VrMOtCWU7KBDzCqY9KdGnw0IpCpmmlw4U7g209VCEp; __Secure-3PSIDCC=AKEyXzXZWNvxe1p7Pg6rA4Lre4c2q_z6rE9XEnd_VVV57ZDEJKEJY9-pzca5epopVjbNsOeP',
        'Upgrade-Insecure-Requests' => '1',
        'Sec-Fetch-Dest' => 'document',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'none',
        'Sec-Fetch-User' => '?1',
        'Priority' => 'u=0, i'
    ];
    $resp = \Illuminate\Support\Facades\Http::withOptions($options)
        ->withHeaders($headers)
        ->get($jobUrl)
        ->throw();
    $htmlContent = $resp->body();

    $findGoogleDiv = function ($html) {

        $dom = new DOMDocument();

        @$dom->loadHTML($html);

        $xpath = new DOMXPath($dom);

        $elements = $xpath->query('//div[@data-title]');

        $foundParentForTitle = null;
        $foundElement = null;

        if ($elements->length > 0) {
            // Loop through all found elements
            foreach ($elements as $element) {
                // Check if this element has any children
                if ($element->hasChildNodes()) {
                    // Get the first child element (skipping text nodes)
                    $firstChild = $element->firstChild;

                    // Skip text nodes and comments until we find the first element
                    while ($firstChild !== null && !($firstChild instanceof DOMElement)) {
                        $firstChild = $firstChild->nextSibling;
                    }

                    // Check if the first element child is an H1
                    if ($firstChild !== null && strtolower($firstChild->nodeName) == 'h1') {
                        $foundParentForTitle = $element;

                        // get the first element that is a <main> element of the parent of the H1
                        $mainElements = $xpath->query('.//main', $firstChild->parentNode);
                        if ($mainElements->length > 0) {
                            $foundElement = $mainElements->item(0);
                            $this->line('Found <main> element');
                            break; // Stop after finding the first match
                        } else {
                            throw new Exception("No <main> element found");
                        }

                    }
                }
            }

            if ($foundElement !== null) {
                // Get the outer HTML of the element
                $outerHTML = $dom->saveHTML($foundElement);
                $dataTitle = $foundParentForTitle->getAttribute('data-title');

                $parts = [
                    'html' => $outerHTML,
                    'text' => $foundElement->textContent,
                    'title' => $dataTitle,
                    'company' => 'Google'
                ];

                if (\Illuminate\Support\Str::contains($dataTitle, ', Google')) {
                    $parts['title'] = \Illuminate\Support\Str::before($dataTitle, ', Google');
                    $parts['company'] = 'Google' . \Illuminate\Support\Str::after($dataTitle, ', Google');
                }
                return $parts;
            } else {
                throw new Exception("No div with data-title and H1 as first child found");
            }
        } else {
            throw new Exception("No elements with data-title attribute found");
        }
    };
    $parts = $findGoogleDiv($htmlContent);

    $htmlContent = $parts['html'];
    $jobTitle = $parts['title'];
    $jobCompany = $parts['company'];

    // convert to md
    $markdownContent = app(HtmlToMarkdownService::class)->convert($htmlContent, true, $jobUrl);
    // post-cleaning on md
    $markdownContent = preg_replace('/\s*share\n/', '', $markdownContent); // replace "share" with empty string
    $markdownContent = preg_replace('/\s*linkCopy linkemailEmail a friend\n/', '', $markdownContent); // replace "share" with empty string
    $markdownContent = preg_replace('/\s*info_outline\n/', '', $markdownContent); // replace "share" with empty string


    // get user skills breakdown
    $user = App\Models\User::find(1);
    $threadService = app(ThreadManagementService::class);
    // $skillsBreakdown = $threadService->formatSkillsBreakdown($user, $withAllDetails = true, $withScale = false);
    $skillsBreakdown = $threadService->formatSkillsSimple($user);

    // load from model prompt and build it out with vars
    $prompt = \App\Models\OpenAIPrompt::where('name', 'job_post_analysis_claude')->first();
    $promptVariables = [
        'job_content' => $markdownContent,
        'company' => $jobCompany,
        'target_role' => $jobTitle,
        'my_skills' => $skillsBreakdown,
    ];
    $promptText = app(\App\Services\JobPostAIService::class)->replacePlaceholders($prompt->prompt_template, $promptVariables);

    // build post data
    $postData = [
        'model' => 'claude-3-7-sonnet-20250219',
        'max_tokens' => 128000,
        'temperature' => 1,
        'system' => $prompt->system_message,
        'messages' => [
            [
                'role' => 'user',
                'content' => []
            ]
        ],
        'thinking' => [
            'type' => 'enabled',
            'budget_tokens' => 64000
        ],
    ];

    // build user messages array
    if (!empty($prompt->examples_message)) {
        $postData['messages'][0]['content'][] = [
            'type' => 'text',
            'text' => $prompt->examples_message,
            'cache_control' => [
                'type' => 'ephemeral',
            ]
        ];
    }
    $postData['messages'][0]['content'][] = [
        'type' => 'text',
        'text' => $promptText,
    ];


    $claudeUrl = 'https://api.anthropic.com/v1/messages';

//    // Do it in batches? so its async
//    $requestId = "{$prompt->type}:{$prompt->id}" . \Illuminate\Support\Str::slug($prompt->name) . ":" . \Illuminate\Support\Str::random(8);
//    $claudeUrl = 'https://api.anthropic.com/v1/messages/batches';
//    $postData = [
//        'requests' => [
//            [
//                'custom_id' => $requestId,
//                'params' => $postData
//            ]
//        ]
//    ];
    // POST request to Claude
    $startedAt = microtime(true);
    $claudeResp = \Illuminate\Support\Facades\Http::withOptions($options)
        ->withHeaders(config('services.anthropic.headers'))
        ->timeout(300)
        ->post($claudeUrl, $postData)
        ->throw()
        ->json();

//    $batchId = $claudeResp['batch_id'];


    // Handle the final response
    $endedAt = microtime(true);
    $elapsedTimeSecs = $endedAt - $startedAt;
    $elapsedTimeSecs = round($elapsedTimeSecs);



    $this->warn('Claude response time: ' . $elapsedTimeSecs . ' seconds');
    $tokenStats = $claudeResp['usage'];

// Store in prompt history
    $historyItem = \App\Models\PromptHistory::create([
        'prompt_id' => $prompt->id,
        'user_id' => $user->id,
        'type' => $prompt->type,
        'name' => $prompt->name,
        'status' => 'completed',
        'src_class' => 'console.php',
        'src_function' => 'php artisan test:claude-analyze',
        'src_stack' => \Illuminate\Support\Str::after(debug_backtrace()[1]['function'], 'App\Console\Kernel->call()'),
        // 'src_data' => json_encode($postData),
        'tokens_used' => $tokenStats['input_tokens'] + $tokenStats['output_tokens'],
        'elapsed_time' => $elapsedTimeSecs,
        'api_response' => $claudeResp,
        'model_config' => Arr::except($postData, ['messages', 'system']),
        'system_message' => $prompt->system_message,
        'user_messages' => $postData['messages'],
    ]);


    $analyzedJobJson = trim(collect($claudeResp['content'])->where('type', 'text')->join("\n\n"));
    $thinkingText = trim(collect($claudeResp['content'])->where('type', 'thinking')->join("\n\n"));
    Log::debug('Claude JSON Response', [
        'response' => $analyzedJobJson,
        'thinking' => $thinkingText,
        'stats' => $tokenStats
    ]);

    // convert respopnse to json if thats all it is
    if(!\Illuminate\Support\Str::startsWith($analyzedJobJson, '```json')) {
        throw new Exception('Invalid response format. Expected JSON.');
    }
    $analyzedJobJson = \Illuminate\Support\Str::after($analyzedJobJson, '```json');
    $analyzedJobJson = \Illuminate\Support\Str::before($analyzedJobJson, '```');
    $analyzedJobJson = json_decode($analyzedJobJson, true);
    if (!$analyzedJobJson || json_last_error() !== JSON_ERROR_NONE) {
        Log::error('Invalid JSON response from Claude', [
            'response' => $analyzedJobJson,
            'error' => json_last_error_msg()
        ]);

        throw new Exception('Failed to parse Claude response: ' . json_last_error_msg());
    }


//
//    $tokenStats = $claudeResp['usage'];
//
//    Log::debug('Claude JSON Response', [
//        'response' => $analyzedJobJson,
//        'stats' => $tokenStats
//    ]);


});

Artisan::command('test:cover-letter', function() {

});

Artisan::command('with-sdk', function(){

    // use SDK
    // Create a configuration object with your API key
    $config = new ClaudeConfig(
        config('services.anthropic.api_key'),
        ClaudeConfig::DEEPSEEK_API_VERSION,
        ClaudeConfig::DEFAULT_BASE_URL,
        config('services.anthropic.model', 'claude-3-7-sonnet-20250219'),
        config('services.anthropic.max_tokens', 128000),
        ClaudeConfig::DEFAULT_AUTH_TYPE,
        ClaudeConfig::DEFAULT_MESSAGE_PATH,
        ['output-128k-2025-02-19' => true]
    );
    $client = new ClaudeClient($config);
    // modify client with reflection to set proxy on underlying httpclient which is guzzle...
    $reflection = new \ReflectionClass($client);
    $property = $reflection->getProperty('httpClient');
    $property->setAccessible(true);
    /** @var \GuzzleHttp\Client $httpClient */
    $httpClient = $property->getValue($client);
    $reflection2 = new \ReflectionClass($httpClient);
    $property2 = $reflection2->getProperty('config');
    $property2->setAccessible(true);
    $httpConfig = $property2->getValue($httpClient);
    // set proxy
    $property2->setValue($httpClient, \Illuminate\Support\Arr::set($httpConfig, 'proxy', 'http://127.0.0.1:10001'));
    $property2->setValue($httpClient, \Illuminate\Support\Arr::set($httpConfig, 'verify', false));


    // Create a message request
    $messageRequest = new MessageRequest();
    $messageRequest->setTemperature(1);
    $messageRequest->setMaxTokens(128000);
    $messageRequest->setModel('claude-3-7-sonnet-20250219');
//    $messageRequest->setMetadata()

    // Set system message with cacheable content
    $systemMessage = new Message('system', [
        // Regular system instruction
        // new TextContent("You are an AI assistant tasked with analyzing literary works."),
        new TextContent($prompt->system_message),
        // Large text to be cached (e.g., an entire book)
        // TextContent::withEphemeralCache("<the entire contents of Pride and Prejudice>")
        TextContent::withEphemeralCache($prompt->examples_message),
    ]);
    // Add the system message properly
    $messageRequest->addSystemMessage($systemMessage);

    // Add a user message
//    if (!empty($prompt->examples_message)) {
//        $messageRequest->addMessage(new Message('user', [
//            TextContent::withEphemeralCache($prompt->examples_message)
//        ]));
//    }
    $messageRequest->addMessage(new Message('user', [
        new TextContent($promptText)
    ]));

    $analyzedJobJson = "";
    $tokenStats = [
        'input_tokens' => 0,
        'output_tokens' => 0,
        'cache_creation_input_tokens' => 0,
        'cache_read_input_tokens' => 0,
        'used_cache' => false,
        'created_cache' => false,
    ];
    $startedAt = microtime(true);
    $client->streamMessage($messageRequest, function ($chunk) use (&$analyzedJobJson, &$tokenStats) {
        if ($chunk instanceof \Claude\Claude3Api\Responses\MessageResponse) {
            // Handle complete message response
            $response = $chunk;
            $tokenStats['input_tokens'] = $response->getInputTokens();
            $tokenStats['output_tokens'] = $response->getOutputTokens();
            $tokenStats['cache_creation_input_tokens'] = $response->getCacheCreationInputTokens();
            $tokenStats['cache_read_input_tokens'] = $response->getCacheReadInputTokens();
            $tokenStats['used_cache'] = $response->usedCache();
            $tokenStats['created_cache'] = $response->createdCache();

            Log::debug('Claude Response', [
                'response' => $response->getContent(),
                'stats' => $tokenStats
            ]);

//            echo "Claude's response: " . $response->getContent()[0]['text'];
        } elseif (is_array($chunk)) {
            if(isset($chunk['delta']['text'])) {
                if(\Illuminate\Support\Str::endsWith($chunk['delta']['text'], "\n")) {
                    echo "[claude] >\t";
                }
                $analyzedJobJson .= $chunk['delta']['text'];
            } else {
                // Handle other types of chunks (e.g., metadata)
                Log::debug('Claude Chunk', [
                    'chunk' => $chunk
                ]);
            }
        }
    });
});

Artisan::command('htmltomd', function () {
//    $baseUrl = 'https://www.kickresume.com/edit/14195435/analyze/';
//    $html = <<<'HTML'
//<div class="modal-content"><div class="AnalyticsDetailModal-module__section___s8Dj0tDoST"><div class="AnalyticsDetailModal-module__title-wrapper___qoJjxNoKZP"><svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="AnalyticsDetailModal-module__icon___yZqt8Aade5"><path d="M8 .533C3.886.533.533 3.886.533 8c0 4.114 3.353 7.467 7.467 7.467 4.114 0 7.467-3.353 7.467-7.467C15.467 3.886 12.114.533 8 .533Zm0 1.6A5.855 5.855 0 0 1 13.867 8 5.855 5.855 0 0 1 8 13.867 5.855 5.855 0 0 1 2.133 8 5.855 5.855 0 0 1 8 2.133Z"></path><path d="M8 4.533a.8.8 0 0 0-.8.801V8a.8.8 0 0 0 .8.8.8.8 0 0 0 .8-.8V5.334a.8.8 0 0 0-.8-.8Zm0 5.334a.8.8 0 0 0-.8.799.8.8 0 0 0 .8.8h.006a.8.8 0 0 0 .8-.8.8.8 0 0 0-.8-.799Z"></path></svg><h4>Oh no! Your skills section may need improvement.</h4><button class="btn btn-square btn-light CloseButton-module__absolute___aGAj002Mtj CloseButton-module__close-btn___6Ya56QG6fg"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="CloseButton-module__close-icon___72Q-fQk34r"><path d="M17.535 5.299a.888.888 0 00-.611.26l-4.979 4.978-4.869-4.869a.888.888 0 00-.002 0 .888.888 0 00-.642-.26.888.888 0 00-.612.26.888.888 0 000 1.256l4.87 4.869-5.286 5.283a.888.888 0 000 1.256.888.888 0 001.256 0l5.285-5.285 5.395 5.394a.888.888 0 001.256 0 .888.888 0 000-1.255L13.2 11.79l4.979-4.979a.888.888 0 000-1.253.888.888 0 00-.645-.26z"></path></svg></button></div><div class="AnalyticsDetailModal-module__description___ih-q-DeW1V">Your skills section is either missing or it's nearly empty. In other words, your resume is missing one of its most important components. By adding more relevant skills to your resume, you will hugely increase your chances of getting invited to job interviews.</div></div><div class="AnalyticsDetailModal-module__section___s8Dj0tDoST AnalyticsDetailModal-module__suggestion-section___jbO6ys80w2"><h6 class="AnalyticsDetailModal-module__suggestion-main-title___ymlT-JKJUz"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15" class="AnalyticsDetailModal-module__suggestion-title-icon___7SkZ108AyU"><path d="M7.5 0a7.5 7.5 0 100 15 7.5 7.5 0 000-15zm0 1.426a6.075 6.075 0 11-.002 12.15A6.075 6.075 0 017.5 1.425zm-.001 2.647a.713.713 0 000 1.426h.007a.713.713 0 000-1.426zm0 2.714a.713.713 0 00-.713.713v2.716a.713.713 0 001.425 0V7.5a.713.713 0 00-.712-.713z"></path></svg>How to improve</h6><div class="AnalyticsDetailModal-module__suggestion___RyhUOZZFBZ"><div class="AnalyticsDetailModal-module__suggestion-title___k1dUcFP39G">1. Include skills from the job post.</div><div class="AnalyticsDetailModal-module__description___ih-q-DeW1V">Take a look at the job post you're replying to and identify the most important skills mentioned there. Which ones do you have? Make sure to mention as many of those skills in your skills section. This will help you pass any ATS checks as well as get on the good side of most recruiters.</div></div><div class="AnalyticsDetailModal-module__suggestion___RyhUOZZFBZ"><div class="AnalyticsDetailModal-module__suggestion-title___k1dUcFP39G">2. Prioritize hard skills.</div><div class="AnalyticsDetailModal-module__description___ih-q-DeW1V">In your skills section, you should prioritize hard skills over soft skills. But what are hard skills? As a rule of thumb, hard skills are the skills that can be learned and taught, such as foreign languages or technical abilities.
//
//Soft skills, or strengths, have more to do with your personality and background. While both can be equally important for a job, consider giving your soft skills a separate section on your resume.</div></div></div><div class="AnalyticsDetailModal-module__section___s8Dj0tDoST AnalyticsDetailModal-module__pro-suggestion-section___tBUlhJoUGn"><div class="AnalyticsDetailModal-module__title-wrapper___qoJjxNoKZP"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="AnalyticsDetailModal-module__icon___yZqt8Aade5"><path d="M17.177 8.77c-.08-.29-.22-.55-.41-.77-.2-.22-.44-.4-.71-.52-.26-.12-.55-.18-.84-.18h-3.08V5.33c0-.71-.28-1.4-.79-1.91s-1.19-.79-1.91-.79c-.27 0-.52.17-.64.42l-2.48 5.58h-1.54a2.045 2.045 0 0 0-2.04 2.04v4.66a2.045 2.045 0 0 0 2.04 2.04h9.52a2.043 2.043 0 0 0 2.02-1.73l.92-6c.05-.29.03-.59-.06-.87zm-11.1 7.2h-1.3c-.17 0-.33-.07-.45-.19a.636.636 0 0 1-.19-.45v-4.66c0-.17.07-.33.19-.45s.28-.19.45-.19h1.3zm8.86-.54c-.03.15-.1.29-.22.39-.11.09-.26.15-.42.15h-6.82V9.48l2.38-5.36c.19.06.36.15.5.29.25.25.38.58.38.92V8c0 .39.32.7.7.7h3.78c.1 0 .19.02.27.06.08.03.16.09.22.16s.1.15.13.24c.03.08.03.18.02.27z"></path></svg><h5>Pro tip: Where to put that skills section?</h5></div><div class="AnalyticsDetailModal-module__description___ih-q-DeW1V">There are many ways to organize your resume. The position of your skills section depends on whether you mean to prioritize experience or skills.
//
//For instance, if the job requires an uncommon set of skills, you may want to place your skills section near the top of your resume —&nbsp;just below your resume summary.
//
//For most jobs, however, the standard reverse-chronological resume format is the way to go (the skills section positioned under the work experience and/or education section).</div></div></div>
//HTML;

//    $baseUrl = 'https://block.xyz/careers/jobs/4140247008';
//    $html = <<<'HTML'
//<div class="job-container svelte-19ltyf0"><div class="twist-grid css-s0hqca svelte-6p5tao"><div class="grid-item css-8wyx66 svelte-16yd68s"><div class="tab-section css-xqxuob svelte-1p6mt9"><div class="tab-container svelte-1p6mt9" role="tablist" aria-labelledby="job-application-tabs" dir="ltr"><button id="job-details-tab" class="tab-button primary-button svelte-1p6mt9 selected" type="button" role="tab" aria-selected="true" aria-controls="job-details-content" tabindex="0" data-dd-action-name="Job Details tab button">Job Details</button> <button id="apply-form-tab" class="tab-button primary-button svelte-1p6mt9" type="button" role="tab" aria-selected="false" aria-controls="apply-form-content" tabindex="-1" data-dd-action-name="Apply tab button">Apply</button></div></div></div> <div class="grid-item css-1nadtug svelte-16yd68s"><div class="css-mw4gp5" style="display: var(--display);margin-bottom: var(--margin-bottom);"><div class="wrapper left svelte-1jzntk9"><div class="lockup css-tsqlec darkOnLight buSingle svelte-1jzntk9"><div class="block svelte-1jzntk9"><img src="/bu_logos/icon/Block_Color.png" alt="block logo" class="logo"></div> <div class="brackets svelte-1jzntk9"><svg width="20" height="98" viewBox="0 0 20 98" fill="none" xmlns="http://www.w3.org/2000/svg" class="bracket">
//<path d="M6.99932 91.0003L6.99992 7L20 7L19.9846 0.000670834L0 0L1.71349e-05 98L20 98L19.9846 91.0007L6.99932 91.0003Z" fill="black"></path>
//</svg> <div class="logoWrap svelte-1jzntk9"><img src="/bu_logos/icon/Proto_Color_Dark.svg" alt="proto" class="logo"></div> <svg width="20" height="98" viewBox="0 0 20 98" fill="none" xmlns="http://www.w3.org/2000/svg" class="bracket">
//<path d="M13.0007 91.0003L13.0001 7L0 7L0.0153713 0.000670834L20 0L20 98L0 98L0.0153713 91.0007L13.0007 91.0003Z" fill="black"></path>
//</svg></div></div></div></div></div> <div class="grid-item css-hikxis svelte-16yd68s"></div> <div class="grid-item css-q50dz svelte-16yd68s"><div id="job-details-content" role="tabpanel" aria-labelledby="job-details" class="svelte-19ltyf0"><div class="css-1u7p4gj svelte-1a3oq8x" data-testid="job-about"><p>Proto is accelerating the world's transition to an open economy with products that increase access and independence for everyone. We're building Bitkey, a simple and safe self-custody bitcoin wallet that will put customers in control, as well as hardware and software that will help decentralize bitcoin mining and enable new and innovative use cases for bitcoin mining. We're developing these products in the open - you can read more about them at <a href="http://bitkey.build/" target="_blank">bitkey.build</a> and <a href="http://mining.build/" target="_blank">mining.build</a>. Within Proto, our Bitcoin Products team delivers the product and go-to-market strategy, software, firmware, and custom silicon needed to make Bitkey and our ambitious mining initiatives a reality. Come build the future of money with us!</p>
//<p>&nbsp;</p>
//<p><strong>The Role</strong></p>
//<p>As a Software Engineering Program Manager, you will oversee the development and delivery of software projects, ensuring they meet internal and external requirements. You will play a pivotal role in aligning cross-functional teams, managing project timelines, and ensuring the successful deployment of software solutions that drive our mission forward. The Software Engineering Program Manager is part of the Hardware organization, partnering with Hardware Engineering Program Managers. This role reports to the Software Engineering Program Management Lead.&nbsp;</p>
//<p><strong>You Will</strong></p>
//<ul>
//<li>Be the central hub bringing together different teams from Software, Hardware, Product, Design, Operations, Manufacturing, all to deliver groundbreaking new products.</li>
//<li>Partner with cross functional teams to define, schedule, track, and help lead the work for these exciting new products.&nbsp;</li>
//<li>Lead the strategy from concept to launch and oversee multiple parallel initiatives.</li>
//<li>Translate complex ambiguous problems into simple actionable solutions to unblock teams and support progress.</li>
//<li>Ensure the teams are on track, facilitate decision-making, resolve blockers, and surface challenges by gleaning early signals.&nbsp;</li>
//<li>Drive transparency and visibility by acting as the point of contact for programs, streamlining communications, and providing regular updates to cross functional partners and leadership to ensure information and knowledge is flowing freely.</li>
//<li>Enable continuous improvement during the project, ensuring takeaways are captured, shared, and coordinated.</li>
//<li>Lead by inspiring, developing and challenging the status quo, modeling excellence.</li>
//<li>Find opportunities to apply AI to automate program management tasks.&nbsp;</li>
//</ul>
//<p><strong>You Have&nbsp;</strong></p>
//<ul>
//<li>Bachelor's degree in Computer Science, Engineering, or a related technical discipline; Master's degree preferred.</li>
//<li>5-10 years of industry experience in software development or program management within a Hardware development environment.</li>
//<li>Experience with embedded systems, knowledge of real time operating systems, familiarity with software development tools, understanding of hardware-software interfaces.</li>
//<li>Proficiency in core project management disciplines including scope, schedule, budget, resources, quality, and risk management.&nbsp;</li>
//<li>Strategic thinker with the ability to manage ambiguity, risk, and frequently changing priorities.&nbsp;</li>
//<li>Thoughtfully sets a vision, adjusting where necessary, working through blockers, simplifying for self and others.</li>
//<li>Excellent written and verbal communication skills, with the ability to effectively communicate with various engineering and business partners.</li>
//<li>Strong problem-solving skills, with a proactive and forward-thinking approach.</li>
//<li>Experience with agile methodologies and tools.</li>
//<li>Ability to manage multiple projects simultaneously, with a keen attention to detail.</li>
//<li>Ability to travel to Contract Manufacturing vendor sites for build milestones.&nbsp;</li>
//<li>Familiarity with Bitcoin and Blockchain technologies preferred.</li>
//</ul>
//<p>&nbsp;</p>
//<p>We're working to build a more inclusive economy where our customers have equal access to opportunity, and we strive to live by these same values in building our workplace. Block is an equal opportunity employer evaluating all employees and job applicants without regard to identity or any legally protected class. We will consider qualified applicants with arrest or conviction records for employment in accordance with state and local laws and "fair chance" ordinances.</p>
//<p>We believe in being fair, and are committed to an inclusive interview experience, including providing reasonable accommodations to disabled applicants throughout the recruitment process. We encourage applicants to share any needed accommodations with their recruiter, who will treat these requests as confidentially as possible. <strong>Want to learn more about what we're doing to build a workplace that is fair and square? Check out our</strong> <a href="https://block.xyz/news/inclusion" target="_blank">I+D page</a>.</p>
//<p>While there is no specific deadline to apply for this role, U.S. roles are typically open for an average of 55 days before being filled by a successful candidate. Please refer to the date listed at the top of this job page for when this role was first posted.</p><div class="content-pay-transparency"><div class="pay-input"><div class="description"><p>&nbsp;</p>
//<p>Block takes a market-based approach to pay, and pay may vary depending on your location. U.S. locations are categorized into one of four zones based on a cost of labor index for that geographic area. The successful candidate’s starting pay will be determined based on job-related skills, experience, qualifications, work location, and market conditions. These ranges may be modified in the future.</p>
//<p>To find a location’s zone designation, please refer to this&nbsp;<a href="https://block.xyz/documents/salaryzones.pdf" target="_blank">resource</a>. If a location of interest is not listed, please speak with a recruiter for additional information.&nbsp;</p>
//<p>&nbsp;</p></div><div class="title">Zone A:</div><div class="pay-range"><span>$156,200</span><span class="divider">—</span><span>$234,200 USD</span></div></div><div class="pay-input"><div class="title">Zone B: </div><div class="pay-range"><span>$148,300</span><span class="divider">—</span><span>$222,500 USD</span></div></div><div class="pay-input"><div class="title">Zone C:</div><div class="pay-range"><span>$140,600</span><span class="divider">—</span><span>$210,800 USD</span></div></div><div class="pay-input"><div class="title">Zone D:</div><div class="pay-range"><span>$132,700</span><span class="divider">—</span><span>$199,100 USD</span></div></div></div><div class="content-conclusion"><p><em data-stringify-type="italic">Every benefit we offer is designed with one goal: empowering you to do the best work of your career while building the life you want. Remote work, medical insurance, flexible time off, retirement savings plans, and modern family planning are just some of our offering.&nbsp;</em><em data-stringify-type="italic"><a class="c-link c-link--underline" href="https://block.xyz/documents/benefits.pdf" target="_blank" data-stringify-link="https://block.xyz/documents/benefits.pdf" data-sk="tooltip_parent">Check out our other benefits at Block.</a></em></p>
//<p><em>Block, Inc. (NYSE: XYZ) builds technology to increase access to the global economy. Each of our brands unlocks different aspects of the economy for more people.&nbsp;<strong data-stringify-type="bold">Square</strong>&nbsp;makes commerce and financial services accessible to sellers.&nbsp;<strong data-stringify-type="bold">Cash App</strong>&nbsp;is the easy way to spend, send, and store money.&nbsp;<strong data-stringify-type="bold">Afterpay</strong>&nbsp;is transforming the way customers manage their spending over time.&nbsp;<strong data-stringify-type="bold">TIDAL</strong>&nbsp;is a music platform that empowers artists to thrive as entrepreneurs.&nbsp;<strong data-stringify-type="bold">Bitkey</strong>&nbsp;is a simple self-custody wallet built for bitcoin.&nbsp;<strong data-stringify-type="bold">Proto</strong>&nbsp;is a suite of bitcoin mining products and services. Together, we’re helping build a financial system that is open to everyone.</em></p>
//<p><a href="https://block.xyz/en/legal/applicant-privacy-notice" target="_blank">Privacy Policy</a></p></div></div> <div style="--theme-button-primary-button-default-background-color: #FF5B00;--theme-button-primary-button-default-foreground-color: black;--theme-button-primary-button-hover-focus-background-color: black;--theme-button-primary-button-hover-focus-foreground-color: white;--theme-button-primary-button-active-background-color: black;--theme-button-primary-button-active-foreground-color: white;--theme-button-secondary-button-default-background-color: #fff;--theme-button-secondary-button-default-foreground-color: #000;--theme-button-secondary-button-default-border-color: #FF5B00;--theme-button-secondary-button-hover-focus-background-color: #FF5B00;--theme-button-secondary-button-hover-focus-foreground-color: black;--theme-button-secondary-button-hover-focus-border-color: #FF5B00;--theme-button-secondary-button-active-background-color: #FF5B00;--theme-button-secondary-button-active-foreground-color: black;--theme-button-secondary-button-active-border-color: #FF5B00;--theme-brand-color: #FF5B00;--theme-bu-marquee-brand-color: #FF5B00;--theme-job-content-brand-color: #FF5B00;--theme-job-location-color: black;"><div class="css-h87lbp" style="display: var(--display);margin-top: var(--margin-top);"><div class="twist-stack css-174wxox svelte-qipevy" data-component="TwistStack"><div class="css-7kb56q" style="display: var(--display);"><button role="button" class="css-1m99t89 button variant-fill full-width animate-when-inactive svelte-3j8joj" tabindex="0" data-dd-action-name="Apply button" style="--transform-rotation: -89.72218528292734;"><span class="text svelte-3j8joj"><div class="css-16u7kps apply-text svelte-gwjsea">Apply
//
//    <div class="apply-icon svelte-gwjsea"><svg viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.3623 0.0473633L12.423 6.10796L6.3623 12.1687L5.30161 11.108L9.55161 6.85796H0.612305V5.35796H9.55161L5.30161 1.10796L6.3623 0.0473633Z" fill="currentColor"></path></svg></div></div></span> <span class="icon-outer svelte-3j8joj"></span></button></div> <div class="css-7kb56q" style="display: var(--display);"><a role="link" class="css-1m99t89 button variant-outline full-width animate-when-inactive svelte-3j8joj" href="/careers/jobs" tabindex="0" data-dd-action-name="See all jobs button" style="--transform-rotation: 0;"><span class="text svelte-3j8joj">See all jobs</span> <span class="icon-outer svelte-3j8joj"></span></a></div></div></div></div></div></div></div> <div id="apply-form-content" role="tabpanel" aria-labelledby="apply-form" class="svelte-19ltyf0 hidden"><div class="twist-grid css-s0hqca svelte-6p5tao"><div class="grid-item css-1t8nci6 svelte-16yd68s"><div id="grnhse_app"></div></div></div></div></div>
//HTML;

    $baseUrl = 'https://www.linkedin.com/jobs/search/?currentJobId=4202640799&f_C=675562&originToLandingJobPostings=4202640799%2C4179644318&trk=d_flagship3_company';
    $html = <<<HTML
<div class="grid-item css-q50dz svelte-16yd68s"><div id="job-details-content" role="tabpanel" aria-labelledby="job-details" class="svelte-19ltyf0"><div class="css-1u7p4gj svelte-1a3oq8x" data-testid="job-about"><p>Block is one company built from many blocks, all united by the same purpose of economic empowerment. The blocks that form our foundational teams — People, Finance, Counsel, Hardware, Information Security, Platform Infrastructure Engineering, and more — provide support and guidance at the corporate level. They work across business groups and around the globe, spanning time zones and disciplines to develop inclusive People policies, forecast finances, give legal counsel, safeguard systems, nurture new initiatives, and more. Every challenge creates possibilities, and we need different perspectives to see them all. Bring yours to Block.</p>
<h3>The Role</h3>
<p>Proto is accelerating the world's transition to an open economy with products that increase access and independence for everyone. We're building Proto, hardware and software to help decentralize bitcoin mining and promote innovative use cases for bitcoin mining. We're developing these products in the open - you can read more about them at <a href="http://mining.build" target="_blank">mining.build</a>. Within Proto, our Bitcoin Products team delivers the product and go-to-market strategy, software, firmware, and custom silicon needed to make Bitkey and our mining initiatives a reality. Come build the future of money with us!</p>
<p>The Proto software team is a newly formed team responsible for prototyping, designing, and delivering software applications to support Block's custom Bitcoin mining chip. We work with the ASIC and Electrical Engineering teams to redefine what Bitcoin mining is.</p>
<p>As the team is small, you will be responsible for all aspects of software development, which includes building, testing, and deploying on both bare metal embedded and Linux environments.</p>
<p>We are a small, dynamic team with an abundance of growth and opportunities to make an impact!</p>
<h3>You Will</h3>
<ul>
<li>Work with the team to help develop features; debug and guide engineers through problems</li>
<li>Participate in reviewing and finalizing technical decisions</li>
<li>Be responsible for the technical architecture of features that go into our products</li>
<li>Scope and evaluate new technologies/architectures/practices to solve new problems on our roadmap</li>
<li>Champion and define team best practices</li>
<li>Mentor other senior engineers or managers on strategy, collaboration, influence, execution, and other aspects of leadership</li>
<li>Partner with stakeholders to ensure technical execution meets expectations.</li>
</ul>
<h3>You Have</h3>
<ul>
<li>8+ years of relevant professional experience.</li>
<li>Experience using Python in automated testing, build systems, or device firmware communication</li>
<li>Proficiency in embedded programming languages like C, C++ or Rust,</li>
<li>Experience with embedded systems, knowledge of operating systems, familiarity with software development tools, understanding of hardware-software interfaces</li>
<li>Experience with application development on Linux or Unix</li>
</ul>
<h3>Preferred:</h3>
<ul>
<li>Knowledge of Bitcoin mining</li>
<li>Experience working in Rust</li>
<li>Experience in multi-threaded programming</li>
<li>Experience working in Web development (HTML, Java Script, CSS, Node.js, CGI)</li>
<li>Experience using Python (or similar scripting languages) in automated testing, build systems, or device firmware communication</li>
<li>Understanding of common communications protocols (e.g. Ethernet, SPI, UART, I2C, USB), debugging practices (e.g. JTAG and associated GDB integrations), and basic electrical design principles</li>
<li>Degree in CS, CE, EE, or equivalent practical experience</li>
</ul>
<p>We're working to build a more inclusive economy where our customers have equal access to opportunity, and we strive to live by these same values in building our workplace. Block is an equal opportunity employer evaluating all employees and job applicants without regard to identity or any legally protected class. We will consider qualified applicants with arrest or conviction records for employment in accordance with state and local laws and "fair chance" ordinances.</p>
<p>We believe in being fair, and are committed to an inclusive interview experience, including providing reasonable accommodations to disabled applicants throughout the recruitment process. We encourage applicants to share any needed accommodations with their recruiter, who will treat these requests as confidentially as possible. <strong>Want to learn more about what we're doing to build a workplace that is fair and square? Check out our</strong> <a href="https://block.xyz/news/inclusion" target="_blank">I+D page</a>.</p>
<p>While there is no specific deadline to apply for this role, U.S. roles are typically open for an average of 55 days before being filled by a successful candidate. Please refer to the date listed at the top of this job page for when this role was first posted.</p><div class="content-pay-transparency"><div class="pay-input"><div class="description"><p>&nbsp;</p>
<p>Block takes a market-based approach to pay, and pay may vary depending on your location. U.S. locations are categorized into one of four zones based on a cost of labor index for that geographic area. The successful candidate’s starting pay will be determined based on job-related skills, experience, qualifications, work location, and market conditions. These ranges may be modified in the future.</p>
<p>To find a location’s zone designation, please refer to this&nbsp;<a href="https://block.xyz/documents/salaryzones.pdf" target="_blank">resource</a>. If a location of interest is not listed, please speak with a recruiter for additional information.&nbsp;</p>
<p>&nbsp;</p></div><div class="title">Zone A:</div><div class="pay-range"><span>$217,800</span><span class="divider">—</span><span>$326,800 USD</span></div></div><div class="pay-input"><div class="title">Zone B: </div><div class="pay-range"><span>$207,000</span><span class="divider">—</span><span>$310,400 USD</span></div></div><div class="pay-input"><div class="title">Zone C:</div><div class="pay-range"><span>$196,100</span><span class="divider">—</span><span>$294,100 USD</span></div></div><div class="pay-input"><div class="title">Zone D:</div><div class="pay-range"><span>$185,200</span><span class="divider">—</span><span>$277,800 USD</span></div></div></div><div class="content-conclusion"><p><em data-stringify-type="italic">Every benefit we offer is designed with one goal: empowering you to do the best work of your career while building the life you want. Remote work, medical insurance, flexible time off, retirement savings plans, and modern family planning are just some of our offering.&nbsp;</em><em data-stringify-type="italic"><a class="c-link c-link--underline" href="https://block.xyz/documents/benefits.pdf" target="_blank" data-stringify-link="https://block.xyz/documents/benefits.pdf" data-sk="tooltip_parent">Check out our other benefits at Block.</a></em></p>
<p><em>Block, Inc. (NYSE: XYZ) builds technology to increase access to the global economy. Each of our brands unlocks different aspects of the economy for more people.&nbsp;<strong data-stringify-type="bold">Square</strong>&nbsp;makes commerce and financial services accessible to sellers.&nbsp;<strong data-stringify-type="bold">Cash App</strong>&nbsp;is the easy way to spend, send, and store money.&nbsp;<strong data-stringify-type="bold">Afterpay</strong>&nbsp;is transforming the way customers manage their spending over time.&nbsp;<strong data-stringify-type="bold">TIDAL</strong>&nbsp;is a music platform that empowers artists to thrive as entrepreneurs.&nbsp;<strong data-stringify-type="bold">Bitkey</strong>&nbsp;is a simple self-custody wallet built for bitcoin.&nbsp;<strong data-stringify-type="bold">Proto</strong>&nbsp;is a suite of bitcoin mining products and services. Together, we’re helping build a financial system that is open to everyone.</em></p>
<p><a href="https://block.xyz/en/legal/applicant-privacy-notice" target="_blank">Privacy Policy</a></p></div></div> <div style="--theme-button-primary-button-default-background-color: #FF5B00;--theme-button-primary-button-default-foreground-color: black;--theme-button-primary-button-hover-focus-background-color: black;--theme-button-primary-button-hover-focus-foreground-color: white;--theme-button-primary-button-active-background-color: black;--theme-button-primary-button-active-foreground-color: white;--theme-button-secondary-button-default-background-color: #fff;--theme-button-secondary-button-default-foreground-color: #000;--theme-button-secondary-button-default-border-color: #FF5B00;--theme-button-secondary-button-hover-focus-background-color: #FF5B00;--theme-button-secondary-button-hover-focus-foreground-color: black;--theme-button-secondary-button-hover-focus-border-color: #FF5B00;--theme-button-secondary-button-active-background-color: #FF5B00;--theme-button-secondary-button-active-foreground-color: black;--theme-button-secondary-button-active-border-color: #FF5B00;--theme-brand-color: #FF5B00;--theme-bu-marquee-brand-color: #FF5B00;--theme-job-content-brand-color: #FF5B00;--theme-job-location-color: black;"><div class="css-h87lbp" style="display: var(--display);margin-top: var(--margin-top);"><div class="twist-stack css-174wxox svelte-qipevy" data-component="TwistStack"><div class="css-7kb56q" style="display: var(--display);"><button role="button" class="css-1m99t89 button variant-fill full-width animate-when-inactive svelte-3j8joj" style="--transform-rotation: -80.72795103723564;" tabindex="0" data-dd-action-name="Apply button"><span class="text svelte-3j8joj"><div class="css-16u7kps apply-text svelte-gwjsea">Apply

    <div class="apply-icon svelte-gwjsea"><svg viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.3623 0.0473633L12.423 6.10796L6.3623 12.1687L5.30161 11.108L9.55161 6.85796H0.612305V5.35796H9.55161L5.30161 1.10796L6.3623 0.0473633Z" fill="currentColor"></path></svg></div></div></span> <span class="icon-outer svelte-3j8joj"></span></button></div> <div class="css-7kb56q" style="display: var(--display);"><a role="link" class="css-1m99t89 button variant-outline full-width animate-when-inactive svelte-3j8joj" style="--transform-rotation: 0;" href="/careers/jobs" tabindex="0" data-dd-action-name="See all jobs button"><span class="text svelte-3j8joj">See all jobs</span> <span class="icon-outer svelte-3j8joj"></span></a></div></div></div></div></div></div>
HTML;


    $markdown = app(\App\Services\HtmlToMarkdownService::class)->convert($html, true, $baseUrl);
    $this->info($markdown);

})->purpose('Convert HTML to Markdown');


Artisan::command('test:generate-cover-letter {jobid}', function () {
    $jobId = $this->argument('jobid');
    $job = \App\Models\JobPost::find($jobId);
    if (!$job) {
        $this->error('Job not found');
        return;
    }

    $this->info('Generating cover letter for job: ' . $job->title);

    $generationService = app(\App\Services\GenerationService::class);
    $coverLetter = $generationService->generateCoverLetter($job);

})->describe('Generate cover letter for a job post');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


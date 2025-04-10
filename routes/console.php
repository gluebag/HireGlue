<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('telescope:prune --hours=48')->daily();


Artisan::command('htmltomd', function() {
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

    $baseUrl = 'https://chatgpt.com/g/g-p-67df7c5c6cfc8191885020cd3bb7a2f8-brickyard-estates-fb-ads/c/67ef1f77-2e28-8002-ae18-7b12e0af6cd6';
    $html = <<<HTML
<div jscontroller="iYVHDc" class="inherited-styles-for-exported-element div-DkhPwc" jsname="tIk9qd" data-id="135461046696452806" jsaction="A6AxKc:CXOOvf;">
  <div class="div-sPeqm">
    <h2 class="h2-p1N2lc">Customer Engineer II, Platform Engineering, Google Cloud</h2>
    <div class="div-Fy5Tp">
      <div class="div-EJNmWe-unique-1" jsname="OrKVr" jscontroller="H27hkd" jsaction="FzgWvd:RFVo1b;JIbuQc:yUMr8c(eEndy),Imfim(qXjuib)">
        <div class="div-VfPpkd-xl07Ob-XxIAqe-OWXEXe-oYxtQd-e0JdFb-unique-1" jscontroller="wg1P6b" jsaction="JIbuQc:aj0Jcf(WjL7X); keydown:uYT2Vb(WjL7X);xDliB:oNPcuf;SM8mFd:li9Srb;iFFCZc:NSsOUb;Rld2oe:NSsOUb" jsshadow="">
          <div jsname="WjL7X" jsslot="" class="undefined"><button class="button-VfPpkd-Bz112c-LgbsSe-yHy1rc-eT1oJ-mN1ivc-unique-1" jscontroller="soHxf" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;" data-idom-class="yHy1rc eT1oJ mN1ivc" aria-label="Share Customer Engineer II, Platform Engineering, Google Cloud" aria-expanded="false" aria-haspopup="menu">
              <div jsname="s3Eaab" class="div-VfPpkd-Bz112c-Jh9lGc-unique-1"></div>
              <div class="div-VfPpkd-Bz112c-J1Ukfc-LhBDec-unique-1"></div><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-1" aria-hidden="true">share</i>
            </button></div>
          <div jsname="U0exHf" jsslot="" class="undefined">
            <div class="div-VfPpkd-xl07Ob-XxIAqe-VfPpkd-xl07Ob-q6oraf-P77izf-unique-1" jscontroller="ywOR5c" jsaction="keydown:I481le;JIbuQc:j697N(rymPhb);XVaHYd:c9v4Fb(rymPhb);Oyo5M:b5fzT(rymPhb);DimkCe:TQSy7b(rymPhb);m0LGSd:fAWgXe(rymPhb);WAiFGd:kVJJuc(rymPhb);" data-is-hoisted="false" data-should-flip-corner-horizontally="false" data-stay-in-viewport="false" data-menu-uid="ucj-2">
              <ul class="ul-VfPpkd-StrnGf-rymPhb-DMZ54e-unique-1" jsname="rymPhb" jscontroller="PHUIyb" jsaction="mouseleave:JywGue; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; keydown:I481le;" role="menu" tabindex="-1" aria-label="Share a job options" data-disable-idom="true"><span class="span-VfPpkd-BFbNVe-bF1uUb-NZp2ef-unique-1" aria-hidden="true"></span>
                <li class="li-VfPpkd-StrnGf-rymPhb-ibnC6b-unique-1" jsaction=" keydown:RDtNu; keyup:JdS61c; click:o6ZaF; mousedown:teoBgf; mouseup:NZPHBc; mouseleave:xq3APb; touchstart:jJiBRc; touchmove:kZeBdd; touchend:VfAz8;focusin:MeMJlc; focusout:bkTmIf;mouseenter:SKyDAe; change:uOgbud;" role="menuitem" jsname="SbVnGf" tabindex="-1"><span class="span-VfPpkd-StrnGf-rymPhb-pZXsl-unique-1"></span><span class="span-VfPpkd-StrnGf-rymPhb-Zmlebc-LhBDec"></span><span class="span-VfPpkd-StrnGf-rymPhb-f7MjDc-unique-1"><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-2" aria-hidden="true">link</i></span><span jsname="K4r5Ff" class="span-VfPpkd-StrnGf-rymPhb-b9t22c-unique-1">Copy link</span></li>
                <li class="li-VfPpkd-StrnGf-rymPhb-ibnC6b-unique-2" jsaction=" keydown:RDtNu; keyup:JdS61c; click:o6ZaF; mousedown:teoBgf; mouseup:NZPHBc; mouseleave:xq3APb; touchstart:jJiBRc; touchmove:kZeBdd; touchend:VfAz8;focusin:MeMJlc; focusout:bkTmIf;mouseenter:SKyDAe; change:uOgbud;" role="menuitem" jsname="YXVnZ" tabindex="-1"><span class="span-VfPpkd-StrnGf-rymPhb-pZXsl-unique-2"></span><span class="span-VfPpkd-StrnGf-rymPhb-Zmlebc-LhBDec"></span><span class="span-VfPpkd-StrnGf-rymPhb-f7MjDc-unique-2"><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-1" aria-hidden="true">email</i></span><span jsname="K4r5Ff" class="span-VfPpkd-StrnGf-rymPhb-b9t22c-unique-2">Email a friend</span></li>
              </ul>
            </div>
          </div>
        </div><button class="button-VfPpkd-Bz112c-LgbsSe-fzRBVc-tmJved-mN1ivc-unique-1" jscontroller="xzbRj" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;" data-idom-class="fzRBVc tmJved mN1ivc" jsname="eEndy" data-sync-idom-state="true" aria-label="Bookmark Customer Engineer II, Platform Engineering, Google Cloud" aria-pressed="false">
          <div jsname="s3Eaab" class="div-VfPpkd-Bz112c-Jh9lGc-unique-2"></div>
          <div class="div-VfPpkd-Bz112c-J1Ukfc-LhBDec-unique-2"></div><span class="span-style-1-unique-1" aria-hidden="true"><i class="i-style-1-unique-1" aria-hidden="true">bookmark</i></span><span class="span-material-icons-extended-VfPpkd-Bz112c-kBDsod-unique-1" aria-hidden="true"><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-2" aria-hidden="true">bookmark_border</i></span>
        </button>
      </div>
    </div>
  </div>
  <div class="div-op1BBf"><span class="span-RP7SMd-unique-1"><i class="i-style-2-unique-1" aria-hidden="true">corporate_fare</i><span class="undefined">Google</span></span><span class="span-pwO9Dc-vo5qdf"><i class="i-style-3-unique-2" aria-hidden="true">place</i><span class="span-r0wTof">Atlanta, GA, USA</span><span class="span-r0wTof-p3oCrc">; Reston, VA, USA</span><span class="span-BVHzed">; +7 more</span><span class="span-Z2gFhf">; +6 more</span></span><span class="span-RP7SMd-unique-2"><i class="i-style-2-unique-1" aria-hidden="true">laptop_windows</i><span class="undefined">Remote eligible</span></span>
    <div class="undefined"><span class="span-VfPpkd-suEOdc-sM5MNb-OWXEXe-nzrxxc" data-is-tooltip-wrapper="true">
        <div class="div-VfPpkd-dgl2Hf-ppHlrf-sM5MNb" data-is-touch-wrapper="true"><button class="button-style-1" jscontroller="soHxf" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;" data-disable-idom="true" data-tooltip-enabled="true" data-tooltip-is-rich="true" aria-describedby="i11" role="img" aria-label="Mid, Learn more about experience filters.">
            <div class="div-VfPpkd-Jh9lGc-unique-1"></div>
            <div class="div-VfPpkd-J1Ukfc-LhBDec"></div>
            <div class="div-VfPpkd-RLmnJb"></div><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-3" aria-hidden="true"><i class="i-style-4-unique-3" aria-hidden="true">bar_chart</i></i><span jsname="V67aGc" class="span-VfPpkd-vQzf8d"><span class="span-wVSTAb">Mid</span></span>
          </button></div>
        <div jsshadow="" jscontroller="HmEm0" jsaction="BfpAHf:TCTP9d;Nwyqre:DsZxZc; transitionend:e204de;" data-title-id-disregard="ucj-3" id="i11" class="div-style-1" aria-hidden="true" role="tooltip">
          <div class="div-VfPpkd-z59Tgd-VfPpkd-z59Tgd-OiiCO" jsname="ebgt1d"><span class="span-VfPpkd-BFbNVe-bF1uUb-NZp2ef-unique-2" aria-hidden="true"></span>
            <h2 class="h2-ucj-3-VfPpkd-MlC99b" id="ucj-3">Mid</h2>
            <div jsslot="" class="div-VfPpkd-IqDDtd">Experience driving progress, solving problems, and mentoring more junior team members; deeper expertise and applied knowledge within relevant area.</div>
          </div>
        </div>
      </span></div>
  </div>
  <div class="div-fe9XXb" jsaction="JIbuQc:lUErtb">
    <div class="div-VfPpkd-dgl2Hf-ppHlrf-sM5MNb" data-is-touch-wrapper="true">
      <div class="div-style-2" jscontroller="nKuFpb" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;" data-idom-class="nCP5yc AjY5Oe DuMIQc LQeN7 A7K2Fc">
        <div class="div-VfPpkd-Jh9lGc-unique-2"></div><span jsname="V67aGc" class="span-VfPpkd-vQzf8d" aria-hidden="true">Apply</span><a class="a-style-1" href="https://www.google.com/about/careers/applications/jobs/results/135461046696452806-customer-engineer-ii-platform-engineering-google-cloud?location=Miami+UnitedStates&amp;src=https://www.google.com/about/careers/applications/jobs/results/135461046696452806-customer-engineer-ii-platform-engineering-google-cloud?location=Miami+UnitedStates&amp;src=Online/Job+Board/Online/Job+Board../apply?jobId=CiUAL2FckeePiy10NIvddpdaw-P5I0iZs6TbUDcpiB1dq9dXNIPGEjsAgOFyA6P0UwZ6SO51vna6eRidS2kvuI6a14p7OvyIDeI0KfgIk4x9P5hSUnwz9MPvZs9tXaJK9exuYw%3D%3D_V2&amp;loc=US&amp;title=Customer+Engineer+II&amp;location=Miami+UnitedStates&amp;src=Online/Job+Board/glassdoor%22 aria-label=" apply"="" id="apply-action-button" data-navigation="server" jsname="hSRGPd"></a>
        <div class="div-VfPpkd-J1Ukfc-LhBDec"></div>
      </div>
    </div>
    <div class="div-v77buc">
      <div class="div-EJNmWe-unique-2" jsname="OrKVr" jscontroller="H27hkd" jsaction="FzgWvd:RFVo1b;JIbuQc:yUMr8c(eEndy),Imfim(qXjuib)">
        <div class="div-VfPpkd-xl07Ob-XxIAqe-OWXEXe-oYxtQd-e0JdFb-unique-2" jscontroller="wg1P6b" jsaction="JIbuQc:aj0Jcf(WjL7X); keydown:uYT2Vb(WjL7X);xDliB:oNPcuf;SM8mFd:li9Srb;iFFCZc:NSsOUb;Rld2oe:NSsOUb" jsshadow="">
          <div jsname="WjL7X" jsslot="" class="undefined"><button class="button-VfPpkd-Bz112c-LgbsSe-yHy1rc-eT1oJ-mN1ivc-unique-2" jscontroller="soHxf" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;" data-idom-class="yHy1rc eT1oJ mN1ivc" aria-label="Share Customer Engineer II, Platform Engineering, Google Cloud" aria-expanded="false" aria-haspopup="menu">
              <div jsname="s3Eaab" class="div-VfPpkd-Bz112c-Jh9lGc-unique-3"></div>
              <div class="div-VfPpkd-Bz112c-J1Ukfc-LhBDec-unique-1"></div><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-1" aria-hidden="true">share</i>
            </button></div>
          <div jsname="U0exHf" jsslot="" class="undefined">
            <div class="div-VfPpkd-xl07Ob-XxIAqe-VfPpkd-xl07Ob-q6oraf-P77izf-unique-2" jscontroller="ywOR5c" jsaction="keydown:I481le;JIbuQc:j697N(rymPhb);XVaHYd:c9v4Fb(rymPhb);Oyo5M:b5fzT(rymPhb);DimkCe:TQSy7b(rymPhb);m0LGSd:fAWgXe(rymPhb);WAiFGd:kVJJuc(rymPhb);" data-is-hoisted="false" data-should-flip-corner-horizontally="false" data-stay-in-viewport="false" data-menu-uid="ucj-4">
              <ul class="ul-VfPpkd-StrnGf-rymPhb-DMZ54e-unique-2" jsname="rymPhb" jscontroller="PHUIyb" jsaction="mouseleave:JywGue; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; keydown:I481le;" role="menu" tabindex="-1" aria-label="Share a job options" data-disable-idom="true"><span class="span-VfPpkd-BFbNVe-bF1uUb-NZp2ef-unique-3" aria-hidden="true"></span>
                <li class="li-VfPpkd-StrnGf-rymPhb-ibnC6b-unique-1" jsaction=" keydown:RDtNu; keyup:JdS61c; click:o6ZaF; mousedown:teoBgf; mouseup:NZPHBc; mouseleave:xq3APb; touchstart:jJiBRc; touchmove:kZeBdd; touchend:VfAz8;focusin:MeMJlc; focusout:bkTmIf;mouseenter:SKyDAe; change:uOgbud;" role="menuitem" jsname="SbVnGf" tabindex="-1"><span class="span-VfPpkd-StrnGf-rymPhb-pZXsl-unique-1"></span><span class="span-VfPpkd-StrnGf-rymPhb-Zmlebc-LhBDec"></span><span class="span-VfPpkd-StrnGf-rymPhb-f7MjDc-unique-1"><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-2" aria-hidden="true">link</i></span><span jsname="K4r5Ff" class="span-VfPpkd-StrnGf-rymPhb-b9t22c-unique-1">Copy link</span></li>
                <li class="li-VfPpkd-StrnGf-rymPhb-ibnC6b-unique-2" jsaction=" keydown:RDtNu; keyup:JdS61c; click:o6ZaF; mousedown:teoBgf; mouseup:NZPHBc; mouseleave:xq3APb; touchstart:jJiBRc; touchmove:kZeBdd; touchend:VfAz8;focusin:MeMJlc; focusout:bkTmIf;mouseenter:SKyDAe; change:uOgbud;" role="menuitem" jsname="YXVnZ" tabindex="-1"><span class="span-VfPpkd-StrnGf-rymPhb-pZXsl-unique-2"></span><span class="span-VfPpkd-StrnGf-rymPhb-Zmlebc-LhBDec"></span><span class="span-VfPpkd-StrnGf-rymPhb-f7MjDc-unique-2"><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-1" aria-hidden="true">email</i></span><span jsname="K4r5Ff" class="span-VfPpkd-StrnGf-rymPhb-b9t22c-unique-2">Email a friend</span></li>
              </ul>
            </div>
          </div>
        </div><button class="button-VfPpkd-Bz112c-LgbsSe-fzRBVc-tmJved-mN1ivc-unique-2" jscontroller="xzbRj" jsaction="click:cOuCgd; mousedown:UX7yZ; mouseup:lbsD7e; mouseenter:tfO1Yc; mouseleave:JywGue; touchstart:p6p2H; touchmove:FwuNnf; touchend:yfqBxc; touchcancel:JMtRjd; focus:AHmuwe; blur:O22p3e; contextmenu:mg9Pef;mlnRJb:fLiPzd;" data-idom-class="fzRBVc tmJved mN1ivc" jsname="eEndy" data-sync-idom-state="true" aria-label="Bookmark Customer Engineer II, Platform Engineering, Google Cloud" aria-pressed="false">
          <div jsname="s3Eaab" class="div-VfPpkd-Bz112c-Jh9lGc-unique-4"></div>
          <div class="div-VfPpkd-Bz112c-J1Ukfc-LhBDec-unique-2"></div><span class="span-style-2-unique-2" aria-hidden="true"><i class="i-style-5-unique-2" aria-hidden="true">bookmark</i></span><span class="span-material-icons-extended-VfPpkd-Bz112c-kBDsod-unique-2" aria-hidden="true"><i class="i-google-material-icons-notranslate-VfPpkd-kBDsod-unique-2" aria-hidden="true">bookmark_border</i></span>
        </button>
      </div>
    </div>
  </div>
  <div class="div-KwJkGe">
    <div role="none" class="div-xbCN0b-KiNied-M0TuQ-LlMNQd" data-announce-callout="true" data-liveregiontext="Info Note: Google’s hybrid workplace includes remote and in-office roles. By applying to this position you will have an opportunity to share your preferred working location from the following:

In-office locations: Atlanta, GA, USA; Reston, VA, USA; Addison, TX, USA.
Remote location(s): Florida, USA; North Carolina, USA; South Carolina, USA; Texas, USA; Virginia, USA." jscontroller="u3jeub" jsaction="rcuQ6b:rcuQ6b;">
      <div class="div-OJgC7b">
        <div class="div-quEfK" role="img" aria-label="Info"><i class="i-google-material-icons-notranslate-Auu9lc" aria-hidden="true">info_outline</i></div><span aria-hidden="true" jsname="arU4oc" class="span-v7dlUb-unique-1">X</span><span role="status" aria-live="polite" jsname="yW4uId" class="span-v7dlUb-unique-2">Info Note: Google’s hybrid workplace includes remote and in-office roles. By applying to this position you will have an opportunity to share your preferred working location from the following:

          In-office locations: Atlanta, GA, USA; Reston, VA, USA; Addison, TX, USA.
          Remote location(s): Florida, USA; North Carolina, USA; South Carolina, USA; Texas, USA; Virginia, USA.</span><span jsname="MyVLbf" class="span-MyVLbf">Note: Google’s hybrid workplace includes remote and in-office roles. By applying to this position you will have an opportunity to share your preferred working location from the following:<br class="css-scan-br-variation-1"><br class="css-scan-br-variation-1"><b class="css-scan-b-variation-2">In-office locations: Atlanta, GA, USA; Reston, VA, USA; Addison, TX, USA.</b><br class="css-scan-br-variation-1"><b class="css-scan-b-variation-2">Remote location(s): Florida, USA; North Carolina, USA; South Carolina, USA; Texas, USA; Virginia, USA.</b></span>
      </div><span role="none" class="span-GMWBfb-NOIZeb"><span role="none" class="span-gdNP2" jsname="gdNP2"></span></span>
    </div><br class="undefined">
    <h3 class="css-scan-h3-variation-3">Minimum qualifications:</h3>
    <ul class="css-scan-ul-variation-4">
      <li class="css-scan-li-variation-5">Bachelor's degree or equivalent practical experience.</li>
      <li class="css-scan-li-variation-6">6 years of experience with cloud native architecture in a customer-facing or support role.</li>
      <li class="css-scan-li-variation-5">Experience with cloud engineering, on-premise engineering, virtualization, or containerization platforms.</li>
      <li class="css-scan-li-variation-6">Experience engaging with, and presenting to, technical stakeholders and executive leaders.</li>
      <li class="css-scan-li-variation-5">Ability to travel up to 20% of the time as needed.</li>
    </ul><br class="undefined">
    <h3 class="css-scan-h3-variation-7">Preferred qualifications:</h3>
    <ul class="css-scan-ul-variation-8">
      <li class="css-scan-li-variation-6">Experience in migrating applications and services to cloud platforms.</li>
      <li class="css-scan-li-variation-5">Experience with "Big Data" technologies or concepts, such as analytics warehousing, data processing, data transformation, data governance, data migrations, ETL, ELT, SQL, NoSQL, performance or scalability optimizations, or batch versus streaming.</li>
      <li class="css-scan-li-variation-6">Experience in developing cloud-native architectures for data warehousing, data lakes, real-time event processing, streaming, data migrations, data visualization tools, and ensuring data governance.</li>
      <li class="css-scan-li-variation-5">Experience with security concepts such as encryption, identity management, access control, attack vectors, and pen testing.</li>
      <li class="css-scan-li-variation-6">Experience prospecting, building, and maintaining customer relationships, with an interest for building out Greenfield territories.</li>
    </ul>
  </div>
  <div class="div-aG5W3">
    <h3 class="css-scan-h3-variation-9">About the job</h3>
    <p class="css-scan-p-variation-10">When leading companies choose Google Cloud, it's a huge win for spreading the power of cloud computing globally. Once educational institutions, government agencies, and other businesses sign on to use Google Cloud products, you come in to facilitate making their work more productive, mobile, and collaborative. You deliver what is most helpful for the customer. You assist fellow sales Googlers by problem-solving key technical issues for our customers. You liaise with the product marketing management and engineering teams to stay on top of industry trends and devise enhancements to Google Cloud products. <br class="undefined"><br class="undefined">As a Customer Engineer, you will partner with technical Sales teams to differentiate Google Cloud to our customers. You will help prospective and existing customers and partners understand the power of Google Cloud, develop creative cloud solutions and architectures to solve their business challenges, engage in proofs-of-concepts, and troubleshoot any technical questions and roadblocks. You will use your expertise and presentation skills to engage with customers to understand their business and technical requirements, and persuasively present practical and useful solutions on Google Cloud. You will have excellent technical, communication and organizational skills.<br class="undefined"><br class="undefined">You will focus on a range of customer opportunities as a technical generalist, spanning infrastructure modernization, application modernization, data analytics and more. You will have a passion for building new relationships, with experience in prospecting and building out greenfield territories.</p>Google Cloud accelerates every organization’s ability to digitally transform its business and industry. We deliver enterprise-grade solutions that leverage Google’s cutting-edge technology, and tools that help developers build more sustainably. Customers in more than 200 countries and territories turn to Google Cloud as their trusted partner to enable growth and solve their most critical business problems.<div class="undefined"><br class="undefined"></div>
    <div class="undefined">The US base salary range for this full-time position is $125,000-$183,000 + bonus + equity + benefits. Our salary ranges are determined by role, level, and location. Within the range, individual pay is determined by work location and additional factors, including job-related skills, experience, and relevant education or training. Your recruiter can share more about the specific salary range for your preferred location during the hiring process.</div>
    <div class="undefined"><br class="undefined"></div>
    <div class="undefined">Please note that the compensation details listed in US role postings reflect the base salary only, and do not include bonus, equity, or benefits. Learn more about <a href="https://careers.google.com/benefits/" class="css-scan-a-variation-11">benefits at Google</a>. </div>
  </div>
  <div class="div-BDNOWe">
    <h3 class="css-scan-h3-variation-12">Responsibilities</h3>
    <ul class="css-scan-ul-variation-13">
      <li class="css-scan-li-variation-14">Work with the team to identify and qualify business opportunities, understand key customer technical objections, and develop the strategy to resolve technical blockers.</li>
      <li class="css-scan-li-variation-15">Share in-depth Google Cloud expertise to support the technical relationship with customers, including technology advocacy, supporting bid responses, product and solution briefings, proof-of-concept work, and partnering directly with product management to prioritize solutions impacting customer adoption to Google Cloud.</li>
      <li class="css-scan-li-variation-14">Work directly with Google Cloud products to demonstrate and prototype integrations in customer and partner environments.</li>
      <li class="css-scan-li-variation-15">Recommend integration strategies, enterprise architectures, platforms, and application infrastructure required to successfully implement a complete solution on Google Cloud.</li>
      <li class="css-scan-li-variation-14">Lead prospecting and acquisition of new logos, creating and building customer relationships from scratch, and establishing yourself as a trusted advisor on their long-term technology and business decisions.</li>
    </ul>
  </div>
  <div class="div-bE3reb">
    <div class="div-XS9rpb">
      <p class="p-ciFk0-unique-1">Information collected and processed as part of your Google Careers profile, and any job applications you choose to submit is subject to Google's <a href="https://www.google.com/about/careers/applications/jobs/results/135461046696452806-customer-engineer-ii-platform-engineering-google-cloud?location=Miami+UnitedStates&amp;src=https://www.google.com/about/careers/applications/jobs/results/135461046696452806-customer-engineer-ii-platform-engineering-google-cloud?location=Miami+UnitedStates&amp;src=Online/Job+Board/Online/Job+Board../privacy-policy%22 class=" targeting-for-doc-to-parse-css-scan-a-variation-16"="">Applicant and Candidate Privacy Policy</a>.</p>
      <p class="p-ciFk0-unique-2">Google is proud to be an equal opportunity and affirmative action employer. We are committed to building a workforce that is representative of the users we serve, creating a culture of belonging, and providing an equal employment opportunity regardless of race, creed, color, religion, gender, sexual orientation, gender identity/expression, national origin, disability, age, genetic information, veteran status, marital status, pregnancy or related condition (including breastfeeding), expecting or parents-to-be, criminal histories consistent with legal requirements, or any other basis protected by law. See also <a href="https://www.google.com/about/careers/applications/eeo/" class="css-scan-a-variation-17"> Google's EEO Policy</a>, <a href="https://careers.google.com/jobs/dist/legal/EEOC_KnowYourRights_10_20.pdf" class="css-scan-a-variation-16">Know your rights: workplace discrimination is illegal</a>, <a href="https://about.google/belonging/" class="css-scan-a-variation-17">Belonging at Google</a>, and <a href="https://careers.google.com/how-we-hire/" class="css-scan-a-variation-16">How we hire</a>.</p>
      <p class="p-ciFk0-unique-1">If you have a need that requires accommodation, please let us know by completing our <a href="https://goo.gl/forms/aBt6Pu71i1kzpLHe2" class="css-scan-a-variation-17">Accommodations for Applicants form</a>.</p>
      <p class="p-ciFk0-unique-2">Google is a global company and, in order to facilitate efficient collaboration and communication globally, English proficiency is a requirement for all roles unless stated otherwise in the job posting.</p>
      <p class="p-ciFk0-unique-1">To all recruitment agencies: Google does not accept agency resumes. Please do not forward resumes to our jobs alias, Google employees, or any other organization location. Google is not responsible for any fees related to unsolicited resumes.</p>
    </div>
  </div>
</div>
HTML;


    $markdown = app(\App\Services\HtmlToMarkdownService::class)->convert($html, true, $baseUrl);
    $this->info($markdown);

})->purpose('Convert HTML to Markdown');


Artisan::command('test:generate-cover-letter {jobid}', function() {
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


Can you help and write me a PROJECT-TODO-CHECKLIST.md aka nice markdown checklist i can follow with concise and clear
steps to follow in terms of architecture and coding the fastest implementation-way meanwhile not skimping on the
functionality and with setting up a fresh laravel 12 and laravel Nova project I called "HireGlue". The checklist should
include the following:

- the relevant research/planning we need to do (e.g. the YouTube videos and notes we need to implement and anything we
  need to do PRIOR to writing any code) to ensure we have a clear understanding of the project requirements and goals
- the relevant steps to set up a fresh laravel 12 and laravel Nova project
- the architecture and code for the database migrations
- the architecture and code for the database seeder (if necessary)
- the architecture and code for the user resource
- the architecture and code for the job post resource
- the architecture and code for the resume resource
- the architecture and code for the cover letter resource
- the architecture and code for the resume generation logic
- the architecture and code for the cover letter generation logic
- the architecture and code for the rules engine
- the architecture and code for the OpenAI API integration

---

## PROJECT PURPOSE / GOALS / OBJECTIVES ##

For me to use internally to, given a specific job post i want to apply to, it will completely automate (or as much as
possible) and generate the absolute PERFECT job-post specific resume.pdf and cover letter.pdf that I should use when
applying to a particular job post. Ive been attempting to do this manually for one job and its very tedious and at the
end of the day, if we strategically map out a robust set of "Rules" it should follow when generating each, and leverage
openai API as much as possible with the latest and most expensive yet powerful models, to ensure the rules are followed
to our best... where even if I went through rule list manually for a week, the system would generate equal quality if
not better that me following same rules. The key point I want to drive home here is that I don't want to just automate
this tedious process I want to automate and come up with a better resume and cover letter for a specific job that I
could never even imagined writing manually even if I spent a week on it.

---

## PROJECT INSPIRATION ##

- the idea came from the fact that I've made so many notes about things to follow and rules when making a cover letter
  or resume and all the YouTube videos I've watched where there's you know 5 tips and tricks and you know to get hired
  at Google and blah blah blah and it's all really good info and there's tons of rules and you know I've taken notes on
  my computer I've taken notes on notebook paper and at the end of the day it's so hard to make sure you're following
  all of them so thats the purpose of making this new HireGlue laravel nova project...

---

## RESEARCH / PLANNING ##

- I want to make sure we do all the research and planning before we start writing any code, so we have a clear
  understanding of the project requirements and goals. This includes:
    - Watching the YouTube videos and taking notes on the rules we need to implement in the project
    - Researching best practices for resume and cover letter generation
    - Researching best practices for using the OpenAI API and the PHP SDK
    - Researching best practices for Laravel Nova and how to set up a new project
- Extra notes:
    - Definitely want to strictly follow and implement all relevant rules from these videos, so please help and write up
      the following which we'll use in the laravel project code to power this beast of a project and generation code.
        1. A breakdown of rules with their source (yt vid, or where), the short title name for the rule, long
           description of the rule if needed... THEN
        2. for each rule (we can chunk them out too and only work 1 at a time or a couple at a time, ill ask you to
           continue to next chunk of rules or rule), but: (2-3 variations of https://github.com/openai-php/client sdk
           php code blocks) that would be used validate and ensure that the resume/cover letter as we generate, follows
           and adheres to the rules we set up).

        - Each variant should be a code block that:
            1. maybe determines/have a score system on how well it adheres to it, and if its low score, put reason why
               it doesnt adhere.
            2. should leverage any best practices for context like embeddings or vector stores or fine-tuned models (if
               applicable or necessary to ensure the output is as good as possible in the context of job posts /
               resumes / cover letters / my job history / github / portfolio / etc / more contextual data to help the
               model generate the best output possible)
            3. should leverage any best practices and implementations for the actual prompting... e.g. using custom
               assistants we can create in the openai API then leverage "threads" on the assistant, and all the extra
               funcionality we can do with the openai API to ensure the output is as good as possible and follows the
               rules we set up... rather than just using the simple one-off chat completions or text completions... we
               should be using the latest and greatest models and functionality available to us in the openai API, and
               leveraging the php sdk to do so... see attached "prompt-HIREGLUE-PHP-OPENAI-SDK-DOCs.md" for more details
               on the openai API and the php sdk and specifically the assistants, threads, thread messages, and tool
               calls, etc. You can also find the docs for openai php sdk
               here: https://raw.githubusercontent.com/openai-php/client/refs/heads/main/README.md
            4. but ya bare minimum in each rules' variant code-block I should see clever, powerful prompts, inputs
               required (aka tokens in the prompt we'll replace with job-post and generation context specific values)
               can be strings, files to attach if necessary etc like idk, a jobposts.html full, or jobpost.md or
               resume-template.pdf or cover-letter-template.pdf or .doc etc,

## List of YouTube videos ##

I watched all of these, and these are what we should reference from when designing/crafting/implementing the rules... I
verified they are solid and watched numerous times (taking notes myself sometimes)

- [Ex-Google recruiter lady - Ex-Google Recruiter Reveals 8 Secrets Recruiters Won’t Tell You](https://www.youtube.com/watch?v=iybdUPYXPEw)
    - **takeaways**
        - if theres more valuable/elaborate rules we can extract from the following notes I PERSONALLY wrote and took
          manually on that same video after watching it 20 times lol:
          ```markdown
          # MY NOTES FROM THE VIDEO
          
          1. No typos, easy to read formatting and simple, professional common fonts (so PDF always loads)
          2. Check out google docs templates for resume
          3. Always have 1/single column resume layout (for app tracking systems)
          4. She said no headshot (im calling bullshit, can A/B test later)
          5. [COVER LETTER? MAYBE RESUME?] Recruiters are looking for "right fit" aka the human-form missing puzzle piece to fit in the composition of the team I'd be working with daily. On the team I'd be working with, whether you're trying to find someone to fill the gap of: soft skills, not the best communicators, or lacking technical skills, Im confident and WILL FILL that gap :)
          6. ASK about what the current gaps are in the cover letter maybe or reword/rephrase it somehow nicely, cleverly and simply in the resume as well.. recruiters like candidates who ask GREAT RELEVANT questions. This further shows I care about the ROLE and being the perfect fit/solution/candidate for them. So ask or sneak in questions even on cover letter or resume that are related to the role, the team, the gaps etc. Ask them what the hiring manager is looking for and even what a perfect ideal candidate looks like to them, After my many years of experience in many facets of industry/tech/people, I'd love to demonstrate my skills further. I wanted to keep this resume (or cover letter) short and simple to start :)
          7. Salary negotiation: u can negotiate only certain things like the top of salary band… BUT you can negotiate sign-on bonus… the time in which I begin , and if I can demonstrate just how soon I can start and ramp-up quickly into working full steam and productivity (in 1-2 weeks vs months like others :)) — and if this is against your policy or unsettling or un-negiotable, I understand :)  never hurts to ask.
          8. DETAILS MATTER ALOT, absolutely 0 typos
          9. Demonstrate that my personality matches googles culture and fully embrace "Googleyness". As a family man with 2 boys, and over the many projects and jobs and teams ive worked with, I truly thrive when everyone including myself is Kind, polite, respectful. I always care about fellow employees even beyond what we're working on. And ensure that, whenever possible, I can offer my help to my fellow employees with any of their workload or struggles. :) go team
          10. When recruiter asks "WHAT ARE YOUR SALARY EXPECTATIONS?" … theyre just genuine and its not a trick question… they want to have an idea if your expectations align with what they are able to give/offer.
              1. ￼"I'VE DONE SOME RESEARCH ON AMAZON'S COMPENSATION & THEIR PHILOSOPHY, I WOULD REALLY LOVE TO KNOW FROM YOU BASED ON THE LEVEL OF THIS POSITION, WHAT DOES AN AVERAGE OFFER LOOK LIKE FOR THIS ROLE?"
              2. ￼"WELL, I DID DO MY RESEARCH ON THIS POSITION BUT I DON'T KNOW WHAT THE SALARY BAND IS FOR THIS ROLE. CAN YOU SHARE THAT WITH ME?"
          11. Recruiters are professional match makers (see screenshot of example resume: RESUME-GOODEXAMPLE-YOUTUBEGOOGLELADY.png) just remember theyre speaking to hiring manager and hearing/ingesting the things theyre looking for, then glancing at tons of resumes and we want ours to just stand out right away boom perfect fit, so always keep that in mind and try to easily bridge-the-mental-gap in recruiters mind and leveraging clever wording on <hiring-manager-requirements> vs <words on my resume/cover letter>
          12. *BE QUALIFIED / BEST FIT / BEST CANDIDATE * for the job… maybe make joke about OFCCP at the bottom of long resume or cover letter (thank you for taking the time to read or even glance at my resume this far **hopefully** with human eyes! *cough* </OFCCP> ;)
          13. Should we separate cover letter and resume into separate PDFs? Or put cover letter on page 1
          14.  I DEFINITELY think we should redo the resume as its WAY too dense and too many words, and I remember watching a YouTube vid by a hiring/recruiting guy for google where he specifically said based on a cool study on tons of resumes that the sweet spot is somewhere in the 500-750 word range. Anything more is just too much… but I do understand that my experience and skills are vast and demonstrating why im a perfect fit both in detail but also simple, and concise and easy to digest… its a challenge, do your best
          ```

    - **video description**
       ```plaintext
      🔥  Need help getting started in your job search? Start here: [https://stan.store/farahsharghi/p/get](https://stan.store/farahsharghi/p/get)...
    
      In this video, you'll discover eight insider secrets from an ex-Google recruiter that recruiters typically keep under wraps. These secrets will help you understand what recruiters are really looking for in candidates, from crafting the perfect resume to acing the interview process. You'll learn how to stand out in a competitive job market and effectively communicate your value to potential employers. By the end of the video, you'll be equipped with strategies to enhance your job search and increase your chances of landing your dream job.
    
      I've worked as a technical recruiter for top companies like Google, Lyft, Uber, TikTok, and The New York Times, and I've reviewed hundreds of thousands of resumes. I'll show you how to avoid common mistakes and so you can land interview and your dream job.
    
      Introduction and Overview - 00:00
    
      Lesson 1 - 00:26
          * **Summary:** Recruiters give resumes a quick glance looking for clear communication of value to the business. Use a professional, single-column format (like Google Docs templates) for Applicant Tracking Systems (ATS). Don't include a headshot for US jobs. Use a clear, readable font (e.g., Arial, Calibri) and avoid fancy designs. ATS may misread complex formats. Use a single-column layout for ATS compatibility. Recruiters see many resumes daily, and ~75% of applicants aren't qualified. 
    
      Lesson 2 - 01:20
          * **Summary:** Use single-column resumes for ATS compatibility. Recruiters see many resumes daily, and ~75% of applicants aren't qualified. Companies seek the right fit. Some (e.g., government contractors) require 100% match for minimum qualifications due to OFCCP rules. Clearly show you're the best candidate.
    
      Lesson 3 - 02:42
          * **Summary:** Recruiters handle many roles (10-20) and get hundreds/thousands of resumes daily. Around 75% of applicants aren't qualified. Companies prioritize finding the right fit. OFCCP rules require meeting 100% of basic qualifications for certain roles and mandate human review of all resumes. Quick rejections often mean qualifications weren't met. Clearly demonstrate suitability; recruiters won't interpret unclear resumes.
    
      Lesson 4 - 05:00
          * **Summary:** Interview delays might happen if recruiters, hiring managers, or interviewers are busy/out of office, or if the company is fast-tracking another candidate they perceive as a better fit.
    
      Lesson 5 - 07:10
          * **Summary:** Understand what's negotiable in salary for both you and the company. Interview performance impacts placement within the salary band. A sign-on bonus might be negotiable if you can show quick productivity. Talk to the recruiter about negotiable aspects.
    
      Lesson 6 - 07:59
          * **Summary:** Focuses on salary negotiation. Understand negotiable compensation aspects. Interview performance affects your salary band position. Consider negotiating alternatives like sign-on bonuses if you can ramp up quickly. Clarify negotiable points with the recruiter.
    
      Lesson 7 - 09:32
          * **Summary:** Professionalism and attention to detail matter throughout the interview process. First impressions count; companies value detail-oriented candidates. Use polite language and be kind. Cultural fit (like Google's "Googliness") is important. Research company culture to show you're a good match.
    
      Lesson 8 - 11:25
          * **Summary:** When asked about salary expectations, it's a genuine question to align with the budget. Mention your research on their compensation philosophy and ask for the average offer or the salary band. Hiring managers often want to offer competitively.
    
      Bonus Tip - 13:11
          * **Summary:** Recruiters act as "professional matchmakers", comparing resumes to hiring manager needs from an "intake meeting" using "pattern matching". If there's a paper match, they talk to the candidate to confirm suitability and gather more "puzzle pieces". (Jokingly called "professional stalkers").
      ```

- [Asian guy - Google’s NEW Prompting Guide is Incredible!](https://www.youtube.com/watch?v=o64Mv-ArFDI)
    - **takeaways**
        - This video covers five AI prompting tips from a Google guide to help write better prompts for tools like
          ChatGPT and Google Gemini:
            - The Power of Three [00:27]: Ask for three variations in your initial prompt instead of just one. This can
              help spark new ideas and directions.
            - Multi-Step Workflows [02:16]: For large tasks, break them down into smaller, sequential prompts. This can
              lead to better results and fewer errors.
            - Template Timesavers [03:46]: Think about common tasks related to your role and generate prompt templates
              for them to save time.
            - Top-Down Competitive Analysis [05:44]: Begin with broad prompts to get an overview of a topic, then use
              more specific prompts to find actionable details.
            - Google Docs to Supercharge Your PDFs [07:42]: Upload PDFs to Google Docs to interact with them using
              Gemini. This allows you to summarize the PDF content or create presentations from it.
    - **video description**
      ```plain
      Grab my AI Toolkit for free: https://academy.jeffsu.org/ai-toolkit...
      
      Unleash the power of AI chatbots like #ChatGPT and #GoogleGemini with Google's latest guide to writing great #prompts!
      
      In this video, I share 5 actionable tips to write better prompts and get the most out of your AI interactions.
      
      Discover how to use multi-step workflows, time-saving templates, and top-down competitive analysis to generate high-quality content and valuable insights. 
      
      TIMESTAMPS
      00:00 Google’s New AI Prompting Guide
      00:25 The Power of Three
      02:13 Multi-Step Workflows
      03:47 Template Time-Savers
      05:45 Top-Down Competitive Analysis
      07:47 Supercharge Your PDFs 
      
      RESOURCES MENTIONED
      Download Google’s Prompt Guide: https://workspace.google.com/learning/content/gemini-prompt-guide
      🔩 Grab my free Workspace Toolkit: https://academy.jeffsu.org/
      Copy prompts from this video: https://www.jeffsu.org/googles-prompting-guide-5-things-to-know/
      ```

- [Asian guy - Write an Amazing Cover Letter: 3 Golden Rules (Template included)](https://www.youtube.com/watch?v=NUhDP30IRKk)
    - **takeaways**
        - This video gives advice on writing effective cover letters, based on one that helped someone get multiple
          offers from top firms. Key points include:
            - Hook the Reader [01:20]: Start with a connection to the company. You can find connections by:
            - Talking to people in the industry [01:56].
            - Researching software or products relevant to the role [02:45].
            - Make the connection relatable [03:14].
            - Be Purposeful [03:53]: Every sentence should have a clear purpose, focusing on relevant skills and
              achievements [04:49].
            - Use a Simple Template [05:36]: Use white space effectively for visual appeal. The video also shares a
              formatting tip for a unique header [06:26].
            - The main idea is that a good cover letter should evoke emotion and highlight what makes you unique to
              stand out [00:00].
    - **video description**
      ```plain
      Purchase the Editable Google Doc file: https://jeffsu.gumroad.com/l/DCulQJ

      📮 A good cover letter complements your resume by telling a story about why you deserve that first round interview. In this video, I share 3 Practical Tips on how to Write an Amazing Cover Letter, and even include a free sample template for you to download.

      For those of you who have never written a cover letter, or perhaps it's been a while since you wrote one, it could be difficult to start, since there are really no standardized template. A cover letter for an internship would be different than that for a full time job. And sometimes, the job application says submitting a cover letter is "optional" so should you even bother? 🤔

      I walk you through step-by-step on how to write an amazing cover letter and even provide an example cover letter for you to reference:

      1️⃣ Hook the reader in by mentioning a connection with the company
      2️⃣ Be purposeful about the information you include in your cover letter body paragraphs
      3️⃣ Use a simple cover letter template

      Now that you have an amazing cover letter, check out my video on how to write an incredible resume and best of luck in your job search 😁

      TIMESTAMPS
      00:00 Intro
      00:51 Hook the Reader
      03:48 Be Purposeful
      05:32 Use a Simple Template
      06:16 Secret Power Tip
      06:45 Template Download

      MY TOP 3 FAVORITE SOFTWARE
      ❎ CleanShot X - https://geni.us/cleanshotx
      ✍️ Skillshare - https://geni.us/skillshare-jeff
      📖 Readwise - https://readwise.io/jeffsu/

      RESOURCES I MENTION IN THE VIDEO
      Cover Letter Mistakes You MUST Avoid - Why Your Cover Letter Gets Rejected : https://www.youtube.com/watch?v=xudcyY06PiI  
      Best Informational Interview Questions to ask during Coffee Chat - Informational Interviews: Best Questi... : https://www.youtube.com/watch?v=4b2iagdHw8M
      Write an Incredible Resume video - https://www.youtube.com/watch?v=Tt08KmFfIYQ
      G2 Website - https://www.g2.com/
      Capterra Website - https://www.capterra.com/
      ```


- [Asian guy - Write an Incredible Resume: 5 Golden Rules!](https://www.youtube.com/watch?v=Tt08KmFfIYQ)
    - **takeaways**
        - This video discusses five key findings from a study analyzing over 125,000 resumes, offering data-backed
          advice for resume writing. Key Takeaways / Summary Points:
            - LinkedIn Profile: Including a link to your LinkedIn profile is associated with higher interview rates.
              This is particularly beneficial for entry-level positions
            - Keywords/Skills: Resumes often lack important keywords found in job descriptions, especially soft skills.
              It's recommended to use tools (like word clouds) to identify relevant keywords and ensure a mix of both
              hard and soft skills are included
            - Measurable Results: Using quantifiable metrics and data points to demonstrate achievements significantly
              improves a resume's impact
            - Resume Length: Data suggests the most effective resume length is between 475 and 600 words.
            - Fluff/Buzzwords: Avoid using generic, overused buzzwords and clichés (e.g., "team player," "hard worker")
              that don't provide concrete value or evidence.
            - The video emphasizes that while these tips might seem basic, the study's data underscores their importance
              for crafting an effective resume that gets noticed.

    - **video description**
      ```plain
      🌟 Most resume advice from the internet is subjective since there is no "one-size-fits-all." But using Austin Belcak's analysis of 125,484 resumes, I share 5 Golden Rules on how to Write an Incredible Resume in 2021.
      
      Whether you're making a resume for your first job, or improving upon a good resume you already have been using for years, it's very important to get the basics principles right before moving on to fancier resume writing tips.
      
      🤔 So how do write a resume or make a strong CV? Make sure your LinkedIn profile is added to your resume, match relevant keywords by using a Word Cloud on the job description, include measurable results to differentiate yourself from others, keep your resume between 475-600 words in length, and avoid buzzwords at all costs! Who knew writing a good resume would be so simple? 😉
      
      All jokes aside, this video contains practical resume writing tips following each one of Austin's findings, and I walk you through each of the 5 Golden Rules so you know the do's and dont's about writing a resume 💯
      
      TIMESTAMPS
      00:00 Intro
      00:57 Quick Disclaimer
      01:06 The 5 Key Learnings
      01:59 Add LinkedIn Profile
      03:21 Include the "Right" Keywords
      04:41 Add Measurable Results
      06:02 The Right Length
      07:05 Buzzwords and Clichés
      07:55 Quick Summary
      
      MY TOP 3 FAVORITE SOFTWARE
      ❎ CleanShot X - https://geni.us/cleanshotx
      ✍️ Skillshare - https://geni.us/skillshare-jeff
      📖 Readwise - https://readwise.io/jeffsu/
      
      RESOURCES I MENTION IN THE VIDEO
      My LinkedIn Tips & Tricks Playlist -    • Top 5 LinkedIn Profile Tips!  
      Austin's Study - https://cultivatedculture.com/resume-...
      Udemy Report - https://business.udemy.com/resources/...
      CNBC Summary of Soft Skills - https://www.cnbc.com/2019/11/21/10-to...
      ```

- [Asian guy - Why Your Cover Letter Gets Rejected (5 MISTAKES TO AVOID)](https://www.youtube.com/watch?v=xudcyY06PiI)
    - **takeaways**
        - This video outlines 5 common cover letter mistakes and provides advice on how to correct them:
            - Mistake: Not addressing a specific person [00:22].
            - Takeaway: Avoid generic greetings. Use LinkedIn to find the hiring manager or a senior team member to
              address the letter to [00:34].
            - Mistake: Grammar mistakes and typos [01:32].
            - Takeaway: Ensure error-free writing, as it shows attention to detail. Use tools like ChatGPT for
              proofreading [02:10].
            - Mistake: Not spending enough time on the hook [02:38].
            - Takeaway: Dedicate significant effort (around 50% of your time) to the first paragraph to make it engaging
              and focused on the employer, not just yourself [02:44].
            - Mistake: Not including tangible results [04:29].
            - Takeaway: Don't just list responsibilities. Quantify your accomplishments with numbers and measurable
              metrics to show your impact [04:41].
            - Mistake: Being unaware of cover letter "hygiene" [05:52].
            - Takeaway: Avoid including your full street address, using excessive industry jargon, and making the letter
              too long [05:56].
            - Bonus Mistake: Creating your cover letter from scratch [06:24].
            - Takeaway: Use examples (the video links to one) as a guide [06:31].
            - The video also notes that networking is important, as cover letters often get more attention after a
              personal connection is established [06:41].
    - **video description**
      ```plain 
      In this video, we go over what makes a successful cover letter by exploring common mistakes and providing practical solutions. 
  
      Using my own cover letter as an example, I reveal the five key mistakes I made back in the day that led to multiple rejections, and how you can avoid these pitfalls.
          
      Mistake 1️⃣: A generic or impersonal address can immediately signal a lack of research
          
      Mistake 2️⃣: Grammatical errors and typos can quickly tank your application
          
      Mistake 3️⃣: A weak introduction can cost you the reader's attention. Discover how to write a compelling hook by addressing a relatable pain point for the company and positioning yourself as a solution
          
      Mistake 4️⃣: Vague descriptions don't impress; quantify your achievements instead
          
      Mistake 5️⃣: Proper cover letter hygiene matters. Avoid oversharing personal information, using industry-specific jargon, and writing excessively long letters
  
      TIMESTAMPS
        00:00 How NOT to Write a Cover Letter
        00:19 Not Addressing to an Actual Person
        01:29 Grammar Mistakes and Typos
        02:36 Not Spending Enough Time on the Hook
        04:25 Not Including Tangible Results
        05:47 Being Unaware of Cover Letter Hygiene
        06:28 Bonus Mistake
        06:45 Why Networking is Important for Cover Letters
          
      RESOURCES I MENTION IN THE VIDEO
        Use LinkedIn to network effectively • LinkedIn Tips & Tricks (https://www.youtube.com/playlist?list=PLo-kPya_Ww2zqOZVXMNQCJeTNAaan8GcW)  
        Austin’s Cover Letter article - (https://cultivatedculture.com/cover-letter/)
        Ali Abdaal’s resume video -    • I Read 2,216 Resumes. Here’s How You ...  (https://www.youtube.com/watch?v=MqXjqOy-TA8&t=1398s)
        Cover Letter deal-break article - (https://cultivatedculture.com/cover-letter-deal-breakers/)
        ```

---

## ROUGH ARCHITECTURE IM ENVISIONING ##

the mvp/basic architecture im envisioning (feel free to share your thoughts and concerns and change anything necessary,
if u feel ur way will accomplish our goal faster, im trying to get this rigged up within 1-2 days max (i code fast lol):

- user resource (will have just one user for now, me)
    - fields like:
        - first name
        - last name
        - date of birth (i like the idea of putting this potentially)
        - profile_photo_url (link to their profile photo that will be used in the resume and cover letter)
        - email
        - phone number
        - location (city, state, country) - not full address since details are private/they might discriminate against
          me
        - resume URL (link to their/my resume)
        - cover letter URL (link to their cover letter)
        - field for their/my LinkedIn profile URL (nullable)
        - maybe a field for their GitHub profile URL
        - maybe a field for their personal website URL
        - maybe a field for their portfolio URL

- job post resource / things maybe on a separate resource for generation sessions:
    - fields like:
        - company name
        - job title
        - job description
        - job post URL
        - job post created date

        - computed fields like:
            - job post age in days (computed from created date)
            - job post expiration date (computed from created date)
            - job post location (remote, in office, hybrid)
            - job post required hard_skills,soft_skills,experience,education,etc (array of strings or text idk best way
              to store this)
            - job post preferred hard_skills,soft_skills,experience,education,etc (array of strings or text idk best way
              to store this)
            - any extra necessary computed fields that we may need to do when generating the resume and cover letter and
              adhereing to the rules we set up
        - job post content HTML (for any extra logic we may need to do when generating the resume and cover letter)
        - job post content markdown (just easier format than html, shorter and better for using with chatgpt openai APIs
          as reference/context for any extra logic we may need to do when generating the resume and cover letter)
        - resume_min_words (default: 450) - this is the minimum number of words that should be in the resume
        - resume_max_words (default: 850?) - this is the maximum number of words that should be in the resume
        - cover_letter_min_words (default: 450) - this is the minimum number of words that should be in the cover letter
        - cover_letter_max_words (default: 750) - this is the maximum number of words that should be in the cover letter
        - resume_min_pages (default: 1) - this is the minimum number of pages that should be in the resume
        - resume_max_pages (default: 2) - this is the maximum number of pages that should be in the resume
        - cover_letter_min_pages (default: 1) - this is the minimum number of pages that should be in the cover letter
        - cover_letter_max_pages (default: 1) - this is the maximum number of pages that should be in the cover letter
        - maybe things that i like about the job post and role specifically
        - maybe things that i don't like about the job post and role specifically
        - maybe things that i like about the company specifically
        - maybe things that i don't like about the company specifically
        - if id be working remote or in office, or partially remote (incase they have a hybrid model)
        - if im open to traveling for the job (default: yes)
        - job post listed salary range min and max columns/fields (nullable BUT typically its there) - this is the
          salary range listed on the job post
        - expected pay min and max columns/fields if I got hired what the job pay range is as listed on the job post
        - minimum salary amount I would accept for this job (default: null) - this is the minimum amount of money I
          would accept for this job, if nullable then it would be the same as the expected pay min and max
          columns/fields
        - position level (default: mid-level) can be: entry-level, mid-level, senior-advanced, director, manager, etc
        - job post type (default: full-time) can be: full-time, part-time, contract, internship, etc
        - what my ideal start date would be like within 30 days Etc
        - what my "favorite position" for this job within the company... if im planning on applying to multiple jobs at
          same company...aka is it my #1 omg i want this job so bad position at a specific company so those are my top 1
          number 1 favorite or number 2 number 3 Etc
        - maybe something whether or not it's our first time applying for any job position at the particular company the
          job is for
        - ANY and every possible column/field/input data for a particular job that way we may need to handle and ENSURE
          we generate the perfect resume and cover letter possible to go along with that job post when applying.




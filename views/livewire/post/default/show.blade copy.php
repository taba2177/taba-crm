<x-layouts.main :title="$post->title">
  <x-banner :title="$post->title" :post="$post" backgroundImage="{{ asset('/assets/img/breadcrumb/breadcrumb-bg.jpg') }}">
    <x-breadcrumbs :items="[
          ['label' => 'الرئيسية', 'url' => route('home')],
          ['label' => $post->postCategory->name, 'url' => route('dynamic.route', [$post->postCategory->slug])],
          ['label' => $post->title, 'url' => '']
          ]" />
  </x-banner>
  <section id="blogs">
    <div class="py-60px md:py-20 lg:py-100px xl:py-30 dark:bg-black-color">
      <div class="container">
        <div class="lg:grid lg:gap-6 lg:grid-cols-12">
          <!-- blogs details -->
          <div class="lg:col-start-1 lg:col-span-8">
            <article class="group relative flex flex-col items-center wow fadeInUp" data-wow-delay=".3s">
              <div class="rounded-lg relative overflow-hidden">
                @if ($post->image)
                <div class="rounded-t-lg overflow-hidden">
                  <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}" class="w-full" />
                </div>
                @endif
                {{-- <a href="#"
                                class="text-size-13 uppercase px-15px py-10px rounded-50px leading-1 absolute top-[15px] right-[15px] text-white-color bg-gradient-secondary-2 bg-200 hover:bg-100">Tutorial</a> --}}

                <div class="pt-25px md:pt-30px -mt-10px">
                  <div class="transition-all duration-500">
                    <div class="relative z-0">
                      <div class="relative z-10 px-15px md:px-25px lg:px-10">
                        <ul class="flex gap-15px md:gap-25px items-center mb-15px md:mb-5">
                          <li>
                            <i class="fa-light fa-user mr-1 text-primary-color transition-all duration-500"></i>
                            <a href="#" class="text-primary-color dark:text-white-color hover:text-primary-color transition-all duration-500">By
                              {{ $post->user->name }}</a>
                          </li>
                          <li class="text-primary-color dark:text-white-color transition-all duration-500">
                            <i class="fa-light fa-calendar-days mr-1 text-primary-color"></i>
                            {{ $post->published_at->translatedFormat('j F، Y', 'ar') }}
                          </li>
                          {{-- <li>
                                                    <i class="fa-light fa-comments mr-1 text-primary-color transition-all duration-500"></i>
                                                    <a href="#"
                                                        class="text-primary-color dark:text-white-color hover:text-primary-color transition-all duration-500">Comment
                                                        (3)</a>
                                                </li> --}}
                        </ul>
                        <h3 class="mb-15px md:mb-5">
                          <span class="text-primary-color dark:text-white-color capitalize relative z-0 text-size-22 md:text-3xl font-bold">
                            {{ $post->title }}
                          </span>
                        </h3>
                        <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                          {{ $post->excerpt }}
                        </p>
                        {{-- <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                                                As businesses unlock growth opportunities in the
                                                digital age, harnessing the power of cloud computing
                                                has become essential. Amazon Web Services (AWS)
                                                offers the AWS SaaS Competency Partner program,
                                                recognizing companies with exceptional expertise in
                                                delivering Software-as-a-Service solutions on the AWS
                                                platform.
                                            </p>
                                            <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                                                In this blog, we will delve into the strategies, best
                                                practices, and key factors that accelerated our
                                                business growth and earned us the prestigious AWS SaaS
                                                Competency Partner status.
                                            </p> --}}
                        <!-- blog quote -->
                        {{-- <div class="rounded-lg relative overflow-hidden bg-primary-color-light">
                                                <blockquote
                                                    class="py-25px px-15px md:p-30px relative block before:content-['\f10e'] before:block before:text-size-40 before:leading-none before:font-fontawesome before:font-light before:relative before:mb-3">
                                                    <div class="transition-all duration-500">
                                                        <div class="relative z-0">
                                                            <div class="relative z-10">
                                                                <p class="text-white-color mb-15px">
                                                                    “Welcome to our blog, where we celebrate our
                                                                    achievement as an AWS SaaS Competency Partner
                                                                    and share insights on how we accomplished this
                                                                    significant milestone. As businesses unlock
                                                                    growth opportunities in the digital age,
                                                                    harnessing the power of cloud computing has
                                                                    become essential. Amazon Web Services (AWS)
                                                                    offers the AWS SaaS Competency.”
                                                                </p>
                                                                <p class="text-white-color mb-2">
                                                                    <cite
                                                                        class="text-xl relative inline-block before:inline-block before:w-[35px] before:h-0.5 before:bg-primary-color before:rounded-[2px] before:relative before:-top-[6px] before:mr-15px">Silvester
                                                                        Scott</cite>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </blockquote>
                                            </div> --}}
                        <!-- role of technology -->
                        <div class="px-15px md:px-25px lg:px-10">
                          {{-- <h3 class="mb-15px mt-5 md:mt-30px">
                                                    <span
                                                        class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-22 md:text-2xl font-bold">
                                                        The Role of Technology in Modern Logistics
                                                        Management
                                                    </span>
                                                </h3> --}}
                          <div class="prose dark:prose-invert max-w-none text-primary-color-light dark:text-white-color">
                            @foreach ($post->blocks as $block)
                            @switch($block->type)
                            @case('markdown')
                            @markdom($block->data->content)
                            @break
                            @case('figure')
                            <x-figure :image="$block->data->image" :alt="$block->data->alt" :caption="$block->data->caption" />
                            @break
                            @default
                            @dump($block)
                            @endswitch
                            @endforeach
                          </div>
                          {{-- <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                                                    Welcome to our blog, where we celebrate our
                                                    achievement as an AWS SaaS Competency Partner and
                                                    share insights on how we accomplished this
                                                    significant milestone.
                                                </p>
                                                <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                                                    As businesses unlock growth opportunities in the
                                                    digital age, harnessing the power of cloud computing
                                                    has become essential. Amazon Web Services (AWS)
                                                    offers the AWS SaaS Competency Partner program,
                                                    recognizing companies with exceptional expertise in
                                                    delivering Software-as-a-Service solutions on the AWS
                                                    platform.
                                                </p>
                                                <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                                                    In this blog, we will delve into the strategies, best
                                                    practices, and key factors that accelerated our
                                                    business growth and earned us the prestigious AWS SaaS
                                                    Competency Partner status.
                                                </p>
                                                <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                                                    Explore the transformative impact of technology on
                                                    logistics management. Discuss how technologies like
                                                    IoT, AI, and blockchain are reshaping the industry and
                                                    improving efficiency.
                                                </p> --}}
                        </div>

                        <!-- keypoint -->
                        {{-- <div class="mb-5">
                                                <h5 class="mb-10px mt-5 md:mt-30px">
                                                    <span
                                                        class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-15 md:text-base lg:text-lg font-bold">
                                                        Key Points
                                                    </span>
                                                </h5>
                                                <ul>
                                                    <li
                                                        class="pl-25px mb-10px relative before:content-['\f058'] before:font-fontawesome before:absolute before:left-0 before:top-0 before:text-primary-color before:font-bold">
                                                        <span class="text-primary-color-light dark:text-white-color">
                                                            IoT and Real-Time Tracking</span>
                                                    </li>
                                                    <li
                                                        class="pl-25px mb-10px relative before:content-['\f058'] before:font-fontawesome before:absolute before:left-0 before:top-0 before:text-primary-color before:font-bold">
                                                        <span class="text-primary-color-light dark:text-white-color">
                                                            Artificial Intelligence in Route
                                                            Optimization and Predictive Analytics</span>
                                                    </li>
                                                    <li
                                                        class="pl-25px mb-10px relative before:content-['\f058'] before:font-fontawesome before:absolute before:left-0 before:top-0 before:text-primary-color before:font-bold">
                                                        <span class="text-primary-color-light dark:text-white-color">
                                                            Blockchain for Enhanced Transparency and
                                                            Security</span>
                                                    </li>
                                                    <li
                                                        class="pl-25px relative before:content-['\f058'] before:font-fontawesome before:absolute before:left-0 before:top-0 before:text-primary-color before:font-bold">
                                                        <span class="text-primary-color-light dark:text-white-color">
                                                            Warehouse Automation and Robotics</span>
                                                    </li>
                                                </ul>
                                            </div> --}}
                        <!-- conclusion -->
                        {{-- <div>
                                                <h3 class="mb-15px mt-5 md:mt-30px">
                                                    <span
                                                        class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-22 md:text-2xl font-bold">
                                                        Conclusion
                                                    </span>
                                                </h3>
                                                <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                                                    Emphasize the long-term benefits of integrating
                                                    sustainable practices into logistics operations, both
                                                    for the planet and a company's reputation.
                                                </p>
                                                <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                                                    These outlines can be expanded into comprehensive blog
                                                    posts, each providing valuable insights and
                                                    information on the respective topics.
                                                </p>
                                            </div> --}}
                        <!-- tags and social -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </article>
            <div class="flex gap-5 md:gap-x-30px flex-wrap md:flex-nowrap py-30px mt-50px border-y border-border-color dark:border-gray-color-3 px-15px md:px-25px lg:px-10">
              <!-- tags -->
              <div class="flex gap-x-5 md:gap-x-30px">
                <div>
                  <h3>
                    <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-22 md:text-2xl font-bold">
                      Tags:
                    </span>
                  </h3>
                </div>
                <div>
                  <ul class="flex flex-wrap gap-10px items-center">
                    @foreach ($post->tags as $tag)
                    <li class="font-medium">
                      <a href="#" class="py-11px px-15px bg-cream-light-color dark:bg-primary-color-light hover:bg-primary-color dark:hover:bg-primary-color text-primary-color hover:text-white-color dark:text-white-color transition-all duration-500 rounded-50px leading-1">{{ $tag->name }}</a>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <!-- socials -->
              <div>
                <ul class="flex gap-x-5">
                  <li>
                    <a href="https://www.facebook.com" class="text-primary-color dark:text-white-color border border-primary-color hover:bg-primary-color w-10 h-10 rounded-full flex items-center justify-center overflow-hidden"><i class="fa-brands fa-facebook-f"></i></a>
                  </li>
                  <li>
                    <a href="https://x.com" class="text-primary-color dark:text-white-color border border-primary-color hover:bg-primary-color w-10 h-10 rounded-full flex items-center justify-center overflow-hidden"><i class="fa-brands fa-x-twitter"></i></a>
                  </li>
                  <li>
                    <a href="https://www.linkedin.com" class="text-primary-color dark:text-white-color border border-primary-color hover:bg-primary-color w-10 h-10 rounded-full flex items-center justify-center overflow-hidden"><i class="fa-brands fa-linkedin-in"></i></a>
                  </li>
                  <li>
                    <a href="https://www.pinterest.com" class="text-primary-color dark:text-white-color border border-primary-color hover:bg-primary-color w-10 h-10 rounded-full flex items-center justify-center overflow-hidden"><i class="fa-brands fa-pinterest-p"></i></a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- prev next blog -->
            <div class="py-30px border-b border-border-color dark:border-gray-color-3">
              <div class="flex flex-wrap md:flex-nowrap md:grid gap-5 xl:gap-30px md:grid-cols-2">
                <!-- prev blog -->
                <div>
                  <div class="group flex gap-x-5 max-w-355px md:max-w-full w-full relative overflow-hidden bg-cream-light-color dark:bg-primary-color-light px-5 py-30px md:px-25px md:py-35px">
                    <div>
                      <a href="#" class="overflow-hidden w-85px h-85px">
                        <img src="./assets/img/blog/1.jpg" alt="" class="w-85px h-85px" /></a>
                    </div>
                    <div>
                      <div class="relative z-10">
                        <button class="uppercase text-primary-color mb-1.5 inline-flex gap-2 items-center">
                          <i class="fa-regular fa-angle-double-left"></i>
                          <span> previous</span>
                        </button>
                        <h3>
                          <a href="#" class="text-primary-color-light dark:text-white-color hover:text-primary-color dark:hover:text-primary-color capitalize relative z-0 text-lg font-medium">
                            Building a Real Estate Website Tips and Ideas
                          </a>
                        </h3>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- next blog -->
                <div class="ml-auto md:ml-0">
                  <div class="group flex items-start gap-x-5 max-w-355px md:max-w-full w-full relative overflow-hidden bg-cream-light-color dark:bg-primary-color-light px-5 py-30px md:px-25px md:py-35px">
                    <div>
                      <div class="relative z-10 flex flex-col items-end">
                        <button class="uppercase text-primary-color mb-1.5 inline-flex gap-2 items-center">
                          <span> Next</span>
                          <i class="fa-regular fa-angle-double-right"></i>
                        </button>
                        <h3>
                          <a href="#" class="text-primary-color-light dark:text-white-color hover:text-primary-color dark:hover:text-primary-color capitalize relative z-0 text-lg font-medium text-end">
                            Architecture Is Not Based On Concrete And Steel
                          </a>
                        </h3>
                      </div>
                    </div>
                    <div>
                      <a href="#" class="overflow-hidden w-85px h-85px">
                        <img src="./assets/img/blog/2.jpg" alt="" class="w-85px h-85px" /></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- comments -->

            {{-- <div class="mt-50px">
                        <h3
                            class="mb-30px pb-3 relative after:w-60px after:h-0.5 after:bg-primary-color after:absolute after:bottom-0 after:left-0 z-1">
                            <span
                                class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-22 md:text-3xl font-bold">
                                3 Comments
                            </span>
                        </h3>
                        <!-- comments -->
                        <div class="flex flex-col gap-y-30px">
                            <!-- comment 1  -->
                            <div
                                class="group flex flex-col md:flex-row gap-5 relative overflow-hidden pb-30px border-b border-border-color dark:border-gray-color-3">
                                <div>
                                    <a href="#" class="overflow-hidden w-30">
                                        <img src="./assets/img/blog/user-1.jpg" alt="" class="w-full" /></a>
                                </div>
                                <div>
                                    <div class="relative z-10">
                                        <h3>
                                            <a href="#"
                                                class="text-primary-color-light dark:text-white-color hover:text-white-color dark:hover:text-primary-color capitalize relative z-0 text-lg md:text-size-22 font-bold mb-1.5">
                                                Jane Doe
                                            </a>
                                        </h3>
                                        <p class="text-sm text-primary-color-light dark:text-white-color mb-15px">
                                            January 3, 2024
                                        </p>
                                        <p class="text-primary-color-light dark:text-body-color mb-25px">
                                            England dotted with a lush, green landscape, rustic
                                            villages and throbbing with humanity. South Asian
                                            country that has plenty to offer to visitors with
                                            its diverse wildlife.
                                        </p>
                                        <div>
                                            <a href="#"
                                                class="px-5 py-1 text-primary-color hover:text-white-color border border-primary-color hover:bg-primary-color inline-block">Reply</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- nested -->
                            <div class="pl-30px flex flex-col gap-y-30px">
                                <!-- comment 2  -->
                                <div
                                    class="group flex flex-col md:flex-row gap-5 relative overflow-hidden pb-30px border-b border-border-color dark:border-gray-color-3">
                                    <div>
                                        <a href="#" class="overflow-hidden w-30">
                                            <img src="./assets/img/blog/user-2.jpg" alt="" class="w-full" /></a>
                                    </div>
                                    <div>
                                        <div class="relative z-10">
                                            <h3>
                                                <a href="#"
                                                    class="text-primary-color-light dark:text-white-color hover:text-white-color dark:hover:text-primary-color capitalize relative z-0 text-lg md:text-size-22 font-bold mb-1.5">
                                                    Fred Bloggs
                                                </a>
                                            </h3>
                                            <p class="text-sm text-primary-color-light dark:text-white-color mb-15px">
                                                February 3, 2024
                                            </p>
                                            <p class="text-primary-color-light dark:text-body-color mb-25px">
                                                It is a long established fact that a reader will
                                                be distracted by the readable content of a page
                                                when looking at its layout. The point of using
                                                Lorem Ipsum is that it has a more-or-less normal
                                                distribution of letters, as opposed to using
                                                'Content here making it look like readable
                                                English.
                                            </p>
                                            <div>
                                                <a href="#"
                                                    class="px-5 py-1 text-primary-color hover:text-white-color border border-primary-color hover:bg-primary-color inline-block">Reply</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- comment 3  -->
                                <div
                                    class="group flex flex-col md:flex-row gap-5 relative overflow-hidden pb-30px border-b border-border-color dark:border-gray-color-3">
                                    <div>
                                        <a href="#" class="overflow-hidden w-30">
                                            <img src="./assets/img/blog/user-3.jpg" alt="" class="w-full" /></a>
                                    </div>
                                    <div>
                                        <div class="relative z-10">
                                            <h3>
                                                <a href="#"
                                                    class="text-primary-color-light dark:text-white-color hover:text-white-color dark:hover:text-primary-color capitalize relative z-0 text-lg md:text-size-22 font-bold mb-1.5">
                                                Jane Bloggs
                                                </a>
                                            </h3>
                                            <p class="text-sm text-primary-color-light dark:text-white-color mb-15px">
                                                January 13, 2024
                                            </p>
                                            <p class="text-primary-color-light dark:text-body-color mb-25px">
                                                But I must explain to you how all this mistaken
                                                idea of denouncing pleasure and praising pain was
                                                born and I will give you a complete account
                                            </p>
                                            <div>
                                                <a href="#"
                                                    class="px-5 py-1 text-primary-color hover:text-white-color border border-primary-color hover:bg-primary-color inline-block">Reply</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- comment 4  -->
                            <div
                                class="group flex flex-col md:flex-row gap-5 relative overflow-hidden pb-30px border-b border-border-color dark:border-gray-color-3">
                                <div>
                                    <a href="#" class="overflow-hidden w-30">
                                        <img src="./assets/img/blog/user-4.jpg" alt="" class="w-full" /></a>
                                </div>
                                <div>
                                    <div class="relative z-10">
                                        <h3>
                                            <a href="#"
                                                class="text-primary-color-light dark:text-white-color hover:text-white-color dark:hover:text-primary-color capitalize relative z-0 text-lg md:text-size-22 font-bold mb-1.5">
                                                Themedemos
                                            </a>
                                        </h3>
                                        <p class="text-sm text-primary-color-light dark:text-white-color mb-15px">
                                            January 20, 2024
                                        </p>
                                        <p class="text-primary-color-light dark:text-body-color mb-25px">
                                            There are many variations of passages of Lorem Ipsum
                                            available, but the majority have suffered alteration
                                            in some form, by injected humour, or randomised
                                            words which don't look even slightly believable. If
                                            you are going to use a passage you need to be sure
                                            there isn't anything embarrassing hidden in the
                                            middle of text. All the
                                        </p>
                                        <div>
                                            <a href="#"
                                                class="px-5 py-1 text-primary-color hover:text-white-color border border-primary-color hover:bg-primary-color inline-block">Reply</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
            <!-- comment form -->

            {{-- <div class="mt-50px">
                        <h3
                            class="mb-30px pb-3 relative after:w-60px after:h-0.5 after:bg-primary-color after:absolute after:bottom-0 after:left-0 z-1">
                            <span
                                class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-22 md:text-3xl font-bold">
                                Leave a Reply
                            </span>
                        </h3>
                        <p class="text-primary-color-light dark:text-body-color mb-4">
                            I design and code beautifully simple things and i love what
                            i do. Just simple like that!
                        </p>
                        <!-- form -->
                        <form>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                                <!--  name -->
                                <div>
                                    <input type="text" placeholder="Enter Name"
                                        class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-primary-color-light focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                                </div>
                                <!-- email -->
                                <div>
                                    <input type="email" placeholder="Enter Email"
                                        class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-primary-color-light focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                                </div>

                                <div class="sm:col-start-1 sm:col-span-2">
                                    <div>
                                        <input type="text" placeholder="Enter Website"
                                            class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-primary-color-light focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                                    </div>
                                </div>
                                <div class="sm:col-start-1 sm:col-span-2">
                                    <textarea cols="1" rows="10" placeholder="Write Your Comments"
                                        class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-primary-color-light focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1"></textarea>
                                </div>
                                <div class="sm:col-start-1 sm:col-span-2 -mt-1">
                                    <label for="agreetocomment"
                                        class="text-primary-color-light dark:text-body-color flex items-center gap-2 mb-1">
                                        <input type="checkbox" id="agreetocomment" />
                                        <span>
                                            Save my name, email, and website in this browser for
                                            the next time I comment.
                                        </span>
                                    </label>
                                </div>
                                <div class="sm:col-start-1 sm:col-span-2">
                                    <button type="submit"
                                        class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> --}}
          </div>
          <!-- sidebar -->
          <div class="sidebar lg:col-start-9 lg:col-span-4 pt-10 lg:pt-0 mt-60px lg:mt-0 border-t border-gray-color-3 lg:border-none">
            <div class="flex flex-col gap-30px">
              <!-- search -->
              <div class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg wow fadeInUp" data-wow-delay=".3s">
                <form>
                  <div class="flex">
                    <!-- first name -->
                    <div class="flex-grow">
                      <input type="search" placeholder="Search..." class="text-white-color w-full pl-5 py-4 border border-gray-color-3 bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-l-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                    </div>
                    <div class="min-h-full">
                      <button type="submit" class="w-60px h-full bg-primary-color hover:bg-seondary-color rounded-r-lg text-white-color text-xl inline-flex items-center justify-center transition-all duration-500">
                        <i class="fa-light fa-magnifying-glass"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- categories -->
              {{-- <div class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg wow fadeInUp" data-wow-delay=".3s">
                            <h3 class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                                Categories
                            </h3>
                            <!-- list -->

                            <ul>
                                @foreach ($categories as $category)
                                <li class="flex items-center justify-between gap-x-5 font-medium">
                                    <a href="{{ route('dynamic.route', $category->slug) }}" class="pb-2 md:pb-10px pt-0 text-primary-color-light dark:text-white-color hover:text-primary-color transition-all duration-500">{{ $category->name }}</a>
              <span class="text-primary-color">({{ $category->posts_count }})</span>
              </li>
              @endforeach
              </ul>
            </div> --}}
            <!-- recent blogs-->
            <div class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg wow fadeInUp" data-wow-delay=".3s">
              <h3 class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                Recent post
              </h3>

              <div class="flex flex-col gap-y-30px">
                @foreach ($relatedposts as $recentPost)
                <div class="group flex gap-x-5 relative overflow-hidden bg-cream-light-color dark:bg-primary-color-light">
                  <div>
                    <a href="{{ $recentPost->url }}" class="overflow-hidden w-20 h-20">
                      <img src="{{ $recentPost->image->url ?? '/assets/images/blog_img_1.jpg' }}" alt="{{ $recentPost->image->alt ?? $recentPost->title }}" class="w-20 h-20 group-hover:scale-110 transition-all duration-[.8s]" /></a>
                  </div>

                  <div>
                    <div class="transition-all duration-500">
                      <div class="relative z-0">
                        <div class="relative z-10">
                          <ul class="flex gap-15px md:gap-25px items-center mb-5px">
                            <li class="text-primary-color dark:text-white-color transition-all duration-500">
                              <i class="fa-light fa-calendar-days mr-1 text-primary-color"></i>
                              {{ $recentPost->published_at->translatedFormat('j F، Y', 'ar') }}
                            </li>
                            {{-- <li>
                                                            <i
                                                                class="fa-light fa-comments mr-1 text-primary-color transition-all duration-500"></i>
                                                            <a href="#"
                                                                class="text-primary-color dark:text-white-color hover:text-primary-color transition-all duration-500">
                                                                (3)</a>
                                                        </li> --}}
                          </ul>
                          <h3>
                            <a href="{{ $recentPost->url }}" class="text-primary-color-light dark:text-white-color hover:text-primary-color dark:hover:text-primary-color capitalize relative z-0 text-lg font-medium">
                              {{ $recentPost->title }}
                            </a>
                          </h3>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            <!-- tags -->
            <div class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg wow fadeInUp" data-wow-delay=".3s">
              <h3 class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                Popular tag
              </h3>
              <!-- list -->

              <ul class="flex flex-wrap gap-15px items-center">
                @foreach ($tags as $tag)
                <li class="font-medium">
                  <a href="#" class="py-10px px-15px hover:bg-primary-color text-primary-color hover:text-white-color dark:text-white-color border border-gray-color-2 hover:border-primary-color transition-all duration-500 rounded-full">{{ $tag->name }}</a>
                </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>
</x-layouts.main>

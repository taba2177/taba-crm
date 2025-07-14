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

                        </ul>
                        <h3 class="mb-15px md:mb-5">
                          <span class="text-primary-color dark:text-white-color capitalize relative z-0 text-size-22 md:text-3xl font-bold">
                            {{ $post->title }}
                          </span>
                        </h3>
                        <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                          {{ $post->excerpt }}
                        </p>

                        <!-- role of technology -->
                        <div class="px-15px md:px-25px lg:px-10">

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

                        </div>

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
                    @if($post->tags)

                    @foreach ($post->tags as $tag)
                    <li class="font-medium">
                      <a href="#" class="py-11px px-15px bg-cream-light-color dark:bg-primary-color-light hover:bg-primary-color dark:hover:bg-primary-color text-primary-color hover:text-white-color dark:text-white-color transition-all duration-500 rounded-50px leading-1">{{ $tag->name }}</a>
                    </li>
                    @endforeach
                    @endif

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
            <h3 class="mb-30px pb-3 relative after:w-60px after:h-0.5 after:bg-primary-color after:absolute after:bottom-0 after:left-0 z-1">
              <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-22 md:text-3xl font-bold">
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

                  <input type="text" placeholder="Enter Name" class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-primary-color-light focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                  </div>
                  <!-- email -->
                  <div>

                  <input type="email" placeholder="Enter Email" class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-primary-color-light focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                  </div>

                <div class="sm:col-start-1 sm:col-span-2">
                  <div>

                    <input type="text" placeholder="Enter Website" class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-primary-color-light focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                    </div>
                    </div>
                    <div class="sm:col-start-1 sm:col-span-2">

                  <textarea cols="1" rows="10" placeholder="Write Your Comments" class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-primary-color-light focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1"></textarea>
                  </div>
                  <div class="sm:col-start-1 sm:col-span-2 -mt-1">

                  <label for="agreetocomment" class="text-primary-color-light dark:text-body-color flex items-center gap-2 mb-1">
                    <input type="checkbox" id="agreetocomment" />
                    <span>
                      Save my name, email, and website in this browser for
                      the next time I comment.
                    </span>
                  </label>
                  </div>
                  <div class="sm:col-start-1 sm:col-span-2">

                  <button type="submit" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">
                    Post Comment
                  </button>
                  </div>
                  </div>
                  </form>

          </div>

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

            <!-- recent blogs-->
            <div class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg wow fadeInUp" data-wow-delay=".3s">
              <h3 class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                Recent post
              </h3>

            </div>
            <!-- tags -->
            <div class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg wow fadeInUp" data-wow-delay=".3s">
              <h3 class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                Popular tag
              </h3>
              <!-- list -->

              <ul class="flex flex-wrap gap-15px items-center">
                @if($post->tags)
                @foreach ($post->tags as $tag)

                <li class="font-medium">
                  <a href="#" class="py-10px px-15px hover:bg-primary-color text-primary-color hover:text-white-color dark:text-white-color border border-gray-color-2 hover:border-primary-color transition-all duration-500 rounded-full">{{ $tag->name }}</a>
                </li>
                @endforeach
                @endif

              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>
</x-layouts.main>

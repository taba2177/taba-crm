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
          <!-- service details -->
          <div class="lg:col-start-1 lg:col-span-8">
            <div class="group relative flex flex-col items-center wow fadeInUp" data-wow-delay=".3s">
              <div class="rounded-lg relative overflow-hidden">
                <div class="rounded-t-lg overflow-hidden">
                  @if ($post->image)
                  <img src="{{ $post->image?->url ?? $post->getRandomImage() }}" alt="{{ $post->title }}" class="w-full rounded" />
                  @endif
                </div>

                @if (Auth::check())
                <div class="border-t pt-4">
                  <a class="inline-flex items-center text-sm text-primary hover:text-primary" href="{{ $post->editUrl }}" title="edit">
                    <x-heroicon-s-pencil class="inline-block w-8 h-8 text-primary-500 mr-2" />
                    {{__('Edit Post')}}
                  </a>
                </div>
                @endif


                <div class="pt-30px md:pt-10">
                  <div class="transition-all duration-500">
                    <div class="relative z-0">
                      <div class="relative z-10">
                        {{-- <h3 class="mb-15px md:mb-5">
                          <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-40 lg:text-size-45 font-bold">
                            {{ $post->title }}
                        </span>
                        </h3> --}}

                        {{-- <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                          {{ $post->excerpt }}
                        </p> --}}
                        <div class="px-15px md:px-25px lg:px-10">
                          <x-portfolio.section>
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
                          </x-portfolio.section>
                        </div>
                        @if($post->homepage_section_component && $post->homepage_section_content)
                        <div class="px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                          @include('components.homepage.' . $post->homepage_section_component, ['content' => $post->homepage_section_content])
                        </div>
                        @endif
                        <!-- process -->

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- sidebar -->
          {{-- pt-50px lg:pt-0 mt-10 lg:mt-0 --}}
          <div class="sidebar lg:col-start-9 lg:col-span-4 h-min border-t border-border-color dark:border-gray-color-3 lg:border-none">
            <div class="flex flex-col gap-30px">
              <!-- All Services -->
              <div class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg wow fadeInUp" data-wow-delay=".3s">
                <h3 class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                  {{ __('Categories') }}
                </h3>
                <!-- list -->

                <ul class="sidebar-categories">
                  @php
                  //get categories from cache using cache
                  $categories = \App\Models\PostCategory::RegisterInHeader();

                  @endphp
                  @foreach ($categories as $category)
                  <li class="mb-2 {{ $post->postCategory->id == $category->id ? 'active' : ' ' }}">
                    <a href="{{route('dynamic.route', [$category->slug])}}" class="px-5 pt-15px pb-3 rounded-lg hover:bg-seondary-color text-primary-color-light dark:text-white-color hover:text-white-color transition-all duration-500 flex items-center justify-between gap-x-5 w-full group">
                      <span class="inline-flex gap-1 items-start">
                        <i class="flaticon-design mr-10px rtl:ml-10px rtl:mr-auto text-size-25 leading-1"></i>
                        {{$category->name}}</span>
                      <span class="text-primary-color-light dark:text-white-color group-hover:text-white-color leading-1 transition-none duration-500"><i class="fas fa-angle-right"></i></span>
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
              <!-- get in tuouch -->
              <div class="px-15px md:px-25px py-30px bg-cream-light-color dark:bg-primary-color-light rounded-lg wow fadeInUp" data-wow-delay=".3s">
                <form>
                  <h3 class="mb-25px text-primary-color dark:text-white-color uppercase relative z-0 text-size-lg md:text-xl font-bold">
                    {{ __('Get in Touch') }}
                  </h3>
                  <div class="flex flex-col gap-10px">
                    <!-- name -->
                    <div>
                      <input type="text" placeholder="{{ __('Name') }}" class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                    </div>
                    <!-- name -->
                    <div>
                      <input type="email" placeholder="{{ __('Email') }}" class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                    </div>
                    <div>
                      <textarea cols="1" rows="10" placeholder="{{ __('Your Message') }}" class="text-white-color w-full px-5 py-14px border border-gray-color-3 bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1"></textarea>
                    </div>
                    <div class="sm:col-start-1 sm:col-span-2">
                      <button type="submit" class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300 w-full text-center">
                        {{__('Send Message')}}
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        @if($relatedPosts->isNotEmpty())
        <div class="px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
          <h4 class="mb-15px md:mb-5">
            <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-20 lg:text-size-35 font-bold">
              {{ __('related posts') .' '. __('about in') .' '. $post->postCategory->name }}
            </span>
          </h4>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($relatedPosts as $post)
            <div class="blog-warp">
              <a href="{{$post->url}}" title="{{ $post->title }}">
                <img src="{{ $post->image?->url ?? $post->getRandomImage() ?? '/assets/images/blog_img_1.jpg' }}" alt="{{ $post->image->alt ?? $post->title }}" class="img-fluid bordered-img w-full h-auto" loading="lazy" style="aspect-ratio: 1/1;" />
              </a>
              <div class="meta-box mt-4">
                <span class="text-primary-color-light dark:text-white-color">{{ $post->postCategory->name }}</span>
              </div>
              <h4 class="h4-md mb-3 mt-2"><a href="{{$post->url}}" title="{{ $post->title }}" class="text-primary-color-light dark:text-white-color hover:text-secondary-color">{{$post->title}}</a></h4>
              <p class="text-primary-color-light dark:text-white-color">{{ $post->excerpt }}</p>
            </div>
            @endforeach
          </div>
        </div>
        @endif

      </div>
    </div>
  </section>

</x-layouts.main>

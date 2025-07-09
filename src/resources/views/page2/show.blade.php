{{-- <x-layouts.main :title="$page->title">
    <x-banner>
        <h1>{{ $page->title }}</h1>
</x-banner>

<x-container>
  <div class="prose mt-8 mx-auto text-black">
    {!! $page->content !!}
  </div>
</x-container>
</x-layouts.main> --}}
<x-layouts.main :title="$page->title">
  <x-banner title="Portfolio Details" backgroundImage="{{ asset('/assets/img/breadcrumb/breadcrumb-bg.jpg') }}">
    <x-breadcrumbs :items="[
                ['label' => 'Home', 'url' => route('home')],
                ['label' => 'Portfolio Details', 'url' => '']
            ]" />
  </x-banner>
  <section id="blogs">
    <div class="py-60px md:py-20 lg:py-100px xl:py-30 dark:bg-black-color">
      <x-container>
        <div class="group relative wow fadeInUp" data-wow-delay=".3s">
          <div class="relative overflow-hidden">
            <div class="overflow-hidden px-15px md:px-25px lg:px-10 pt-30px md:pt-10 lg:pt-50px bg-cream-light-color dark:bg-primary-color-light">
              <img src="{{ asset('/assets/img/portfolio/modal-img.jpg') }}" alt="" class="w-full h-[400px]" />
            </div>
            <div class="pt-30px md:pt-10 lg:pt-60px">
              <div class="transition-all duration-500">
                <div class="relative z-10">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-5 gap-x-50px items-start px-15px md:px-25px lg:px-10 mb-10 md:mb-50px">
                    <div>
                      <h3 class="mb-10px">
                        <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-size-35 md:text-size-40 lg:text-size-45 font-bold">
                          DStudio
                        </span>
                      </h3>
                      <p class="text-primary-color-light dark:text-white-color mb-5 md:mb-7">
                        They are was greater open above shelter lets itself under appear sixth open gathering made upon can't own above midst gathering gathered he one us saying can't divide.
                      </p>
                      <x-button href="#" label="live preview" icon="fa-arrow-right" />
                    </div>

                    <x-portfolio.meta-grid>
                      <x-portfolio.meta-item label="Category" value="Web Design" />
                      <x-portfolio.meta-item label="Client" value="Artboard Studio" />
                      <x-portfolio.meta-item label="Start Date" value="August 20, 2023" />
                      <x-portfolio.meta-item label="Designer" value="Battour marketting" />
                    </x-portfolio.meta-grid>
                  </div>

                  <x-portfolio.gallery class="mb-10 md:mb-50px">
                    <x-portfolio.gallery-item src="{{ asset('/assets/img/portfolio-gallery/p-gallery-1.jpg') }}" />
                    <x-portfolio.gallery-item src="{{ asset('/assets/img/portfolio-gallery/p-gallery-2.jpg') }}" />
                    <x-portfolio.gallery-item src="{{ asset('/assets/img/portfolio-gallery/p-gallery-3.jpg') }}" />
                    <x-portfolio.gallery-item src="{{ asset('/assets/img/portfolio-gallery/p-gallery-4.jpg') }}" />
                  </x-portfolio.gallery>

                  <div class="px-15px md:px-25px lg:px-10">
                    <x-portfolio.section title="Project Description">
                      <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                        The goal is there are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
                      </p>
                      <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5">
                        There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.
                      </p>
                    </x-portfolio.section>

                    <x-portfolio.section>
                      <div class="flex gap-15px xl:gap-x-50px flex-wrap mb-10 md:mb-50px">
                        <h4 class="max-w-265px w-full">
                          <span class="text-primary-color-light dark:text-white-color uppercase relative z-0 text-xl font-bold">
                            The story
                          </span>
                        </h4>
                        <p class="text-primary-color-light dark:text-white-color mb-15px md:mb-5 max-w-3xl w-full">
                          There are many variations of passages of Lorem Ipsum available...
                        </p>
                      </div>
                    </x-portfolio.section>
                  </div>

                  <x-portfolio.navigation>
                    <x-portfolio.nav-item direction="prev" projectName="Sebastian" href="#" />
                    <x-portfolio.nav-item direction="next" projectName="Qwillo" href="#" />
                  </x-portfolio.navigation>
                </div>
              </div>
            </div>
          </div>
        </div>
      </x-container>
    </div>
  </section>
</x-layouts.main>

{{-- <x-layouts.main :title="'Portfolio Details'">
    <x-banner :background="url('../img/breadcrumb/breadcrumb-bg.jpg')" :overlay="true" :overlayOpacity="70">
        <h1>Portfolio Details</h1>

        <x-breadcrumbs>
            <x-breadcrumbs.item href="index.html">Home</x-breadcrumbs.item>
            <x-breadcrumbs.item active>Portfolio Details</x-breadcrumbs.item>
        </x-breadcrumbs>
    </x-banner>

    <x-container class="py-[60px] md:py-[100px] dark:bg-black-color">
        <div class="group relative wow fadeInUp" data-wow-delay=".3s">
            <div class="relative overflow-hidden">
                <div class="overflow-hidden px-[15px] md:px-[25px] lg:px-[10px] pt-[30px] md:pt-[50px] bg-cream-light-color dark:bg-primary-color-light">
                    <img src="{{ asset('/assets/assets/img/portfolio/modal-img.jpg') }}" alt="Project preview" class="w-full" />
</div>

<div class="pt-[30px] md:pt-[60px]">
  <div class="transition-all duration-500">
    <div class="relative z-10">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-5 gap-x-[50px] items-start px-[15px] md:px-[25px] lg:px-[10px] mb-[50px]">
        <div>
          <h3 class="mb-[10px]">
            <span class="text-primary-color-light dark:text-white-color capitalize relative z-0 text-[35px] md:text-[40px] lg:text-[45px] font-bold">
              DStudio
            </span>
          </h3>

          <p class="text-primary-color-light dark:text-white-color mb-[7px]">
            They are was greater open above shelter lets itself
            under appear sixth open gathering made upon can't
            own above midst gathering gathered he one us saying
            can't divide.
          </p>
          <div>
            <x-button href="#" variant="gradient-secondary" class="group/nested">
              live preview
              <x-icon name="heroicon-s-arrow-small-right" class="ml-[10px] -rotate-45 group-hover/nested:rotate-0 transition-all duration-300" />
            </x-button>
          </div>
        </div>

        <x-portfolio.meta-grid>
          <x-portfolio.meta-item label="Category">Web Design</x-portfolio.meta-item>
          <x-portfolio.meta-item label="Client">Artboard Studio</x-portfolio.meta-item>
          <x-portfolio.meta-item label="Start Date">August 20, 2023</x-portfolio.meta-item>
          <x-portfolio.meta-item label="Designer">Battour marketting</x-portfolio.meta-item>
        </x-portfolio.meta-grid>
      </div>

      <x-portfolio.gallery>
        <x-portfolio.gallery-item src="assets/img/portfolio-gallery/p-gallery-1.jpg" />
        <x-portfolio.gallery-item src="assets/img/portfolio-gallery/p-gallery-2.jpg" />
        <x-portfolio.gallery-item src="assets/img/portfolio-gallery/p-gallery-3.jpg" />
        <x-portfolio.gallery-item src="assets/img/portfolio-gallery/p-gallery-4.jpg" />
      </x-portfolio.gallery>

      <div class="px-[15px] md:px-[25px] lg:px-[10px]">
        <x-portfolio.section title="Project Description">
          <p class="text-primary-color-light dark:text-white-color mb-[15px] md:mb-[25px]">
            The goal is there are many variations of passages
            of Lorem Ipsum available, but the majority have
            suffered alteration in some form, by injected
            humour, or randomised words which don't look even
            slightly believable.
          </p>
          <p class="text-primary-color-light dark:text-white-color mb-[15px] md:mb-[25px]">
            There are many variations of passages of Lorem
            Ipsum available, but the majority have suffered
            alteration in some form, by injected humour, or
            randomised words which don't look even slightly
            believable.
          </p>
        </x-portfolio.section>

        <x-portfolio.section title="The Story" class="flex flex-col md:flex-row gap-[15px] xl:gap-[50px] mb-[50px]">
          <p class="text-primary-color-light dark:text-white-color max-w-3xl w-full">
            There are many variations of passages of Lorem
            Ipsum available, but the majority have suffered
            alteration in some form, by injected humour, or
            randomised words which don't look even slightly
            believable.
          </p>
        </x-portfolio.section>

        <x-portfolio.section title="Our Approach" class="flex flex-col md:flex-row gap-[15px] xl:gap-[50px]">
          <p class="text-primary-color-light dark:text-white-color max-w-3xl w-full">
            There are many variations of passages of Lorem
            Ipsum available, but the majority have suffered
            alteration in some form, by injected humour, or
            randomised words which don't look even slightly
            believable.
          </p>
        </x-portfolio.section>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<x-portfolio.navigation class="px-[15px] py-[30px] md:px-[25px] lg:px-[50px] lg:py-[35px]">
  <x-portfolio.nav-item direction="prev" project="Sebastian" />
  <x-portfolio.nav-item direction="next" project="Qwillo" />
</x-portfolio.navigation>
</x-container>
</x-layouts.main> --}}

<x-layouts.main title="Contact">
  <x-banner :title="$page->title"
    backgroundImage="{{ $page->image?->url ?? asset('/assets/img/breadcrumb/breadcrumb-bg.jpg') }}">

    <x-breadcrumbs :items="[
          ['label' => 'الرئيسية', 'url' => route('home')],
          ['label' => __($page->slug), 'url' => route('dynamic.route', ['slug' => $page->slug])],]" />

  </x-banner>

  {{-- <x-container>
        <div class="max-w-[600px] mt-8 mx-auto">
            @livewire('contact-form')
        </div>
    </x-container> --}}

  <!-- contact area -->
  <section id="contact">
    <div class="bg-cream-light-color dark:bg-black-color py-60px md:py-20 lg:py-100px xl:py-30">
      <div class="container">
        <div class="flex flex-col-reverse md:grid md:grid-cols-12 md:items-center gap-x-6 gap-y-10">
          <!-- section heading -->
          <div class="md:col-start-1 md:col-span-7 lg:col-span-6">
            <div class="wow fadeInLeft" data-wow-delay=".3s">
              <form id="contact-form"
                class="contact px-15px py-30px md:px-5 lg:px-30px lg:py-10 xl:px-10 bg-white-color dark:bg-primary-color-light rounded-15px">
                <div class="text-center mb-25px">
                  <h2
                    class="text-3xl md:text-size-35 lg:text-size-40 xl:text-size-45 bg-gradient-text-light dark:bg-gradient-text bg-clip-text xl:leading-1.2 text-transparent mb-15px">
                    Let’s work together!
                  </h2>
                  <p class="text-primary-color-light dark:text-body-color wow fadeInLeft" data-wow-delay=".4s">
                    I design and code beautifully simple things and i love
                    what i do. Just simple like that!
                  </p>
                </div>
                <!-- inputs -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-15px">
                  <!-- first name -->
                  <div>
                    <input name="conName" id="conName" type="text" placeholder="First name"
                      class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                  </div>
                  <!-- Last name -->
                  <div>
                    <input name="conLName" id="conLName" type="text" placeholder="Last name"
                      class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                  </div>
                  <!-- Email address -->
                  <div>
                    <input name="conEmail" id="conEmail" type="email" placeholder="Email address"
                      class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                  </div>
                  <!-- Phone number -->
                  <div>
                    <input name="conPhone" id="conPhone" type="text" placeholder="Phone number"
                      class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1" />
                  </div>
                  <div class="form_group sm:col-start-1 sm:col-span-2">
                    <select name="conService" id="conService" class="tj-nice-select">
                      <option value="" selected="" disabled="">
                        Choose Service
                      </option>
                      <option value="braning">Branding Design</option>
                      <option value="web">Web Design</option>
                      <option value="uxui">UI/UX Design</option>
                      <option value="app">App Design</option>
                    </select>
                  </div>
                  <div class="sm:col-start-1 sm:col-span-2">
                    <textarea name="conMessage" id="conMessage" cols="1" rows="10" placeholder="Message"
                      class="text-white-color w-full px-5 py-14px border bg-cream-light-color dark:bg-black-color focus:border-primary-color rounded-lg outline-none focus:outline-none transition-all duration-300 placeholder:text-gray-color leading-1"></textarea>
                  </div>
                  <div class="sm:col-start-1 sm:col-span-2">
                    <button type="submit"
                      class="text-size-15 font-bold text-white-color capitalize py-17px px-35px bg-200 bg-gradient-secondary hover:bg-[-100%] rounded-full leading-1 transition-all duration-300">
                      Send Message
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- experience single area -->
          <div class="md:col-start-8 md:col-span-5">
            <div class="contact-info-list">
              <ul class="flex flex-col gap-y-10">
                <li class="flex flex-wrap items-center gap-25px position-relative wow fadeInRight" data-wow-delay=".4s">
                  <div
                    class="icon-box text-xl w-50px h-50px text-white-color flex justify-center items-center flex-col bg-gradient-primary-2 rounded-full leading-1">
                    <i class="flaticon-phone-call leading-1 mt-1"></i>
                  </div>
                  <div class="text-box">
                    <p class="text-primary-color-light dark:text-white-color mb-1">
                      Phone
                    </p>
                    <a href="tel:0123456789"
                      class="text-primary-color-light dark:text-white-color text-lg lg:text-xl font-medium hover:text-primary-color">+01
                      123 654 8096</a>
                  </div>
                </li>
                <li class="flex flex-wrap items-center gap-25px position-relative wow fadeInRight" data-wow-delay=".5s">
                  <div
                    class="icon-box text-xl w-50px h-50px text-white-color flex justify-center items-center flex-col bg-gradient-primary-2 rounded-full leading-1">
                    <i class="flaticon-mail-inbox-app leading-1 mt-1"></i>
                  </div>
                  <div class="text-box">
                    <p class="text-primary-color-light dark:text-white-color mb-1">
                      Email
                    </p>
                    <a href="mailto:mail@mail.com"
                      class="text-primary-color-light dark:text-white-color text-lg lg:text-xl font-medium hover:text-primary-color">gerolddesign@mail.com</a>
                  </div>
                </li>
                <li class="flex flex-wrap items-center gap-25px position-relative wow fadeInRight" data-wow-delay=".6s">
                  <div
                    class="icon-box text-xl w-50px h-50px text-white-color flex justify-center items-center flex-col bg-gradient-primary-2 rounded-full leading-1">
                    <i class="flaticon-location leading-1 mt-1"></i>
                  </div>
                  <div class="text-box">
                    <p class="text-primary-color-light dark:text-white-color mb-1">
                      Address
                    </p>
                    <a href="#"
                      class="text-primary-color-light dark:text-white-color text-lg lg:text-xl font-medium hover:text-primary-color">Warne
                      Park Street Pine, <br />
                      FL 33157, New York</a>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</x-layouts.main>